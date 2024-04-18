<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://jackballdev.com
 * @since      1.0.0
 *
 * @package    Oe_Deal_Tracker
 * @subpackage Oe_Deal_Tracker/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Oe_Deal_Tracker
 * @subpackage Oe_Deal_Tracker/includes
 * @author     Jack Ball <jackballdev@gmail.com>
 */
class Oe_Deal_Tracker_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'oe-deal-tracker',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
