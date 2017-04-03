<?php
function my_cool_plugin_settings_page() {

	$prices = get_option( 'prices' );
	$ranges = array(
		'500-1000', '1000-2000', '2000-3000', '3000-4000', '4000-5000', '5000-6000', '6000-7000',
		'7000-8000', '8000-9000', '9000-10000',
		'10000-11000', '11000-12000', '12000-13000', '13000-14000', '14000-15000', '15000-17000', '17000-20000',
		'20000-25000', '25000-30000', '30000-35000', '35000-40000', '40000-45000', '45000-50000', '55000-60000',
		'60000-65000', '65000-70000', '75000-80000', '80000-90000', '90000-100000', '100000-120000', '120000-150000',
		'150000-200000', '200000-250000', '250000-300000', '300000-350000', '350000-400000', '400000-450000',
		'450000-500000', '500000-600000', '600000-700000', '700000-800000',	'800000-1000000', '1000000-1500000',
		'1500000-2000000', '2000000-3000000', '3000000-4000000', '4000000-5000000', '5000000-10000000'
	);
	//wp_clear_scheduled_hook( 'showteka_hook', array('egayi@yandex.ru', 'Тест тема', 'Тест сообщение') );

	/*$offers = get_option( 'offers' );
	$agents = get_option( 'api_agents' );
	$loop = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );

	?><pre>loop: <?php print_r($loop->posts); ?></pre><br><?php

	while ( $loop->have_posts() ) : $loop->the_post();
	$theid = get_the_ID();
	$api_id = get_post_meta($theid, 'wccaf_api_id', true );
	echo '<br>the id: ' . $theid . '<br>';
	echo '<br>api id: ' . $api_id . '<br>';


	if ($api_id) {

		//$args = array( 'post_type' => 'product_variation', 'numberposts' => -1, 'post_parent' => $theid );
		//$variations = get_posts( $args );
		$offer_array = sht_api_request('<RepertoireId>'. $api_id .'</RepertoireId>', 'GetOfferListByRepertoireId');

		foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {

			if (in_array($offer->AgentId, $agents)) {

				if (array_key_exists((string) $offer->Id, $offers) && (array) $offer->SeatList->Item != $offers[(string) $offer->Id]) {

					$offers[(string)$offer->Id] = (array)$offer->SeatList->Item;
				}
				else {

					$offers[(string)$offer->Id] = (array)$offer->SeatList->Item;
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
update_option( 'offers', $offers );
wp_reset_query();*/
$offers = get_option( 'offers' );
echo 'offers:   '. count($offers);
?>
<div class="wrap">
	<div class="postbox-container">
		<h1>Наценки</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
			<?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
			<table class="form-table">
				<?php foreach ($ranges as $range) :?>
					<tr valign="top">
						<th scope="row"><?php echo $range ?></th>
						<td><input type="number" name="prices[<?php echo $range ?>]" value="<?php echo $prices[$range] ?>" /></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
</div>
<?php
}
?>
