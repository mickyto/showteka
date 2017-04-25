<?php
function process_sh_add_tickets() {

  $tickets = get_option( 'tickets' );
  write_log($_POST);

  if (isset($_POST['events'])) {
    foreach ($_POST['events'] as $key) {
      if (!isset($tickets[$key])) {
        $tickets[$key] = array();
      }
    }
  }

  $ids_array = preg_grep("/\d+/", array_keys($_POST));
  write_log($ids_array);
  if (!empty($ids_array)) {
    foreach ($ids_array as $key => $value) {

      if (count(get_post_meta( $value, '_product_attributes', true)) == 0) {
        $date_attribute = array(
          'pa_date' => array(
            'name'         => 'pa_date',
            'value'        => '',
            'is_visible'   => '0',
            'is_variation' => '1',
            'is_taxonomy'  => '1'
          )
        );
        add_post_meta( $value, '_product_attributes', $date_attribute);
      }

      //wp_set_object_terms($key, $dates, 'pa_date', true);



      $terms = wp_get_post_terms( $key, 'pa_date' );
      write_log($terms);


      $places = str_replace(' ', '', $_POST[$value]['places']);
      $tickets[$value][] = array(
        'date'   => $_POST[$value]['date'] . ' ' . $_POST[$value]['time'] . ':00',
        'sector' => $_POST[$value]['sector'],
        'row'    => $_POST[$value]['row'],
        'places' => explode(',', $places),
        'price'  => $_POST[$value]['price'],
      );
    }
  }

  update_option( 'tickets', $tickets );
  wp_redirect( admin_url( 'admin.php?page=add_tickets&m=1' ) );
  exit;
}
?>
