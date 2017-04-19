<?php
function process_sh_my_prices() {

  $my_prices = get_option( 'my-prices' );

  write_log($_POST);

  if (isset($_POST['events'])) {
    foreach ($_POST['events'] as $key) {
      if (!isset($my_prices[$key])) {
        $my_prices[$key] = array();
      }
    }
  }

  if (isset($_POST['prices'])) {
    foreach ($_POST['prices'] as $key => $value) {
      $my_prices[$_POST['event-'. $key]][$key] = $value;
    }
  }

  update_option( 'my-prices', $my_prices );

  wp_redirect( admin_url( 'admin.php?page=my_prices&m=1' ) );
  exit;
}
?>
