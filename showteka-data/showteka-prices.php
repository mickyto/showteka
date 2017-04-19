<?php
function my_cool_plugin_settings_page() {

	$prices = get_option( 'prices' );
	$ranges = array(
		'500-1000', '1000-2000', '2000-3000', '3000-4000', '4000-5000', '5000-6000', '6000-7000',
		'7000-8000', '8000-9000', '9000-10000',
		'10000-11000', '11000-12000', '12000-13000', '13000-14000', '14000-15000', '15000-17000', '17000-20000',
		'20000-25000', '25000-30000', '30000-35000', '35000-40000', '40000-45000', '45000-50000', '50000-55000', '55000-60000',
		'60000-65000', '65000-70000', '75000-80000', '80000-90000', '90000-100000', '100000-120000', '120000-150000',
		'150000-200000', '200000-250000', '250000-300000', '300000-350000', '350000-400000', '400000-450000',
		'450000-500000', '500000-600000', '600000-700000', '700000-800000',	'800000-1000000', '1000000-1500000',
		'1500000-2000000', '2000000-3000000', '3000000-4000000', '4000000-5000000', '5000000-10000000'
	);

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
