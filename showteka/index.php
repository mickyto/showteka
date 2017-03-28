<?php get_header(); ?>
<div id="container">
    <?php woocommerce_content(); ?>
    <!--<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <p><?php the_content(__('(more...)')); ?></p>
    <?php endwhile; else: ?>
    <p>Извините, но такой страницы не существуетю</p>
    <p>Перейти на <a href="/">главную</a></p>
    <?php endif;  ?>-->
</div>
</div>
<?php get_footer(); ?>
