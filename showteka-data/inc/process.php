<?php
function process_sh_api_options() {

  $options = get_option( 'options' );
  $offers = get_option( 'offers' );
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
      $variations = array();
      $current_offers = array();
      $offer_array = sht_api_request('<RepertoireId>'. $key .'</RepertoireId>', 'GetOfferListByRepertoireId');
      foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {

        if (in_array($offer->AgentId, get_option( 'api_agents' ))) {

          $current_offers[(string)$offer->Id] = (array)$offer->SeatList->Item;

          $variation = array(
            'sector'  => (string) $offer->SectorId,
            'row'     => (string) $offer->Row,
            'date'    => (string) $offer->EventDateTime,
            'price'   => (string) $offer->AgentPrice,
            'offer'   => (string) $offer->Id
          );
          array_push($variations, $variation);
        }
      }

      $offers[$key] = $current_offers;

      add_post_meta( $post_id, 'wccaf_api_id', $key);
      add_post_meta( $post_id, 'wccaf_place', $_POST['place-' . $key]);
      add_post_meta( $post_id, 'wccaf_address', $_POST['address-' . $key]);
      add_post_meta( $post_id,'_visibility','visible');
      wp_set_object_terms($post_id, 'variable', 'product_type');
      insert_product_attributes($post_id, $variations);
      insert_product_variations($post_id, $variations);
    }
  }
  else if (count($removed) !== 0) {
    foreach ($removed as $key) {
      unset($offers[$key]);
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
  update_option( 'offers', $offers );
  wp_redirect( admin_url( 'admin.php?page=showteka_api&m=1&re=' . count($removed) . '&ad=' . count($added) ) );
  exit;
}
?>
