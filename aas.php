<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              panda-ant.com
 * @since             1.0.0
 * @package           Aas
 *
 * @wordpress-plugin
 * Plugin Name:       Amazon affiliate search
 * Plugin URI:        panda-ant.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jonathan Royere
 * Author URI:        panda-ant.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aas
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aas-activator.php
 */
function activate_aas() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aas-activator.php';
	Aas_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aas-deactivator.php
 */
function deactivate_aas() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aas-deactivator.php';
	Aas_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_aas' );
register_deactivation_hook( __FILE__, 'deactivate_aas' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aas.php';

require plugin_dir_path( __FILE__ ) . 'SearchWidget.php';

//register plugin settings
add_action( 'admin_menu', 'aas_add_admin_menu' );
add_action( 'admin_init', 'aas_settings_init' );


function aas_add_admin_menu(  ) {

	add_options_page( 'Amazon Associate Sales', 'Amazon Associate Sales', 'manage_options', 'amazon_associate_sales', 'aas_options_page' );

}


function aas_settings_init(  ) {

	register_setting( 'pluginPage', 'aas_settings' );

	add_settings_section(
			'aas_pluginPage_section',
			__( 'Associate Account Info', 'wordpress' ),
			'aas_settings_section_callback',
			'pluginPage'
		);


	add_settings_field(
		'api_key',
		__( 'AWS API Key', 'wordpress' ),
		'api_key_render',
		'pluginPage',
		'aas_pluginPage_section'
	);

	add_settings_field(
		'aas_secret_key',
		__( 'AWS API Secret Key', 'wordpress' ),
		'aas_secret_key_render',
		'pluginPage',
		'aas_pluginPage_section'
	);

	add_settings_field(
		'aas_assoc_tag',
		__( 'AWS Associate Tag', 'wordpress' ),
		'aas_assoc_tag_render',
		'pluginPage',
		'aas_pluginPage_section'
	);

	add_settings_field(
		'aas_text_field_3',
		__( 'Secondary AWS Associate Tag', 'wordpress' ),
		'aas_text_field_3_render',
		'pluginPage',
		'aas_pluginPage_section'
	);

	add_settings_field(
		'search_terms',
		__( 'Search terms (separate with comma)', 'wordpress' ),
		'search_terms_render',
		'pluginPage',
		'aas_pluginPage_section'
	);


}


function api_key_render(  ) {

	$options = get_option( 'aas_settings' );
	?>
	<input type='text' name='aas_settings[api_key]' value='<?php echo $options["api_key"]; ?>'>
	<?php

}


function aas_secret_key_render(  ) {

	$options = get_option( 'aas_settings' );
	?>
	<input type='text' name='aas_settings[aas_secret_key]' value='<?php echo $options['aas_secret_key']; ?>'>
	<?php

}


function aas_assoc_tag_render(  ) {

	$options = get_option( 'aas_settings' );
	?>
	<input type='text' name='aas_settings[aas_assoc_tag]' value='<?php echo $options['aas_assoc_tag']; ?>'>
	<?php

}


function aas_text_field_3_render(  ) {

	$options = get_option( 'aas_settings' );
	?>
	<input type='text' name='aas_settings[aas_text_field_3]' value='<?php echo $options['aas_text_field_3']; ?>'>
	<?php

}

function search_terms_render(  ) {

	$options = get_option( 'aas_settings' );
	?>
	<input type='text' name='aas_settings[search_terms]' value='<?php echo $options['search_terms']; ?>'>
	<?php

}


function aas_settings_section_callback(  ) {

	echo __( 'Enter your amazon info to help generate product url', 'wordpress' );

}


function aas_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<h2>Amazon Associate Sales</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_aas() {

	$plugin = new Aas();
	$plugin->run();

}
run_aas();
