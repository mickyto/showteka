<?php
function testRange($int){

  $ranges  = get_option( 'prices' );
  foreach ($ranges as $range => $addition) {
    $limits = explode("-", $range);
    if ($limits[0] <= $int && $int < $limits[1]) {
      return $int + $addition;
    }
  }
  return $int;
}

function insert_product_variations($post_id, $variations) {
  foreach ($variations as $index => $variation) {

    $variation_post = array(
      'post_status'   => 'publish',
      'post_parent'   => $post_id,
      'post_type'     => 'product_variation',
    );

    $variation_post_id = wp_insert_post($variation_post);

    add_post_meta($variation_post_id, 'attribute_pa_sector', $variation['sector']);
    add_post_meta($variation_post_id, 'attribute_pa_row', $variation['row']);
    add_post_meta($variation_post_id, 'attribute_pa_date', $variation['date']);
    add_post_meta($variation_post_id, 'wccaf_offer_id', $variation['offer']);
    add_post_meta($variation_post_id, 'wccaf_datetime', (string)$variation['places']);
    //add_post_meta($variation_post_id, '_stock', 1);
    add_post_meta($variation_post_id, '_price', testRange($variation['price']));
  }
}
?>
