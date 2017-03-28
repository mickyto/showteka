<?php

function showteka_update_tickets( $to, $subject, $msg ) {
  set_time_limit(0);

  /*$offers = get_option( 'offers' );
	$agents = get_option( 'api_agents' );
	$loop = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );
  //$options = get_option( 'options' );
  // $loop_2 = new WP_Query( array( 'post_type' => 'product_variation', 'posts_per_page' => -1 ) );
  //
  // foreach ($loop_2->posts as $vars) {
  //   if (!get_post( $vars->post_parent )) {
  //     wp_delete_post( $vars->ID, true );
  //   }
  // }

  $test = array();
  while ( $loop->have_posts() ) : $loop->the_post();
  $theid = get_the_ID();
  $api_id = get_post_meta($theid, 'wccaf_api_id', true );

  if ($api_id) {

    // if (!in_array($api_id, $options)) {
    //   $options[] = $api_id;
    //   update_option( 'options', $options );
    // }

    $args = array(
      'post_type'     => 'product_variation',
      'numberposts'   => -1,
      'post_parent'   => $theid
    );
    $variations = get_posts( $args );
    $offer_array = sht_api_request('<RepertoireId>'. $api_id .'</RepertoireId>', 'GetOfferListByRepertoireId');

    foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {

			if (in_array($offer->AgentId, $agents)) {

				$seats = array();

        if (array_key_exists((string) $offer->Id, $offers) && (array) $offer->SeatList->Item != $offers[(string) $offer->Id]) {

					$offers[(string)$offer->Id] = (array)$offer->SeatList->Item;

					$unknown_seats = (array) $offer->SeatList->Item;

					foreach ($variations as $variation) {

						$seat = get_post_meta($variation->ID, 'attribute_pa_place', true);
						if (!in_array($seat, $unknown_seats)) {
							wp_delete_post( $variation->ID, true );
						}
						else {
							$unknown_seats = array_diff($unknown_seats, [$seat]);
						}
					}
					$seats = $unknown_seats;
				}
				else {
					$offers[(string)$offer->Id] = (array)$offer->SeatList->Item;
          $seats = (array) $offer->SeatList->Item;
        }
        $test = $seats;

				if (count($seats) !== 0) {
					$new_variations = array();
					foreach ($seats as $new_seat) {
						array_push($new_variations, sub_variation($offer, $new_seat));
					}
					insert_product_attributes($theid, $new_variations);
					insert_product_variations($theid, $new_variations);
				}
			}
		}
  }


endwhile;
update_option( 'offers', $offers );
wp_reset_query();

wp_mail( $to, $agents[agentMOST], 'post17   '. implode(" ",$test));*/
}
?>
