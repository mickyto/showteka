<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<div id="primary" class="site-content">
		<div id="content" role="main" class="twentytwelve">
			<?php woocommerce_content(); ?>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>