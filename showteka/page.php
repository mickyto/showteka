<?php
/**
* The template for displaying all pages
*
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages
* and that other 'pages' on your WordPress site may use a
* different template.
*
* @link https://codex.wordpress.org/Template_Hierarchy
*
* @package WordPress
* @subpackage Twenty_Seventeen
* @since 1.0
* @version 1.0
*/

get_header(); ?>

<div id="primary" class="content-area">
	<div id="content" role="main"><?php

	if (!is_cart() && is_page() && isset($_GET['title'])) {
		echo "<div class=\"banner-vert\">" . do_shortcode('[crellyslider alias="баннер-верт-1"]'), do_shortcode('[crellyslider alias="баннер-верт-2"]') . "</div>";
		?>
		<div id="announce">
			<p class="title">Мероприятия по запросу "<?php echo $_GET['title']; ?>"</p>
			<hr />
			<div class="s-result"><?php


			$args = array(
				'post_type' => 'product',
				's' => $_GET['title']
			);
			$posts = get_posts( $args );

			foreach ($posts as $post) {
				$date = get_post_meta($post->ID, 'wccaf_date', true); ?>

				<div class="var">
					<div class="offer-date">
						<table>
							<td class="post-title">
								<h2><?php echo $post->post_title; ?></h2><br>
								<p><?php echo $date ?></p>
							</td>
							<td class="post-button">
								<a href="<?php echo get_post_permalink($post->ID); ?>">Купить билеты</a>
							</td>
						</table>
					</div>
				</div>
			</div><?php
		} ?>
	</div>
</div><?php
}
else if (!is_cart() && is_page() && isset($_GET['date'])) {
	echo "<div class=\"banner-vert\">" . do_shortcode('[crellyslider alias="баннер-верт-1"]'), do_shortcode('[crellyslider alias="баннер-верт-2"]') . "</div>";
	?>

	<div id="announce">
		<p class="title">События на дату "<?php echo $_GET['date']; ?>"</p>
		<hr />
		<div class="s-result"><?php

		$all_terms = get_terms( array(
			'taxonomy' => 'pa_date',
			'fields' => 'names',
		) );

		$regex = '/' . $_GET['date'] . ' */';
		$filtered_terms = preg_grep($regex, $all_terms);

		$args = array(
			'post_type' => 'product',
			'numberposts' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'pa_date',
					'field' => 'name',
					'terms' => $filtered_terms,
				)
			)
		);
		$posts = get_posts($args);

		foreach ($posts as $post) {

			$terms = wp_get_post_terms( $post->ID, 'pa_date' );
			foreach ($terms as $value) {
				if (preg_match($regex, $value->name)) {
					$date = format_date($value->name); ?>
					<div class="var">
						<div class="offer-date">
							<table>
								<td class="post-title">
									<h2><?php echo $post->post_title; ?></h2><br>
									<p><?php echo $date ?></p>
								</td>
								<td class="post-button">
									<a href="<?php echo get_post_permalink($post->ID); ?>?date=<?php echo $value->name; ?>">Купить билеты</a>
								</td>
							</table>
						</div>
					</div><?php
				}
			}
		} ?>
	</div>
</div><?php
}
else {
	while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</article>
<?php endwhile;
}
?>

</div><!-- #main -->
</div><!-- #primary -->


<?php get_footer(); ?>
