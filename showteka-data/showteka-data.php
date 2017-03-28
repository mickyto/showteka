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
include('inc/insert-attributes.php');
include('inc/insert-variations.php');
include('inc/api-request.php');
include('inc/data-updater.php');

add_filter( 'cron_schedules', 'true_moi_interval');
function true_moi_interval( $raspisanie ) {
	$raspisanie['every_10_mins'] = array(
		'interval' => 600,
		'display' => 'Каждые десять минут'
	);
	return $raspisanie;
}

$parametri = array('egayi@yandex.ru', 'Тест тема', 'Тест сообщение');

if( !wp_next_scheduled('showteka_hook', $parametri ) )
	wp_schedule_event( time(), 'every_10_mins', 'showteka_hook', $parametri );

add_action( 'showteka_hook', 'showteka_update_tickets', 10, 3 );

// create custom plugin settings menu
add_action('admin_menu', 'api_data_plugin_menu');
add_action('admin_post_sh_create_products_from_api', 'process_sh_api_options' );
add_action('admin_post_sh_handle_places', 'process_sh_update_sectors' );
