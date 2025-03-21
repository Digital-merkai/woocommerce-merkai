<?php

/**
 * Fired during plugin activation
 *
 * @link       https://merkai.io
 * @since      1.0.0
 *
 * @package    Woocommerce_Merkai
 * @subpackage Woocommerce_Merkai/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Merkai
 * @subpackage Woocommerce_Merkai/includes
 * @author     MERKAI TECHNOLOGY <info@merkai.io>
 */

class Woocommerce_Merkai_Activator {

	/**
	 * Actions on plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

    /**
     * This action hook registers our PHP class as a WooCommerce payment gateway
     */
        if( !class_exists( 'WooCommerce' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( 'Please install and Activate WooCommerce first.', 'woocommerce-merkai' ), 'Plugin dependency check', array( 'back_link' => true ) );
        }

        flush_rewrite_rules();
	}
}
