<?php


function insert_product_variations($post_id, $variations) {
  foreach ($variations as $index => $variation) {

    $variation_post = array(
      'post_author'   => 1,
      'post_status'   => 'publish',
      'post_parent'   => $post_id,
      'post_type'     => 'product_variation',
    );

    $variation_post_id = wp_insert_post($variation_post);
    $sht_price = testRange($variation['price']);
    $date_term = get_term_by('name', $variation['date'], 'pa_date');

    add_post_meta($variation_post_id, 'attribute_pa_sector', $variation['sector']);
    add_post_meta($variation_post_id, 'attribute_pa_row', $variation['row']);
    add_post_meta($variation_post_id, 'attribute_pa_date', $date_term->slug);
    add_post_meta($variation_post_id, 'wccaf_offer_id', $variation['offer']);
    //add_post_meta($variation_post_id, 'wccaf_datetime', $variation['places']);
    //add_post_meta($variation_post_id, '_stock', 1);
    add_post_meta($variation_post_id, '_price', $sht_price);
    add_post_meta($variation_post_id, '_regular_price', $sht_price);
  }
}
?>
