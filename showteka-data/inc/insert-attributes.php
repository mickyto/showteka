<?php
function insert_product_attributes($post_id, $variations) {

  $available_attributes = array(
    'sector',
    'row',
    'date',
  );

  $product_attributes_data = array(); // Setup array to hold our product attributes data

  foreach ($available_attributes as $attribute) // Loop round each attribute
  {
    $product_attributes_data['pa_'.$attribute] = array( // Set this attributes array to a key to using the prefix 'pa'

      'name'         => 'pa_'.$attribute,
      'value'        => '',
      'is_visible'   => '0',
      'is_variation' => '1',
      'is_taxonomy'  => '1'
    );
  }

  update_post_meta($post_id, '_product_attributes', $product_attributes_data); // Attach the above array to the new posts meta data key '_product_attributes'

  foreach ($available_attributes as $attribute) {

    $values = array();

    foreach ($variations as $variation) { // Loop each variation in the file

      $values[] = $variation[$attribute];
    }

    $values = array_unique($values); // Filter out duplicate values

    // Store the values to the attribute on the new post, for example without variables
    wp_set_object_terms($post_id, $values, 'pa_' . $attribute, true);
  }
}
?>
