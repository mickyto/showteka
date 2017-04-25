<?php
/**
* Plugin Name: Showteka Data
* Description: Плагин для управления данными из API
* Version: 1.0
* Author: Mickyto
* Author URI: https://github.com/mickyto
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include or Require any files
include('showteka-prices.php');
include('showteka-agents.php');
include('showteka-places.php');
include('inc/process.php');
include('inc/place-handler.php');
include('inc/display-options.inc.php');
include('inc/menus.inc.php');
include('inc/api-request.php');
include('inc/data-updater.php');
include('my-prices.php');
include('inc/my-tickets.php');
include('showteka-tickets.php');
include('inc/add-tickets.php');

add_filter( 'cron_schedules', 'true_moi_interval');
function true_moi_interval( $raspisanie ) {
	$raspisanie['every_30_mins'] = array(
		'interval' => 1800,
		'display' => 'Каждые тридцать минут'
	);
	return $raspisanie;
}

$parametri = array('egayi@yandex.ru', 'Обновление предложений');

if( !wp_next_scheduled('showteka_hook', $parametri ) )
	wp_schedule_event( time(), 'every_30_mins', 'showteka_hook', $parametri );

add_action( 'showteka_hook', 'showteka_update_tickets', 10, 3 );


// remove old dates
if( !wp_next_scheduled('sht_clear_date_terms_hook') )
	wp_schedule_event( time(), 'twicedaily', 'sht_clear_date_terms_hook' );

add_action( 'sht_clear_date_terms_hook', 'showteka_remove_old_dates', 10, 3 );
function showteka_remove_old_dates() {
	$terms = get_terms( array(
    'taxonomy' => 'pa_date',
    'hide_empty' => false,
  ) );
  $date = date('Y-m-d-00-00-00' );
  foreach ($terms as $value) {
    if ($value->slug < $date) {
      wp_delete_term( $value->term_id, 'pa_date' );
    }
  }
	wp_mail( 'egayi@yandex.ru', 'Удаление истекших дат', 'Удаление прошло успешно' );
}

// Remove custom post data along post removing
add_action( 'before_delete_post', 'my_func' );
function my_func( $postid ){

	$repertoire_id = get_post_meta( $postid, 'wccaf_api_id', true );

	if (!$repertoire_id) return;

	$options = get_option( 'options' );
	unset($options[$repertoire_id]);
	update_option( 'options', $options );

	$my_prices = get_option( 'my-prices' );
	if (isset($my_prices[$repertoire_id])) {
		unset($my_prices[$repertoire_id]);
		update_option( 'my-prices', $my_prices );
	}
}

// create custom plugin settings menu
add_action('admin_menu', 'api_data_plugin_menu');
add_action('admin_post_sh_create_products_from_api', 'process_sh_api_options' );
add_action('admin_post_sh_handle_places', 'process_sh_update_sectors' );
add_action('admin_post_sh_set_prices_for_my_tickets', 'process_sh_my_prices' );
add_action('admin_post_sh_manually_add_tickets', 'process_sh_add_tickets' );
