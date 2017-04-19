<?php
function api_places_plugin_settings_page() {

  $place_array = sht_api_request('', 'GetPlaceList');
  $places = get_option( 'places' );
  ?>
  <div class="wrap">
    <div class="postbox-container">
      <h1>Площадки</h1>
      <?php
      if (gettype($place_array) == 'object') {
        ?>
        <form method="post" action="admin-post.php">
          <?php
          settings_fields( 'api-places-plugin-settings-group' );
          do_settings_sections( 'api-places-plugin-settings-group' );
          ?>
          <input type="hidden" name="action" value="sh_handle_places" />
          <table border = "1">
            <thead>
              <tr><th></th><th>Название площадки</th></tr>
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
                <?php
              }
              ?>
            </tbody>
          </table>
          <?php submit_button(); ?>
        </form>
        <?php
      }
      if (gettype($place_array) == 'string') {
        ?>
        <div id='message' class='error notice'><p><strong>Похоже мы не можем получить ответ от сервера. Проверьте работу API.</strong></p>
          <p>Текст ошибки:</p><small><?php echo $agent_array; ?></small>
        </div>
        <?php
      }
      ?>
      <?php $sectors = get_option( 'sectors' ); ?>
      <pre><?php print_r($places); ?></pre>
      <pre><?php print_r($sectors); ?></pre>
    </div>
  </div>
  <?php
}
?>
