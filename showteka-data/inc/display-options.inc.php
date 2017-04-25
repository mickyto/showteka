<?php
function api_data_plugin_settings_page() {

  $options = get_option( 'options' );
  $places = get_option( 'places' );
  ?>
  <div class="wrap">
    <div class="postbox-container">
      <h1>Выбор мероприятий</h1>
      <?php
      if ( isset( $_GET['m'] ) && $_GET['m'] == '1' ) {
        ?>
        <div id='message' class='updated fade'><p><strong>Настройки успешно обновлены. Добавлено <?php echo $_GET['ad']; ?>, удалено <?php echo $_GET['re']; ?></strong></p></div>
        <?php
      }
      ?>
      <form method="post" action="admin-post.php">
        <?php
        settings_fields( 'my-cool-plugin-settings-group' );
        do_settings_sections( 'my-cool-plugin-settings-group' );
        ?>
        <input type="hidden" name="action" value="sh_create_products_from_api" />
        <table cellpadding="10" border = "1">
          <thead>
            <tr>
              <th>Название площадки</th>
              <th>Залы площадки</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($places as $place => $place_name) :?>
              <tr>
                <td class="stage">
                  <h3><?php echo $place_name; ?></h3>
                  <p>Id: <?php echo $place; ?></p>
                </td>
                <td class="stage">
                  <?php
                  $stage_array = sht_api_request('<PlaceId>'.$place.'</PlaceId>', 'GetStageListByPlaceId');
                  if (gettype($stage_array) == 'object') {
                    foreach ($stage_array->ResponseData->ResponseDataObject->Stage as $stage) {
                      ?>
                      <h3><?php echo $stage->Name; ?> | </h3>
                      <p class="id">Id: <?php echo $stage->Id; ?></p>
                      <ul>
                        <?php
                        $repertoire_array = sht_api_request('<StageId>'.$stage->Id.'</StageId>', 'GetRepertoireListByStageId');                        foreach ($repertoire_array->ResponseData->ResponseDataObject->Repertoire as $value) {                          ?>                          <li>
                            <input type="checkbox" name="event[<?php echo $value->Id; ?>]" value='<?php echo $value->Name; ?>' <?php checked(isset($options[(string)$value->Id])); ?> />
                            <input type="hidden" name="stage-<?php echo $value->Id; ?>" value="<?php echo $stage->Id; ?>" />
                            <input type="hidden" name="place-<?php echo $value->Id; ?>" value="<?php echo $place_name; ?>" />
                            <input type="hidden" name="address-<?php echo $value->Id; ?>" value="<?php echo $stage->Address; ?>" />
                            <?php echo $value->Name; ?> | id: <?php echo $value->Id; ?>
                          </li>
                          <?php
                        }
                        ?>
                      </ul>
                      <?php
                    }
                  }
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php submit_button(); ?>
      </form>
      <pre><?php print_r($options); ?></pre>
    </div>
  </div>
  <?php
}
?>
