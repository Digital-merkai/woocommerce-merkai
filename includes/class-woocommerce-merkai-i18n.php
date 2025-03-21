<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://merkai.io
 * @since      1.0.0
 *
 * @package    Woocommerce_Merkai
 * @subpackage Woocommerce_Merkai/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Merkai
 * @subpackage Woocommerce_Merkai/includes
 * @author     MERKAI TECHNOLOGY <info@merkai.io>
 */
class Woocommerce_Merkai_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-merkai',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
