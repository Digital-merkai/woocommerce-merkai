<?php

/**
 * The file that adds Block editor support for Merkai Gateway
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://merkai.io
 * @since      1.0.0
 *
 * @package    Woocommerce_Merkai
 * @subpackage Woocommerce_Merkai/includes
 */

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class Woocommerce_Merkai_Gateway_Blocks_Support  extends AbstractPaymentMethodType {

    /**
     * The gateway instance.
     *
     * @var Woocommerce_Merkai_Gateway
     */
    private $gateway;

    /**
     * Payment method name/id/slug.
     *
     * @var string
     */
    protected $name = 'merkai';

    /**
     * Initializes the payment method type.
     */
    public function initialize() {
        $this->settings = get_option( 'woocommerce_merkai_settings', [] );
        $gateways       = WC()->payment_gateways->payment_gateways();
        $this->gateway  = $gateways[$this->name];
    }

    /**
     * Returns if this payment method should be active. If false, the scripts will not be enqueued.
     *
     * @return boolean
     */
    public function is_active() {
        return $this->gateway->is_available();
    }

    /**
     * Returns an array of scripts/handles to be registered for this payment method.
     *
     * @return array
     */
    public function get_payment_method_script_handles() {
        $script_path       = 'dist/js/blocks.js';
        $script_asset_path = WOOCOMMERCE_MERKAI_ABSPATH . 'dist/js/blocks.asset.php';
        $script_asset      = file_exists( $script_asset_path )
            ? require( $script_asset_path )
            : array(
                'dependencies' => array(),
                'version'      => '1.0.0'
            );
        $script_url        = plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . $script_path;

        wp_register_script(
            'merkai-payments-blocks',
            $script_url,
            $script_asset[ 'dependencies' ],
            $script_asset[ 'version' ],
            true
        );

        wp_enqueue_style( 'merkai-payments-blocks', plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'dist/assets/css/blocks.css', array(), $script_asset[ 'version' ], 'all' );

        return [ 'merkai-payments-blocks' ];
    }

    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     */
    public function get_payment_method_data() {

        global $woocommerce;

        $merkai = new Woocommerce_Merkai();

        $wallet = $merkai->get_merkai_wallet_info();

        $cart_total = 0;
        if($woocommerce->cart) {
            $cart_total = floatval($woocommerce->cart->total);
        }

        return [
            'title'       => $this->get_setting( 'title' ),
            'description' => $this->get_setting( 'description' ),
            'wallet' => $wallet,
            'cart_total' => $cart_total,
            'user' => is_user_logged_in(),
        ];
    }
}