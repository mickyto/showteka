<?php
function process_sh_api_options() {

  $options = get_option( 'options' );
  $events = count($_POST['event']) != 0 ? array_keys( $_POST['event'] ) : array();
  $removed = array_diff($options, $events);
  $added = array_diff($events, $options);
  update_option( 'options', $events );

  if (count($added) !== 0) {

    foreach ($added as $key) {

      $post = array(
        'post_author'  => 1,
        'post_status'  => 'publish',
        'post_title'   => $_POST['event'][$key],
        'post_type'    => 'product'
      );

      $post_id = wp_insert_post($post);

      add_post_meta( $post_id, 'wccaf_api_id', $key);
      add_post_meta( $post_id, 'wccaf_place', $_POST['place-' . $key]);
      add_post_meta( $post_id, 'wccaf_address', $_POST['address-' . $key]);
      add_post_meta( $post_id,'_visibility','visible');
      wp_set_object_terms($post_id, 'variable', 'product_type');

      $date_attribute = array( // Set this attributes array to a key to using the prefix 'pa'
        'pa_date' => array(
          'name'         => 'pa_date',
          'value'        => '',
          'is_visible'   => '0',
          'is_variation' => '1',
          'is_taxonomy'  => '1'
        )
      );
      add_post_meta( $post_id, '_product_attributes', $date_attribute); // Attach the above array to the new posts meta data key '_product_attributes'

      $dates = array();
      $offer_array = sht_api_request('<RepertoireId>'. $key .'</RepertoireId>', 'GetOfferListByRepertoireId');
      foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {
          $dates[] = (string) $offer->EventDateTime;
      }
      $dates = array_unique($dates);
      wp_set_object_terms($post_id, $dates, 'pa_date', true);

      foreach ($dates as $date) {

        $variation_post = array(
          'post_author'   => 1,
          'post_status'   => 'publish',
          'post_parent'   => $post_id,
          'post_type'     => 'product_variation',
        );

        $variation_post_id = wp_insert_post($variation_post);
        $date_term = get_term_by('name', $date, 'pa_date');

        add_post_meta($variation_post_id, 'attribute_pa_date', $date_term->slug);
        add_post_meta($variation_post_id, '_price', '0');
        add_post_meta($variation_post_id, '_regular_price', '0');
      }
    }
  }
  else if (count($removed) !== 0) {
    foreach ($removed as $key) {
      $args = array(
        'meta_key' => 'wccaf_api_id',
        'meta_value' => $key,
        'post_type' => 'product',
        'post_status' => 'any',
        'posts_per_page' => -1
      );
      $posts = get_posts($args);
      wp_delete_post( $posts[0]->ID, true );
    }
  }
  wp_redirect( admin_url( 'admin.php?page=showteka_api&m=1&re=' . count($removed) . '&ad=' . count($added) ) );
  exit;
}
?>
