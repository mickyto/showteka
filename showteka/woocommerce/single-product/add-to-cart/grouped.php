<?php
/**
 * Grouped product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/grouped.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $post;

$parent_product_post = $post;

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

    <div class="popular">
        <p class="title">Афиша мероприятий театра:</p>
        <hr />
        <ul class="products">
            <?php
                foreach ( $grouped_products as $product_id ) :
                    if ( ! $product = wc_get_product( $product_id ) ) {
                        continue;
                    }

                    if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $product->is_in_stock() ) {
                        continue;
                    }

                    $post    = $product->post;
                    setup_postdata( $post );
                    ?>
                    <li class="product">
                        <a href="<?php echo get_post_permalink(); ?>" class="woocommerce-LoopProduct-link">
                            <?php
                                $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'shop_catalog' );
                                echo '<img src="' . $image_url[0] . '">'
                            ?>
                            <h3><?php echo get_the_title(); ?></h3>
                            <span class="price">
                                <?php
                                    echo $product->get_price_html();
                                    if ( $availability = $product->get_availability() ) {
                                        $availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';
                                        echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
                                    }
                                ?>
                            </span>
                        </a>
                    </li>
                    <?php
                endforeach;

                // Reset to parent grouped product
                $post    = $parent_product_post;
                $product = wc_get_product( $parent_product_post->ID );
                setup_postdata( $parent_product_post );
            ?>
        </ul>
    </div>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
