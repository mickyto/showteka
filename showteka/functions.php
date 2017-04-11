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
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

// Remove reviews
add_filter('woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98);
function wcs_woo_remove_reviews_tab($tabs) {
	unset($tabs['reviews']);
	unset( $tabs['description'] );
	return $tabs;
}

add_filter('woocommerce_after_shop_loop_item', 'add_contact_us_button', 98);
function add_contact_us_button() {
	global $product;
	if (!$product->is_in_stock()) {
		echo '<a href="'.get_permalink().'" rel="nofollow" class="outstock_button"><span class="contact-us">Оставить заявку</span></a>';
	}
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
	if (!is_product_category()) {
		$args = array(
			'hierarchical' => 1,
			'show_option_none' => '',
			'hide_empty' => 0,
			'parent' => 10,
			'taxonomy' => 'product_cat'
		);
		$subcats = get_categories($args);
		echo '<ul class="products">';
		foreach ($subcats as $sc) {
			$wthumbnail_id = get_woocommerce_term_meta( $sc->term_id, 'thumbnail_id', true );
			$wimage = wp_get_attachment_url( $wthumbnail_id );
			$link = get_term_link( $sc->slug, $sc->taxonomy );
			echo '<li class="product">
			<a href="'. $link .'">
			<img width="200" height="265" src="' . $wimage . '">
			<h3>'.$sc->name.'</h3>
			<span class="contact-us">Афиша театра</span>
			</a></li>';
		}
		echo '</ul>';
	}
	$ss_url = get_stylesheet_directory_uri();
	echo '<div class="banner-data" style="display: none">' . do_shortcode('[crellyslider alias="баннер-бол-1"]') . '</div>';
	echo '<div class="banner-data" style="display: none">' . do_shortcode('[crellyslider alias="баннер-бол-2"]') . '</div>';
	echo '<div class="banner-data" style="display: none">' . do_shortcode('[crellyslider alias="баннер-бол-3"]') . '</div>';
	echo '<script src="' . $ss_url . '/scripts/insertBanners.js?v=6"></script>';
}

// Customize category page
add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
function woocommerce_category_image() {
	if ( is_product_category() ){
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
		$image = wp_get_attachment_url( $thumbnail_id );
		if ( $image ) {
			echo '<div class="images"><a href="' . $image . '" class="woocommerce-main-image"><img src="' . $image . '" alt="' . $cat->name . '" /></a></div>';
			echo '<div class="summary entry-summary"><h1>'. $cat->name .'</h1></div>';
		}
	}
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
if ( ! function_exists('write_log')) {
	function write_log ( $log )  {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}

// Make order throught API
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');

function my_custom_checkout_field_process() {

	$cart_items = WC()->cart->cart_contents;

	foreach ($cart_items as $cart_item) {

		$repertoire_id = get_post_meta( $cart_item['product_id'], "wccaf_api_id", true );
		$attribute_term = get_term_by('slug', $cart_item['variation']['attribute_pa_date'], 'pa_date');

		$offer_request = sht_api_request('
		<RepertoireId>99787</RepertoireId>
		<EventDateTime>'. $attribute_term->name .'</EventDateTime>
		<SectorId>'. $cart_item['variation']['attribute_pa_sector'] .'</SectorId>
		<Row>'. $cart_item['variation']['attribute_pa_row'] .'</Row>
		<Seat>'. $cart_item['_custom_options'] .'</Seat>',
		'GetOfferIdBySeatInfo');

		if ($offer_request->ResponseResult->Code == 0) {
			$offer_id = (string) $offer_request->ResponseData->ResponseDataObject->OfferId;
			$offers = get_option( 'offers' );

			$order = sht_api_request('
			<OfferId>'. $offer_id .'</OfferId>
			<SeatList><Item>'. $cart_item['_custom_options'] .'</Item></SeatList>',
			'MakeOrder');

			if ($order->ResponseResult->Code == 0) {

				if(($key = array_search($cart_item['_custom_options'], $offers[$repertoire_id][$offer_id])) !== false) {
					unset($offers[$repertoire_id][$offer_id][$key]);
					update_option( 'offers', $offers );
				}
			}
		}
		else {
			wc_add_notice( __( 'Некоторые места или мероприятия недоступны' ), 'error' );

			wp_enqueue_script( 'mark-cart-items', get_template_directory_uri() . '/scripts/cartItemsMarker.js', 'jquery', '', true );
		}
	}
}

// Show only lowest price on product list
add_filter( 'woocommerce_variable_price_html', 'my_variation_price_format', 10, 2 );
function my_variation_price_format( $price, $product ) {

	$prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
	$price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
	return $price;
}

// Sort varitions helper
function sortByOrder($a, $b) {
	return strcmp($a['attributes']['attribute_pa_date'], $b['attributes']['attribute_pa_date']);
}

// Main fuction for showing product information
function woocommerce_variable_add_to_cart() {
	global $product, $post;

	$place = get_post_meta(get_the_ID(), 'wccaf_place', true);
	if ($place) { ?>
		<div class="where"><q><?php echo $place ?></q>
			<p class="address"><?php echo get_post_meta(get_the_ID(), 'wccaf_address', true) ?></p>
		</div>
		<div class="action-buttons"><?php echo get_post_meta(get_the_ID(), 'wccaf_date', true) ?></div><?php
	}

	$attachment_ids = $product->get_gallery_attachment_ids();
	if ($attachment_ids) { ?>
		<div class="action-buttons">
			<a id="schema" href="<?php echo wp_get_attachment_url($attachment_ids[0]) ?>" class="purple-b">Схема зала</a>
		</div><?php
	}
	echo the_content();

	$offers = get_option( 'offers' );
	$api_id = get_post_meta( $post->ID, 'wccaf_api_id', true );
	if (isset($offers[$api_id])) {
		$sectors = get_option( 'sectors' );
		$variations = $product->get_available_variations();
		usort($variations, 'sortByOrder');
		?>
		<div id="announce">
			<table>
				<tr>
					<td>СЕКТОР</td>
					<td>РЯД / ЛОЖА</td>
					<td>МЕСТО</td>
					<td>СТОИМОСТЬ</td>
					<td></td>
				</tr>
			</table>
			<?php foreach ($variations as $key => $value) :?>
				<div class="offer-date">
					<div><?php echo get_post_meta( $value['variation_id'], 'attribute_pa_date', true ); ?></div>
				</div>
				<?php
				$offer_id = get_post_meta( $value['variation_id'], "wccaf_offer_id", true );
				foreach ($offers[$api_id][$offer_id] as $item) {
					?>
					<div class="var">
						<form class="variations_form cart" onsubmit='event.preventDefault(); return addToCart(this)' method="post" enctype="multipart/form-data">
							<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $post->ID ); ?>">
							<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" >
							<input type="hidden" name="variation_id" class="variation_id" value="<?php echo $value['variation_id']?>" >
							<input type="hidden" name="attribute_pa_place" value="<?php echo $item; ?>" >
							<?php foreach ($value['attributes'] as $attr_key => $attr_value) :?>
								<input type="hidden" name="<?php echo $attr_key?>" value="<?php echo $attr_value?>">
							<?php endforeach; ?>
							<div class="table-item1"><?php echo $sectors[$value['attributes']['attribute_pa_sector']]?></div>
							<div class="table-item2"><?php echo $value['attributes']['attribute_pa_row']; ?></div>
							<div class="table-item3"><?php echo $item; ?></div>
							<div class="table-item4"><?php echo $value['display_price']; ?></div>
							<div class="table-item5">
								<button type="submit" class="single_add_to_cart_button button alt">
									<?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?>
								</button>
							</div>
						</form>
					</div>
					<?php
				}
				?>
			<?php endforeach; ?>

			<script src="<?php get_template_directory_uri(); ?>/wp-content/themes/showteka/scripts/dateHandler.js"></script>
		</div>
		<?php
	}
	else { ?>
		<div class="action-buttons">
			<a href="#fast-order" class="purple-b btn-further"><span class="triangle"></span>Оставить заявку</a>
		</div>
		<div style="display: none;">
			<div class="g-modal" id="fast-order"><?php echo do_shortcode("[ninja_form id=5]") ?></div>
		</div><?php
	}
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

// add_filter('woocommerce_add_cart_item_data', 'wdm_add_item_data',1,10);
// function wdm_add_item_data($cart_item_data, $product_id) {
//
// 	global $woocommerce;
// 	$new_value = array();
// 	$new_value['_custom_options'] = $_POST['place'];
// 	return $new_value;
// }
//
// add_filter('woocommerce_get_cart_item_from_session', 'wdm_get_cart_items_from_session', 1, 3 );
// function wdm_get_cart_items_from_session($item,$values,$key) {
//
// 	if (array_key_exists( '_custom_options', $values ) ) {
// 		$item['_custom_options'] = $values['_custom_options'];
// 	}
// 	return $item;
// }
//
// add_action('woocommerce_add_order_item_meta','wdm_add_values_to_order_item_meta',1,2);
// function wdm_add_values_to_order_item_meta($item_id, $values) {
// 	global $woocommerce,$wpdb;
// 	$sectors = get_option( 'sectors' );
// 	wc_update_order_item_meta( $item_id, 'sector', $sectors[$values['variation']['attribute_pa_sector']] );
// 	wc_add_order_item_meta($item_id,'place',$values['_custom_options']);
// }
?>
