<?php
function api_agents_plugin_settings_page() {

	$loop = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );
  while ( $loop->have_posts() ) : $loop->the_post();
  $theid = get_the_ID();
  $api_id = get_post_meta($theid, 'wccaf_api_id', true );

  if ($api_id) {
    $args = array(
      'post_type'     => 'product_variation',
      'numberposts'   => -1,
      'post_parent'   => $theid
    );
    $variations = get_posts( $args );
    ?><pre><?php print_r($variations[0]); ?></pre><?php


    echo '<br>post: ' . get_the_title($theid) . '      Vars count: ' . count($variations);
  }
  endwhile;
  wp_reset_query();
  $options = get_option( 'offers' );
  ?><pre><?php print_r($options); ?></pre><?php


  $xml = '<?xml version="1.0" encoding="UTF-8"?>
  <Request>
  <RequestAuthorization>
  <UserId>10277</UserId>
  <Hash>b5744d1e326e866aac01ac17bffb3e5b</Hash>
  </RequestAuthorization>
  </Request>';
  $client = new SoapClient('http://api.zriteli.ru/index.php?wsdl');
  $response = $client->__soapCall('GetAgentList', array($xml));
  $new_xml = simplexml_load_string($response);
  $options = get_option( 'api_agents' );
  ?>
  <div class="wrap">
    <div class="postbox-container">
      <h1>Агенты</h1>
      <form method="post" action="options.php">
        <?php settings_fields( 'api-agents-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'api-agents-plugin-settings-group' ); ?>
        <table border = "1">
          <thead>
            <tr>
              <th></th>
              <th>Имя</th>
              <th>Название компании</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($new_xml->ResponseData->ResponseDataObject->Agent as $agent) {
              ?>
              <tr>
                <td class="stage">
                  <input type="checkbox" name="api_agents[agent<?php echo $agent->Code; ?>]" value="<?php echo $agent->Id; ?>" <?php checked( isset( $options['agent' . $agent->Code] ) ); ?> />
                </td>
                <td class="stage">
                  <p><?php echo $agent->Name; ?> | id: <?php echo $agent->Id; ?></p>
                </td>
                <td class="stage">
                  <p><?php echo $agent->CompanyName; ?></p>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php submit_button(); ?>
        </form>
        <pre><?php print_r($options); ?></pre>
      </div>
    </div>
    <?php } ?>
