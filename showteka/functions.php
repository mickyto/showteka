<?php
// Enable menu settings
add_theme_support('menus');
add_theme_support('widgets');

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

// Fix Woocommerce styles urls
add_action('wp_print_styles', 'enqueueStylesFix', 100);
function enqueueStylesFix() {
	if (!is_admin()) {
		global $wp_styles;
		foreach ((array) $wp_styles->registered as $script) {
			if (stripos($script->src, '//') === 0) {
				$script->src = str_replace('//', 'http://', $script->src);
			}
		}
	}
}

// Remove the smallscreen optimisation
add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
function jk_dequeue_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-smallscreen'] );
	return $enqueue_styles;
}

add_action('admin_enqueue_scripts', 'admin_style');
function admin_style() {
	wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin.css');
}

add_action('wp_enqueue_scripts', 'c2h_styles');
function c2h_styles() {
	wp_enqueue_style('style_css', get_template_directory_uri() . '/style.css?v=4');
}

add_action('wp_enqueue_scripts', 'c2h_scripts');
function c2h_scripts() {
	wp_enqueue_script('to_top', get_template_directory_uri() . '/scripts/toTop.js', 'jquery', '', false );
}

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
	$fields['order']['order_comments']['placeholder'] = '';
	$fields['billing']['billing_address_1']['label'] = 'Адрес доставки';
	$fields['billing']['billing_address_1']['placeholder'] = '';
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_address_2']);
	unset($fields['billing']['billing_state']);
	unset($fields['billing']['billing_postcode']);
	unset($fields['billing']['billing_city']);
	return $fields;
}

// Make billing fields not required in checkout
add_filter( 'woocommerce_billing_fields', 'wc_npr_filter_phone', 10, 1 );
function wc_npr_filter_phone( $address_fields ) {
	$address_fields['billing_state']['required'] = false;
	$address_fields['billing_last_name']['required'] = false;
	$address_fields['billing_address_1']['required'] = false;
	$address_fields['billing_city']['required'] = false;
	$address_fields['billing_postcode']['required'] = false;
	$address_fields['billing_country']['required'] = false;
	return $address_fields;
}

// Remove actions
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_variable_add_to_cart');
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_product_add_to_cart_text' );
function woo_custom_product_add_to_cart_text() {
	global $product;
	if (!$product->is_in_stock()) {
		return __( 'Оставить заявку', 'woocommerce' );
	}
	return __( 'Купить билеты', 'woocommerce' );
}

// Remove reviews
add_filter('woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98);
function wcs_woo_remove_reviews_tab($tabs) {
	unset($tabs['reviews']);
	unset( $tabs['description'] );
	return $tabs;
}

// Remove related products
add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);
function wc_remove_related_products($args) {
	return array();
}

// Hide page title
add_filter( 'woocommerce_show_page_title' , 'woo_hide_page_title' );
function woo_hide_page_title() {
	return false;
}

// Display 50 products per page
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 50;' ), 20 );

// Add banners to shop and category page
add_action( 'woocommerce_after_shop_loop', 'add_banners', 10 );
function add_banners() {
	$ss_url = get_stylesheet_directory_uri();
	echo '<div class="banner-data" style="display: none">' . do_shortcode('[crellyslider alias="баннер-бол-1"]') . '</div>';
	echo '<div class="banner-data" style="display: none">' . do_shortcode('[crellyslider alias="баннер-бол-2"]') . '</div>';
	echo '<div class="banner-data" style="display: none">' . do_shortcode('[crellyslider alias="баннер-бол-3"]') . '</div>';
	echo '<script src="' . $ss_url . '/scripts/insertBanners.js?v=6"></script>';
}

add_action( 'woocommerce_archive_description', 'category_loop_title', 12 );
function category_loop_title() {
	if ( is_product_category() ){
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		if ($cat->parent == 10) {
			echo '<div class="popular"><p class="title">Афиша мероприятий театра:</p><hr></div>';
		}
	}
}

// Display popular product
add_action( 'woocommerce_after_single_product_summary', 'add_popular_events', 50 );
function add_popular_events() {
	echo "<div class=\"popular\">
	<p class=\"title\">Популярные мероприятия:</p>
	<hr />"
	. do_shortcode('[product_category category="Популярные"]') .
	"</div>";
}

// Remove product quantity for order
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );
function wc_remove_all_quantity_fields( $return, $product ) {
	return true;
}

// Add vertical banners
add_action( 'woocommerce_before_single_product', 'add_vertical_banner', 10 );
function add_vertical_banner() {
	echo "<div class=\"banner-vert\">" . do_shortcode('[crellyslider alias="баннер-верт-1"]'), do_shortcode('[crellyslider alias="баннер-верт-2"]') . "</div>";
}

// Write logs to debug.log file on the server
// if ( ! function_exists('write_log')) {
// 	function write_log ( $log )  {
// 		if ( is_array( $log ) || is_object( $log ) ) {
// 			error_log( print_r( $log, true ) );
// 		} else {
// 			error_log( $log );
// 		}
// 	}
// }

add_action('woocommerce_calculate_totals', 'calculate_totals', 10, 1);
function calculate_totals($Cart) {
	$sht_total = $Cart->cart_contents_total;

	foreach ($Cart->cart_contents as $key => $value) {
		$price = $value['variation']['attribute_pa_price'];
		$sht_total += $price;
		$Cart->cart_contents[$key]['data']->price = $price;
		$Cart->cart_contents[$key]['line_total'] = $price;
		$Cart->cart_contents[$key]['line_subtotal'] = $price;
	}
	$Cart->cart_contents_total = $sht_total;
	return $Cart;
}

// Make order throught API
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');

function my_custom_checkout_field_process() {

	$cart_items = WC()->cart->cart_contents;

	foreach ($cart_items as $cart_item) {

		$sectors = get_option( 'sectors' );
		$repertoire_id = get_post_meta( $cart_item['product_id'], 'wccaf_api_id', true );
		$attribute_term = get_term_by('slug', $cart_item['variation']['attribute_pa_date'], 'pa_date');
		$sector_id = array_search($cart_item['variation']['attribute_pa_sector'], $sectors);

		// Check if ofаer item is available
		$offer_request = sht_api_request('
		<RepertoireId>'. $repertoire_id .'</RepertoireId>
		<EventDateTime>'. $attribute_term->name .'</EventDateTime>
		<SectorId>'. $sector_id .'</SectorId>
		<Row>'. $cart_item['variation']['attribute_pa_row'] .'</Row>
		<Seat>'. $cart_item['variation']['attribute_pa_place'] .'</Seat>',
		'GetOfferIdBySeatInfo');

		if ($offer_request->ResponseResult->Code == 0) {
			$offer_id = (string) $offer_request->ResponseData->ResponseDataObject->OfferId;
			$order = sht_api_request('
			<OfferId>'. $offer_id .'</OfferId>
			<SeatList><Item>'. $cart_item['_custom_options'] .'</Item></SeatList>',
			'MakeOrder');
		}
		else {
			wc_add_notice( __( 'Некоторые места или мероприятия недоступны' ), 'error' );
			wp_enqueue_script( 'mark-cart-items', get_template_directory_uri() . '/scripts/cartItemsMarker.js', 'jquery', '', true );
		}
	}
}

// Show products on shop page
add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
function custom_pre_get_posts_query( $q ) {

	if ( ! $q->is_main_query() ) return;
	if ( ! $q->is_post_type_archive() ) return;
	if ( ! is_admin() && is_shop() ) {
		$q->set( 'tax_query', array(array(
			'taxonomy' => 'product_cat',
			'field' => 'slug',
			'terms' => 'main'
		)));
	}
}

function place_handler($places) {
	$groups = array();
	for($i = 0; $i < count($places); $i++)
	{
		if($i > 0 && ($places[$i - 1] == $places[$i] - 1))
		array_push($groups[count($groups) - 1], $places[$i]);
		else // First value or no match, create a new group
		array_push($groups, array($places[$i]));
	}
	$place_strings = array();
	foreach($groups as $group) {
		if(count($group) == 1) // Single value
		$place_strings[] = $group[0];
		else
		$place_strings[] = $group[0] . "-" . $group[count($group) - 1];
	}
	return implode(", ", $place_strings);
}

function format_date($date) {
	$months = array('января', 'февраля', 'марта', 'апреля',
	'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');

	$readable_date = substr($date,8,2) . ' '
	. $months[(int) substr($date,5,2) - 1] . ' '
	. substr($date,0,4) . ' | '
	. substr($date,11,2) . ':'
	. substr($date,14,2);
	return $readable_date;
}

function woocommerce_grouped_add_to_cart() {
	global $product, $post;

	$place = get_post_meta(get_the_ID(), 'wccaf_place', true);
	if ($place) { ?>
		<div class="where"><q><?php echo $place ?></q>
			<p class="address"><?php echo get_post_meta(get_the_ID(), 'wccaf_address', true) ?></p>
		</div>
		<div class="date"><?php echo get_post_meta(get_the_ID(), 'wccaf_date', true) ?></div><?php
	} ?>

	<div class="description"><?php echo the_content(); ?></div><?php

	$attachment_ids = $product->get_gallery_attachment_ids();
	if ($attachment_ids) { ?>
		<div class="action-buttons">
			<a id="schema" href="<?php echo wp_get_attachment_url($attachment_ids[0]) ?>" class="purple-b">Схема зала</a>
		</div><?php
	}

	$event_ids = explode(",", get_post_meta( $post->ID, 'wccaf_event_ids', true ));

	if (empty($event_ids[0])) return; ?>

	<div id="announce"><?php

	foreach ($event_ids as $api_id) {

		$args = array(
			'meta_key' => 'wccaf_api_id',
			'meta_value' => $api_id,
			'post_type' => 'product',
			'post_status' => 'any',
			'posts_per_page' => -1
		);
		$posts = get_posts($args);

		if (!isset($posts[0])) continue;

		$terms = wp_get_post_terms( $posts[0]->ID, 'pa_date' );

		if (!isset($terms[0])) continue;

		foreach ($terms as $date_term) {

			$date = format_date($date_term->name); ?>

			<div class="var">
				<div class="offer-date">
					<table>
						<td class="post-title">
							<h2><?php echo $posts[0]->post_title; ?></h2><br>
							<p><?php echo $date ?></p>
						</td>
						<td class="post-button">
							<a href="<?php echo get_post_permalink($posts[0]->ID); ?>?date=<?php echo $date_term->name; ?>">Купить билеты</a>
						</td>
					</table>
				</div>
			</div><?php
		}
	} ?>
</div><?php
}

// Main fuction for showing product information
function woocommerce_variable_add_to_cart() {
	global $product, $post;

	$place = get_post_meta(get_the_ID(), 'wccaf_place', true);
	if ($place) { ?>
		<div class="where"><q><?php echo $place ?></q>
			<p class="address"><?php echo get_post_meta(get_the_ID(), 'wccaf_address', true) ?></p>
		</div><?php
	} ?>
	<div class="date"><?php echo get_post_meta(get_the_ID(), 'wccaf_date', true) ?></div>
	<div class="description"><?php echo the_content(); ?></div><?php

	$attachment_ids = $product->get_gallery_attachment_ids();
	if ($attachment_ids) { ?>
		<div class="action-buttons">
			<a id="schema" href="<?php echo wp_get_attachment_url($attachment_ids[0]) ?>" class="purple-b">Схема зала</a>
		</div><?php
	}

	$api_id = get_post_meta( $post->ID, 'wccaf_api_id', true );
	$terms = wp_get_post_terms( $post->ID, 'pa_date' );

	$Fast_order = '<div class="action-buttons">
	<a href="#fast-order" class="purple-b btn-further"><span class="triangle"></span>Оставить заявку</a>
	</div>
	<div style="display: none;">
	<div class="g-modal" id="fast-order">' . do_shortcode("[ninja_form id=5]") .'</div>
	</div>
	<p class="no-tickets">Не найдено доступных билетов. Можете оставить заявку.</p>';

	if (!$api_id || count($terms) == 0) {
		echo $Fast_order;
		return;
	}

	if (isset($_GET['date']) || (count($terms) == 1 && !isset($_GET['offer']))) {

		echo 'date ' . $_GET['date'];

		$date_str = isset($_GET['date']) ? $_GET['date'] : $terms[0]->name;
		$offer_array = sht_api_request('<RepertoireId>'. $api_id .'</RepertoireId>
		<EventDateTime>'. $date_str .'</EventDateTime>',
		'GetOfferListByEventInfo');

		if (!count($offer_array->ResponseData->ResponseDataObject->Offer)) {
			echo $Fast_order;
			return;
		}
	} ?>

	<div id="announce"><?php

	if (count($terms) > 1 && count($_GET) == 0) {

		foreach ($terms as $value) {

			$date = format_date($value->name); ?>

			<div class="var">
				<div class="offer-date">
					<table>
						<td class="post-title">
							<h2><?php echo $post->post_title; ?></h2><br>
							<p><?php echo $date ?></p>
						</td>
						<td class="post-button">
							<a href="<?php echo get_permalink(); ?>?date=<?php echo $value->name; ?>">Купить билеты</a>
						</td>
					</table>
				</div>
			</div><?php
		} ?>
	</div><?php
	return;
}

$sectors = get_option( 'sectors' );

if (isset($_GET['date']) || (count($terms) == 1 && !isset($_GET['offer']))) { ?>

	<table class="table-head">
		<tr><td>СЕКТОР</td><td>РЯД / ЛОЖА</td><td>МЕСТА</td><td>СТОИМОСТЬ</td><td></td></tr>
	</table><?php
	foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {
		if (in_array($offer->AgentId, get_option( 'api_agents' ))) {
			$sector = $sectors[(string)$offer->SectorId];
			$sht_price = testRange($offer->AgentPrice);
			?>
			<div class="var">
				<div class="shadow">
					<div class="table-item1"><?php echo $sector; ?></div>
					<div class="table-item2"><?php echo $offer->Row; ?></div>
					<div class="table-item3"><?php echo place_handler((array)$offer->SeatList->Item); ?></div>
					<div class="table-item4"><?php echo $sht_price; ?></div>
					<div class="table-item5">
						<a href="<?php echo get_permalink(); ?>?offer=<?php echo $offer->Id; ?>">Выбрать билет</a>
					</div>
				</div>
			</div><?php
		}
	}
}
else if (isset($_GET['offer'])) { ?>
	<table class="table-head">
		<tr><td>СЕКТОР</td><td>РЯД / ЛОЖА</td><td>МЕСТО</td><td>СТОИМОСТЬ</td><td></td></tr>
	</table><?php
	$offer_object = sht_api_request('<OfferId>'. $_GET['offer'] .'</OfferId>', 'GetOfferById');
	$offer = $offer_object->ResponseData->ResponseDataObject->Offer;
	$sector = $sectors[(string)$offer->SectorId];
	$sht_price = testRange($offer->AgentPrice);

	foreach ($offer->SeatList->Item as $item) { ?>

		<div class="var">
			<form class="variations_form cart shadow" method="post" enctype="multipart/form-data">
				<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $post->ID ); ?>">
				<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>">
				<input type="hidden" name="attribute_pa_date" value="<?php echo $offer->EventDateTime; ?>">
				<input type="hidden" name="attribute_pa_sector" value="<?php echo $sector; ?>">
				<input type="hidden" name="attribute_pa_row" value="<?php echo $offer->Row; ?>">
				<input type="hidden" name="attribute_pa_place" value="<?php echo $item; ?>">
				<input type="hidden" name="attribute_pa_price" value="<?php echo $sht_price; ?>">
				<div class="table-item1"><?php echo $sector; ?></div>
				<div class="table-item2"><?php echo $offer->Row; ?></div>
				<div class="table-item3"><?php echo $item; ?></div>
				<div class="table-item4"><?php echo $sht_price; ?></div>
				<div class="table-item5">
					<button type="submit" class="single_add_to_cart_button button alt">
						<?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?>
					</button>
				</div>
			</form>
		</div><?php
	}
}
?>
</div><?php
}

add_action( 'woocommerce_init', 'remove_message_after_add_to_cart', 99);
function remove_message_after_add_to_cart(){
	if( isset( $_GET['add-to-cart'] ) ){
		wc_clear_notices();
	}
}

add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start(); ?>
	<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?></a><?php
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}
?>
