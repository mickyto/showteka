<?php
function showteka_update_tickets( $to, $subject ) {

  $start = microtime(true);
  $options = get_option( 'options' );
  $loop = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );
  $msg_info = array(
    'api_offers' => 0,
    'sht_offers' => 0,
    'added'      => 0,
    'added_vars' => 0
  );

  while ( $loop->have_posts() ) : $loop->the_post();
  $theid = get_the_ID();
  $api_id = get_post_meta($theid, 'wccaf_api_id', true );
  $msg_info['api_offers']++;

  if ($api_id) {

    $msg_info['sht_offers']++;

    if (!in_array($api_id, $options)) {
      $options[$api_id] = get_the_title();
      update_option( 'options', $options );
    }

    $args = array(
      'post_type'     => 'product_variation',
      'numberposts'   => -1,
      'post_parent'   => $theid
    );
    $variations = get_posts( $args );

    if (count($variations) == 0) {

      $msg_info['added']++;

      $date_attribute = array( // Set this attributes array to a key to using the prefix 'pa'
        'pa_date' => array(
          'name'         => 'pa_date',
          'value'        => '',
          'is_visible'   => '0',
          'is_variation' => '1',
          'is_taxonomy'  => '1'
        )
      );
      update_post_meta( $theid, '_product_attributes', $date_attribute); // Attach the above array to the new posts meta data key '_product_attributes'

      $dates = array();
      $offer_array = sht_api_request('<RepertoireId>'. $api_id .'</RepertoireId>', 'GetOfferListByRepertoireId');
      if (gettype($offer_array) == 'string' || !count($offer_array->ResponseData->ResponseDataObject->Offer)) continue;

      foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {
        $dates[] = (string) $offer->EventDateTime;
      }

      $dates = array_unique($dates);
      wp_set_object_terms($theid, $dates, 'pa_date', true);

      foreach ($dates as $date) {
        $msg_info['added_vars']++;
        $variation_post = array(
          'post_author'   => 1,
          'post_status'   => 'publish',
          'post_parent'   => $theid,
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
endwhile;
$time_elapsed_secs = microtime(true) - $start;
$msg = '<table border = "1" cellpadding="10">
<tr><td>Всего мероприятий из апи</td><td>' . $msg_info['api_offers'] . '</td><tr>
<tr><td>Мероприятия из апи</td><td>' . $msg_info['sht_offers'] . '</td><tr>
<tr><td>Мероприятия без дат</td><td>' . $msg_info['added'] . '</td><tr>
<tr><td>Добавленные даты</td><td>' . $msg_info['added_vars'] . '</td><tr>
<tr><td>Время работы скрипта</td><td>' . $time_elapsed_secs . '</td><tr></table>';
$headers = array('Content-Type: text/html; charset=UTF-8');
wp_mail( $to, $subject, $msg, $headers );
}
?>
