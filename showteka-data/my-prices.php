<?php
function my_prices_plugin_settings_page() {

  $options = get_option( 'options' );
  $sectors = get_option( 'sectors' );
  $my_prices = get_option( 'my-prices' );

  ?>
  <div class="wrap">
    <div class="postbox-container">
      <h1>Управления ценами на мои билеты</h1>
      <?php
      if ( isset( $_GET['m'] ) && $_GET['m'] == '1' ) {
        ?>
        <div id='message' class='updated fade'><p><strong>Настройки успешно обновлены.</strong></p></div>
        <?php
      }
      ?>
      <div class="sh-section">
      <h2>Выберите мероприятия, чтобы изменять цены на билеты</h2>
      <form method="post" action="admin-post.php">
        <?php
        settings_fields( 'my-cool-plugin-settings-group' );
        do_settings_sections( 'my-cool-plugin-settings-group' );
        ?>
        <input type="hidden" name="action" value="sh_set_prices_for_my_tickets" />
        <div class="stage">
          <ul>
            <?php
            foreach ($options as $key => $value) {
              ?>
              <li>
                <input type="checkbox" name="events[]" value="<?php echo $key; ?>" <?php checked(isset($my_prices[$key])); ?> />
                <?php echo $value; ?>
              </li>
              <br>
              <?php
            }
            ?>
          </ul>
        </div>
        <?php submit_button(); ?>
      </form>
    </div>

      <h2>Ваши предложения на мероприятия</h2>

      <form method="post" action="admin-post.php">
        <?php
        settings_fields( 'my-cool-plugin-settings-group' );
        do_settings_sections( 'my-cool-plugin-settings-group' );
        ?>
        <input type="hidden" name="action" value="sh_set_prices_for_my_tickets" />
        <table border="1" cellpadding="10">
          <thead>
            <tr>
              <th>Мероприятие</th>
              <th>Сектор</th>
              <th>Ряд / Ложе</th>
              <th>Места</th>
              <th>Цена из API</th>
              <th>Моя цена</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php
              foreach ($my_prices as $key => $value) {

                $offer_array = sht_api_request('<RepertoireId>'. $key .'</RepertoireId>', 'GetOfferListByRepertoireId');
                if (gettype($offer_array) == 'string' || !count($offer_array->ResponseData->ResponseDataObject->Offer)) {
                  ?>
                  <div id='message' class='error notice'><p><strong>У мероприятия "<?php echo $options[$key]; ?>" нет предложений.</strong></p></div>
                  <?php
                  continue;
                }
                foreach ($offer_array->ResponseData->ResponseDataObject->Offer as $offer) {
                  if ((string)$offer->AgentId == '10277') {
                    ?>
                    <tr>
                      <td>
                        <?php echo $options[$key]; ?><br>
                        <?php echo $offer->EventDateTime; ?>
                      </td>
                      <td><?php echo $sectors[(string)$offer->SectorId]; ?></td>
                      <td><?php echo $offer->Row; ?></td>
                      <td><?php echo place_handler((array)$offer->SeatList->Item); ?></td>
                      <td><?php echo $offer->AgentPrice; ?></td>
                      <td>
                        <input type="text" name="prices[<?php echo $offer->Id; ?>]" value="<?php echo !empty($my_prices[$key]) && isset($my_prices[$key][(string)$offer->Id]) ? $my_prices[$key][(string)$offer->Id] : ''; ?>" />
                        <input type="hidden" name="event-<?php echo $offer->Id; ?>" value="<?php echo $key; ?>" />
                      </td>
                    </tr>
                    <?php
                  }
                }
              }
              ?>
            </tbody>
          </table>
          <?php submit_button(); ?>
        </form>
        <pre><?php print_r($my_prices); ?></pre>
      </div>
    </div>
    <?php
  }
  ?>
