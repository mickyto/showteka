<?php
function showteka_update_tickets( $to, $subject ) {

  $start = microtime(true);
  $offers = get_option( 'offers' );
  $loop = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );
  $msg_info = array(
    'api_offers' => 0,
    'sht_offers' => 0,
    'changed'    => 0,
    'added'      => 0,
    'deleted'    => 0
  );

  while ( $loop->have_posts() ) : $loop->the_post();
  $theid = get_the_ID();
  $api_id = get_post_meta($theid, 'wccaf_api_id', true );

  if ($api_id) {

    $api_offer_ids = array();
    $offer_array = sht_api_request('<RepertoireId>'. $api_id .'</RepertoireId>', 'GetOfferListByRepertoireId');

    foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {

      $api_offer_ids[] = (string) $offer->Id;
      $msg_info['api_offers']++;
      if (in_array($offer->AgentId, get_option( 'api_agents' ))) {
        $msg_info['sht_offers']++;

        if (array_key_exists((string) $offer->Id, $offers[$api_id])) {
          if ((array) $offer->SeatList->Item != $offers[$api_id][(string) $offer->Id]) {
            $msg_info['changed']++;
            $offers[$api_id][(string)$offer->Id] = (array)$offer->SeatList->Item;
          }
        }
        else {
          $msg_info['added']++;
          $offers[$api_id][(string)$offer->Id] = (array)$offer->SeatList->Item;
          $new_variations = array();
          $variation = array(
            'sector'  => (string) $offer->SectorId,
            'row'     => (string) $offer->Row,
            'date'    => (string) $offer->EventDateTime,
            'price'   => (string) $offer->AgentPrice,
            'offer'   => (string) $offer->Id
          );
          array_push($new_variations, $variation);
          insert_product_attributes($theid, $new_variations);
          insert_product_variations($theid, $new_variations);
        }
      }
    }

    $removed_offers = array_diff( array_keys( $offers[$api_id] ), $api_offer_ids );
    $msg_info['deleted'] = $msg_info['deleted'] + count($removed_offers);
    if (count($removed_offers) != 0) {
			foreach ($removed_offers as $key) {
				unset($offers[$api_id][$key]);

        $args = array(
          'meta_key' => 'wccaf_offer_id',
          'meta_value' => $key,
          'post_type' => 'product_variation',
        );
        $posts = get_posts($args);
        wp_delete_post( $posts[0]->ID, true );
			}
    }
    update_option( 'offers', $offers );
  }
endwhile;
$time_elapsed_secs = microtime(true) - $start;
$msg = '<table border = "1" cellpadding="10">
<tr><td>Всего мероприятий из апи</td><td>' . count($offers) . '</td><tr>
<tr><td>Мероприятия из апи</td><td>' . $msg_info['api_offers'] . '</td><tr>
<tr><td>Предложения для шоутеки</td><td>' . $msg_info['sht_offers'] . '</td><tr>
<tr><td>Измененные предложения</td><td>' . $msg_info['changed'] . '</td><tr>
<tr><td>Удаленные предложения</td><td>' . $msg_info['deleted'] . '</td><tr>
<tr><td>Добавленные предложения</td><td>' . $msg_info['added'] . '</td><tr>
<tr><td>Время работы скрипта</td><td>' . $time_elapsed_secs . '</td><tr></table>';
$headers = array('Content-Type: text/html; charset=UTF-8');
wp_mail( $to, $subject, $msg, $headers );
}
?>
