<?php
function api_data_plugin_menu() {

	//create new top-level menu
	add_menu_page('Управление API', 'API', 'manage_options', 'showteka_api', 'api_data_plugin_settings_page' , 'dashicons-screenoptions', 72 );
  add_submenu_page('showteka_api', 'Управление ценами', 'Цены', 'manage_options', 'showteka_prices' , 'my_cool_plugin_settings_page' );
	add_submenu_page('showteka_api', 'Управление агентами', 'Агенты', 'manage_options', 'showteka_agents' , 'api_agents_plugin_settings_page' );
	add_submenu_page('showteka_api', 'Управление площадками', 'Площадки', 'manage_options', 'showteka_places' , 'api_places_plugin_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'register_api_data_plugin_settings' );
}

function register_api_data_plugin_settings() {
	//register our settings
	register_setting( 'api_data_plugin-settings-group', 'options' );
	register_setting( 'api_data_plugin-settings-group', 'sectors' );
	register_setting( 'api_data_plugin-settings-group', 'offers' );
	register_setting( 'my-cool-plugin-settings-group', 'prices' );
	register_setting( 'api-agents-plugin-settings-group', 'api_agents' );
	register_setting( 'api-places-plugin-settings-group', 'places' );
}
?>
