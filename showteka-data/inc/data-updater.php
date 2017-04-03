<?php
function showteka_update_tickets( $to, $subject, $msg ) {

  $offers = get_option( 'offers' );
  $agents = get_option( 'api_agents' );
  $loop = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );
  $all_offers_from_api = 0;
  $all_showteka_offers = 0;
  $all_chenged_offers = 0;
  $all_added_offers = 0;


  while ( $loop->have_posts() ) : $loop->the_post();
  $theid = get_the_ID();
  $api_id = get_post_meta($theid, 'wccaf_api_id', true );

  if ($api_id) {

    $offer_array = sht_api_request('<RepertoireId>'. $api_id .'</RepertoireId>', 'GetOfferListByRepertoireId');

    foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {

      $all_offers_from_api++;
      if (in_array($offer->AgentId, $agents)) {
        $all_showteka_offers++;

        if (array_key_exists((string) $offer->Id, $offers[$api_id])) {
          if ((array) $offer->SeatList->Item != $offers[$api_id][(string) $offer->Id]) {
            $all_chenged_offers++;
            $offers[$api_id][(string)$offer->Id] = (array)$offer->SeatList->Item;
          }
        }
        else {
          $all_added_offers++;
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
  }
endwhile;
$msg = '<table border = "1"><tr><td>Всего предложений</td><td>' . count($offers) . '</td><tr>
<tr><td>Всего агентов</td><td>' . count($agents) . '</p><br>
<tr><td>Всего мероприятий из апи</td><td>' . $all_offers_from_api . '</td><tr>
<tr><td>Всего предложений для шоутеки</td><td>' . $all_showteka_offers . '</td><tr>
<tr><td>Всего измененных предложений</td><td>' . $all_chenged_offers . '</td><tr>
<tr><td>Всего добавленных предложений</td><td>' . $all_added_offers . '</td><tr></table>';
$headers = array('Content-Type: text/html; charset=UTF-8');
wp_mail( $to, 'Обновление предложений', $msg, $headers );
}
?>
