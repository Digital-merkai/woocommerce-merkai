<?php

/**
 * Adds custom gateway to Woocommerce
 *
 *
 * @link       https://merkai.io
 * @since      1.0.0
 *
 * @package    Woocommerce_Merkai
 * @subpackage Woocommerce_Merkai/includes
 *
 * @author     MERKAI TECHNOLOGY <info@merkai.io>
 */
class Woocommerce_Merkai_Add_Gateway {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function add_woocommerce_gateway() {

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-merkai-gateway.php';

        add_filter( 'woocommerce_payment_gateways', 'woocommerce_merkai_add_gateway' );

        function woocommerce_merkai_add_gateway( $methods ) {
            $methods[] = 'Woocommerce_Merkai_Payment_Gateway';
            return $methods;
        }
    }



}
