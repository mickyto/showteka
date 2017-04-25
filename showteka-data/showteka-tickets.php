<?php
add_action( 'admin_enqueue_scripts', 'enqueue_date_picker' );
function enqueue_date_picker() {
  wp_enqueue_script( "ajax-remove", plugin_dir_url( __FILE__ ) . '/ajax-remove.js', array( 'jquery' ) );
  wp_localize_script( 'ajax-remove', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

  wp_enqueue_script(
    'field-date-js',
    get_template_directory_uri() . '/scripts/datepicker.js',
    array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
    time(),
    true
  );
  wp_enqueue_style('jquery-datepicker', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css');
}

add_action('wp_ajax_test_response', 'text_ajax_process_request');
function text_ajax_process_request() {

  $tickets = get_option( 'tickets' );

  if ( isset( $_POST['id']) && isset( $_POST['key'] ) ) {
    unset($tickets[$_POST['id']][$_POST['key']]);
    $response = array(
      'msg'     => $_POST["post_var"],
      'action'  => 'remove-offer'
    );
  }
  else {
    unset($tickets[$_POST['id']]);
    $response = array(
      'msg'     => $_POST["post_var"],
      'action'  => 'remove-event'
    );
  }

  update_option( 'tickets', $tickets );
  print_r(json_encode($response));
  die();
}

function add_tickets_plugin_settings_page() {

  $options = get_option( 'options' );
  $sectors = get_option( 'sectors' );
  $tickets = get_option( 'tickets' );
  ?>
  <div class="wrap">
    <div class="postbox-container">
      <h1>Ручное добавление билетов</h1>
      <?php
      if ( isset( $_GET['m'] ) && $_GET['m'] == '1' ) {
        ?>
        <div id='message' class='updated fade'><p><strong>Настройки успешно обновлены.</strong></p></div>
        <?php
      }
      ?>
      <div class="sh-section">
        <h2>Выберите мероприятия, чтобы добавить билеты</h2>
        <form method="post" action="admin-post.php">
          <?php
          settings_fields( 'add_tickets_plugin-settings-group' );
          do_settings_sections( 'add_tickets_plugin-settings-group' );
          ?>
          <input type="hidden" name="action" value="sh_manually_add_tickets" />
          <div class="stage">
            <ul>
              <?php
              $loop = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );
              while ( $loop->have_posts() ) : $loop->the_post();
              $theid = get_the_ID();
              ?>
              <li>
                <input type="checkbox" id="<?php echo $theid; ?>" name="events[]" value="<?php echo $theid; ?>" <?php checked(isset($tickets[$theid])); ?> />
                <?php echo get_the_title(); ?>
              </li>
              <br>
              <?php
              endwhile;
              ?>
            </ul>
          </div>
          <?php submit_button(); ?>
        </form>
      </div>
      <br>


      <?php foreach ($tickets as $key => $value) { ?>

        <div class="sh-section" id="sh-section-<?php echo $key; ?>">
          <h2><?php echo get_the_title($key); ?></h2>
          <a href="#" rel="<?php echo $key; ?>" class="ajax-link">Удалить</a>
          <form method="post" class="my-tickets" action="admin-post.php">
            <?php
            settings_fields( 'add_tickets_plugin-settings-group' );
            do_settings_sections( 'add_tickets_plugin-settings-group' );
            ?>
            <input type="hidden" name="action" value="sh_manually_add_tickets" />
            <div class="event">
              <input type="text" placeholder="Выберите дату" class="datepicker-u"/>
              <input type="hidden" name="<?php echo $key; ?>[date]" class="datepicker"/>
              <input type="text" name="<?php echo $key; ?>[time]" placeholder="Время (19:00)" />
              <input type="text" name="<?php echo $key; ?>[sector]" placeholder="Сектор" />
              <input type="text" name="<?php echo $key; ?>[row]" placeholder="Ряд / ложе" />
              <input type="text" name="<?php echo $key; ?>[places]" placeholder="Места (1, 2, 3...)" />
              <input type="text" name="<?php echo $key; ?>[price]" placeholder="Цена" />
            </div>
            <?php
            if (!empty($tickets[$key])) {
              foreach ($value as $num => $offer) {
                ?>
                <table id="<?php echo $key.$num; ?>">
                  <td><?php echo $offer['date']; ?></td>
                  <td><?php echo $offer['sector']; ?></td>
                  <td><?php echo $offer['row']; ?></td>
                  <td><?php echo place_handler($offer['places']); ?></td>
                  <td><?php echo $offer['price']; ?></td>
                  <td><a href="#" id="<?php echo $num; ?>" rel="<?php echo $key; ?>" class="ajax-link">Удалить</a></td>
                </table>
                <?php
              }
            }
            ?>
            <?php submit_button(); ?>
          </form>
        </div>
        <br>
        <?php
      }
      ?>
      <!-- <pre><?php print_r($tickets); ?></pre> -->
    </div>
  </div>
  <?php


}
?>
