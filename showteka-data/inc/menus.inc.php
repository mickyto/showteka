<?php
function api_data_plugin_menu() {

	//create new top-level menu
	add_menu_page('Управление API', 'API', 'edit_pages', 'showteka_api', 'api_data_plugin_settings_page' , 'dashicons-screenoptions', 72 );
  add_submenu_page('showteka_api', 'Управление ценами', 'Цены', 'edit_pages', 'showteka_prices' , 'my_cool_plugin_settings_page' );
	add_submenu_page('showteka_api', 'Управление агентами', 'Агенты', 'edit_pages', 'showteka_agents' , 'api_agents_plugin_settings_page' );
	add_submenu_page('showteka_api', 'Управление площадками', 'Площадки', 'edit_pages', 'showteka_places' , 'api_places_plugin_settings_page' );
	add_submenu_page('showteka_api', 'Управление ценами на мои мероприятия', 'Мои цены', 'edit_pages', 'my_prices' , 'my_prices_plugin_settings_page' );
	add_submenu_page('showteka_api', 'Ручное добавление билетов', 'Мои билеты', 'edit_pages', 'add_tickets' , 'add_tickets_plugin_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'register_api_data_plugin_settings' );
}

function register_api_data_plugin_settings() {
	//register our settings
	register_setting( 'api_data_plugin-settings-group', 'options' );
	register_setting( 'api_data_plugin-settings-group', 'sectors' );
	register_setting( 'add_tickets_plugin-settings-group', 'tickets' );
	register_setting( 'my-cool-plugin-settings-group', 'prices' );
	register_setting( 'my_prices_plugin-settings-group', 'my-prices' );
	register_setting( 'api-agents-plugin-settings-group', 'api_agents' );
	register_setting( 'api-places-plugin-settings-group', 'places' );
}
?>
