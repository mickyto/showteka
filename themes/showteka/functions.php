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
	wp_enqueue_style('style_css', get_template_directory_uri() . '/style.css?v=1');
}

add_action('wp_enqueue_scripts', 'c2h_scripts');
function c2h_scripts() {
	wp_enqueue_script('to_top', get_template_directory_uri() . '/scripts/toTop.js', array('jquery'), '', false );
}

add_filter('woocommerce_order_item_meta_start', 'add_custom_data_to_email_template');
function add_custom_data_to_email_template( $item_id ) {
	$data = wc_get_order_item_meta( $item_id, '_variation_id', true );
	$date = get_post_meta( $data, "wccaf_date", true );
	$time = get_post_meta( $data, "wccaf_time", true );
	echo '<br><small>Дата: ' . $date . '</small><br><small>Время: ' . $time . '</small>';
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

add_action( 'woocommerce_after_cart', 'add_checkout' );
function add_checkout() {
	if ( WC()->cart->get_cart_contents_count() !== 0 ) {
		echo do_shortcode('[woocommerce_checkout]');
	}
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
			<span class="product_type_grouped">Афиша театра</span>
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

// Add custom product data and action buttons
add_action( 'woocommerce_single_product_summary', 'add_event_data', 10 );
function add_event_data() {
	global $product;
	$attachment_ids = $product->get_gallery_attachment_ids();
	echo '<div class="where">
	<q>'. get_post_meta(get_the_ID(), 'wccaf_place', true).'</q>
	<p class="address">' . get_post_meta(get_the_ID(), 'wccaf_address', true) . '</p>
	</div>';
	echo '<div class="action-buttons">'. get_post_meta(get_the_ID(), 'wccaf_date', true) . '</div>';
	echo the_content();
	if ($attachment_ids) {
		echo '<div class="action-buttons">
		<a id="schema" href="' . wp_get_attachment_url($attachment_ids[0]) .'" class="purple-b">Схема зала</a>
		<a href="#fast-order" class="purple-b btn-further">
		<span class="triangle"></span>
		Быстрый заказ
		</a></div>';
	}
	echo '<div style="display: none;">
	<div class="g-modal" id="fast-order">' . do_shortcode("[ninja_form id=5]") . '</div>
	</div>';
}

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

add_filter( 'woocommerce_variable_price_html', 'my_variation_price_format', 10, 2 );
function my_variation_price_format( $price, $product ) {
	$prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
	$price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
	return $price;
}

function woocommerce_variable_add_to_cart() {
	global $product, $post;
	$sectors = get_option( 'sectors' );
	$variations = $product->get_available_variations();
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
			<?php foreach ($variations as $key => $value) :?>
				<tr data-date="<?php echo get_post_meta( $value['variation_id'], "wccaf_datetime", true ); ?>">
					<form class="variations_form cart" method="post" enctype="multipart/form-data">
						<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $post->ID ); ?>">
						<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
						<input type="hidden" name="variation_id" class="variation_id" value="<?php echo $value['variation_id']?>" />
						<?php if (!empty($value['attributes'])) {
							foreach ($value['attributes'] as $attr_key => $attr_value) {
								?>
								<input type="hidden" name="<?php echo $attr_key?>" value="<?php echo $attr_value?>">
								<?php
							}
						}
						?>
						<!-- <td><?php
						$attribute_term = get_term_by('slug', $value['attributes']['attribute_pa_sector'], 'pa_sector');
						if ($attribute_term) {
							echo $attribute_term->name;
						}
						?></td> -->
						<td><?php echo $sectors[$value['attributes']['attribute_pa_sector']]?></td>
						<td><?php echo $value['attributes']['attribute_pa_row']; ?></td>
						<td><?php echo $value['attributes']['attribute_pa_place']; ?></td>
						<td><?php echo $value['display_price']; ?></td>
						<td>
							<button type="submit" class="single_add_to_cart_button button alt">
								<?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?>
							</button>
						</td>
					</form>
				</tr>
			<?php endforeach; ?>
		</table>
		<script src="<?php get_template_directory_uri(); ?>/wp-content/themes/twentytwelve/scripts/dateHandler.js?v=0"></script>
	</div>
	<?php
}
?>
