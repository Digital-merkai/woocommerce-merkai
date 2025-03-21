<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://merkai.io
 * @since             1.0.0
 * @package           Woocommerce_Merkai
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Merkai Payment Gateway
 * Plugin URI:        https://merkai.io
 * Description:       Official Merkai payment plugin for Woocommerce.
 * Version:           1.0.0
 * Author:            MERKAI TECHNOLOGY
 * Author URI:        https://merkai.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-merkai
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
const WOOCOMMERCE_MERKAI_VERSION = '1.0.0';

/**
 * Merkai page activation slug
 */
const WOOCOMMERCE_MERKAI_ACTIVATION_PAGE_SLUG = 'merkai-activation';
const WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG = 'bonuses-wallet';

/**
 * Merkai params names
 */
const PAYNOCCHIO_USER_UUID_KEY = 'user_uuid';
const PAYNOCCHIO_ENV_KEY = 'environment_uuid';
const PAYNOCCHIO_CURRENCY_KEY = 'currency_uuid';
const PAYNOCCHIO_WALLET_KEY = 'wallet_uuid';
const PAYNOCCHIO_TYPE_KEY = 'type_uuid';
const PAYNOCCHIO_STATUS_KEY = 'status_uuid';
const PAYNOCCHIO_SECRET_KEY = 'secret_key';

if ( ! defined( 'WOOCOMMERCE_MERKAI_PAGE_FILE' ) ) {
    define( 'WOOCOMMERCE_MERKAI_PAGE_FILE', __FILE__ );
}

if ( ! defined( 'WOOCOMMERCE_MERKAI_PATH' ) ) {
    define( 'WOOCOMMERCE_MERKAI_PATH', plugin_dir_path( WOOCOMMERCE_MERKAI_PAGE_FILE ) );
}

if ( ! defined( 'WOOCOMMERCE_MERKAI_BASENAME' ) ) {
    define( 'WOOCOMMERCE_MERKAI_BASENAME', plugin_basename( WOOCOMMERCE_MERKAI_PAGE_FILE ) );
}

if ( ! defined( 'WOOCOMMERCE_MERKAI_ABSPATH' ) ) {
    define( 'WOOCOMMERCE_MERKAI_ABSPATH', trailingslashit( plugin_dir_path( __FILE__ ) ));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-merkai-activator.php
 */
function activate_woocommerce_merkai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-merkai-activator.php';
	Woocommerce_Merkai_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-merkai-deactivator.php
 */
function deactivate_woocommerce_merkai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-merkai-deactivator.php';
	Woocommerce_Merkai_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_merkai' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_merkai' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-merkai.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_merkai() {

	$plugin = new Woocommerce_Merkai();
	$plugin->run();

}
run_woocommerce_merkai();
