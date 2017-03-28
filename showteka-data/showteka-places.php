<?php
function api_places_plugin_settings_page() {

  $place_array = sht_api_request('', 'GetPlaceList');
  $places = get_option( 'places' );
  $sectors = get_option( 'sectors' );
  ?>
  <div class="wrap">
    <div class="postbox-container">
      <h1>Площадки</h1>
      <form method="post" action="admin-post.php">
        <?php settings_fields( 'api-places-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'api-places-plugin-settings-group' ); ?>
        <input type="hidden" name="action" value="sh_handle_places" />
        <table border = "1">
          <thead>
            <tr>
              <th></th>
              <th>Название площадки</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($place_array->ResponseData->ResponseDataObject->Place as $place) {
              ?>
              <tr>
                <td class="stage">
                  <input type="checkbox" name="places[<?php echo $place->Id; ?>]" value="<?php echo $place->Name; ?>" <?php checked( array_key_exists( (string)$place->Id, $places ) ); ?> />
                </td>
                <td class="stage">
                  <p><?php echo $place->Name; ?> | id: <?php echo $place->Id; ?></p>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php submit_button(); ?>
        </form>
        <pre><?php print_r($places); ?></pre>
        <pre><?php print_r($sectors); ?></pre>
      </div>
    </div>
    <?php } ?>
