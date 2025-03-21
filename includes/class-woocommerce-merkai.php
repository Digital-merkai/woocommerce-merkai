<?php

/**
 * The file that defines the core plugin class
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

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Merkai
 * @subpackage Woocommerce_Merkai/includes
 * @author     MERKAI TECHNOLOGY <info@merkai.io>
 */
class Woocommerce_Merkai {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_Merkai_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

    /**
     * Is current user logged in?
     *
     * @since    1.0.0
     * @access   protected
     * @var      boolean    $is_user_logged_in    Check current user.
     */
    private  $is_user_logged_in;

    /**
     * Current User id
     *
     * @since    1.0.0
     * @access   protected
     * @var      $user_id
     */

    public $user_id;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WOOCOMMERCE_MERKAI_VERSION' ) ) {
			$this->version = WOOCOMMERCE_MERKAI_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-merkai';

		$this->init();
		$this->load_dependencies();
		$this->set_locale();
		$this->add_gateway();
		$this->add_routes();
		$this->add_shortcodes();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function init()
    {
        add_action('init', function(){
            $this->is_user_logged_in = is_user_logged_in();
            if($this->is_user_logged_in) {
                $this->user_id = $this->get_user_id();
            }
        });
    }

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woocommerce_Merkai_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Merkai_i18n. Defines internationalization functionality.
	 * - Woocommerce_Merkai_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Merkai_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-merkai-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-merkai-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-merkai-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-merkai-admin-users-export-menu.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-merkai-admin-users-export.php';

		/**
		 * The class responsible for defining Merkai Wocommerce payment Gateway.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-add-gateway.php';

		/**
		 * The class responsible for defining Merkai Wocommerce custom RESTapi Routes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-add-restapi-routes.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-merkai-public.php';

		/**
		 * Shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-merkai-shortcodes.php';

		/**
		 * Merkai Wallet Class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-merkai-wallet.php';

		$this->loader = new Woocommerce_Merkai_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_Merkai_Add_Gateway class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_gateway() {

	    $add_gateway = new Woocommerce_Merkai_Add_Gateway();

		$this->loader->add_action( 'plugins_loaded', $add_gateway, 'add_woocommerce_gateway' );

	}
	/**
	 * Add custom REST API routes
	 *
	 * Uses the Woocommerce_Merkai_Add_RESTapi_routes class in order to set the routes and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_routes() {

	    $add_routes = new Woocommerce_Merkai_Add_RESTapi_Routes();

		$this->loader->add_action( 'rest_api_init', $add_routes, 'add_custom_routes' );

	}
	/**
	 * Add shortcodes
	 *
	 * Uses the Woocommerce_Merkai_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_shortcodes() {

        add_shortcode( 'merkai_activation_block', [ 'Woocommerce_Merkai_Shortcodes', 'merkai_activation_block' ] );
        add_shortcode( 'merkai_registration_block', [ 'Woocommerce_Merkai_Shortcodes', 'merkai_registration_block' ] );
        add_shortcode( 'merkai_account_page', [ 'Woocommerce_Merkai_Shortcodes', 'merkai_account_page' ] );
        add_shortcode( 'merkai_cart_wallet_widget', [ 'Woocommerce_Merkai_Shortcodes', 'merkai_cart_wallet_widget' ] );
        add_shortcode( 'merkai_kopybara_cart_wallet_widget', [ 'Woocommerce_Merkai_Shortcodes', 'merkai_kopybara_cart_wallet_widget' ] );
        add_shortcode( 'merkai_payment_widget', [ 'Woocommerce_Merkai_Shortcodes', 'merkai_payment_widget' ] );
        add_shortcode( 'merkai_modal_forms', [ 'Woocommerce_Merkai_Shortcodes', 'merkai_modal_forms' ] );
        add_shortcode( 'merkai_wallet_management_page', [ 'Woocommerce_Merkai_Shortcodes', 'merkai_wallet_management_page' ] );

	}

	/**
	 * Register Woocommerce custom gateway
	 *
	 * Uses the WC_Payment_Gateway.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_Merkai_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woocommerce_Merkai_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// TODO: add Block editor Support
		//$this->loader->add_action( 'woocommerce_blocks_loaded', $plugin_admin, 'merkai_gateway_block_support' );

        add_action( 'plugins_loaded', function() {
            $plugin_user_export = new Merkai_Users_Export_Menu( new Merkai_Users_Export_Page() );
            $plugin_user_export->init();
        } );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_Merkai_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_login', $this, 'merkai_ajax_login');
        $this->loader->add_action( 'wp_ajax_merkai_ajax_activation', $this,'merkai_ajax_activation');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_activation', $this, 'merkai_ajax_activation');
        $this->loader->add_action( 'wp_ajax_merkai_ajax_registration', $this,'merkai_ajax_registration');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_registration', $this, 'merkai_ajax_registration');

        $this->loader->add_action( 'wp_ajax_merkai_ajax_login', $this,'merkai_ajax_login');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_login', $this, 'merkai_ajax_login');

        $this->loader->add_action( 'wp_ajax_merkai_ajax_top_up', $this,'merkai_ajax_top_up');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_top_up', $this,'merkai_ajax_top_up');
        $this->loader->add_action( 'wp_ajax_merkai_ajax_set_status', $this, 'merkai_ajax_set_status');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_set_status', $this, 'merkai_ajax_set_status');
        $this->loader->add_action( 'wp_ajax_merkai_ajax_delete_wallet', $this, 'merkai_ajax_delete_wallet');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_delete_wallet', $this, 'merkai_ajax_delete_wallet');
        $this->loader->add_action( 'wp_ajax_merkai_ajax_check_balance', $this, 'merkai_ajax_check_balance');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_check_balance', $this, 'merkai_ajax_check_balance');
        $this->loader->add_action( 'wp_ajax_merkai_ajax_get_user_wallet', $this, 'merkai_ajax_get_user_wallet');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_get_user_wallet', $this, 'merkai_ajax_get_user_wallet');
        $this->loader->add_action( 'wp_ajax_merkai_ajax_withdraw', $this, 'merkai_ajax_withdraw');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_withdraw', $this, 'merkai_ajax_withdraw');

        $this->loader->add_action( 'wp_ajax_merkai_ajax_get_env_structure', $this, 'merkai_ajax_wallet_info');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_get_env_structure', $this, 'merkai_ajax_wallet_info');
        $this->loader->add_action( 'wp_ajax_merkai_ajax_get_structure_calculation', $this, 'merkai_ajax_structure_calculation');
        $this->loader->add_action( 'wp_ajax_nopriv_merkai_ajax_get_structure_calculation', $this, 'merkai_ajax_structure_calculation');

        //TODO Do we need redirect to Checkout?
        //$this->loader->add_action( 'woocommerce_add_to_cart_redirect', $plugin_public, 'redirect_checkout_add_cart' );

        $this->loader->add_filter( 'body_class', $plugin_public, 'merkai_body_class' );

        $this->loader->add_filter ( 'woocommerce_account_menu_items', $this, 'merkai_bonuses_wallet' );
        // register permalink endpoint
        $this->loader->add_action( 'init', $this, 'merkai_bonuses_wallet_endpoint');
        // content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
        $this->loader->add_action( 'woocommerce_account_bonuses-wallet_endpoint', $this, 'woocommerce_merkai_bonuses_wallet_endpoint_content' );
        $this->loader->add_filter( 'query_vars', $this, 'merkai_add_custom_query_vars' );

    }

    public function merkai_bonuses_wallet( $menu_links )
    {
        $menu_links = array_slice( $menu_links, 0, 0, true )
            + array( WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG => 'Wallet and Bonuses' )
            + array_slice( $menu_links, 0, NULL, true );
        return $menu_links;
    }

    public function merkai_bonuses_wallet_endpoint()
    {
        add_rewrite_endpoint( WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG,EP_ROOT | EP_PAGES  );
    }

    public function merkai_add_custom_query_vars( $vars ) {
    $vars[] = WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG;
    return $vars;
}

    public function woocommerce_merkai_bonuses_wallet_endpoint_content() {
        echo do_shortcode('[merkai_wallet_management_page]');
    }

    /**
     * Handle Creation of the UUID
     *
     * @since    1.0.0
     */
    public function merkai_ajax_activation()
    {
        $nonce = isset( $_POST['ajax-activation-nonce'] ) ? sanitize_text_field( $_POST['ajax-activation-nonce'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'merkai_ajax_activation' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

        $this->set_uuid();

         if(!get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
             $wallet = new Woocommerce_Merkai_Wallet($this->get_uuid());
             $wallet_response = $wallet->createWallet();
             $json_response = json_decode($wallet_response);
             if($json_response->status === 'success') {
                 add_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, $json_response->wallet, true);
                 $user = get_user_by('id', $this->user_id);

                 $headers = "MIME-Version: 1.0" . "\r\n";
                 $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                 $message = "<b>Dear friend!</b>,<br/><br/> Congratulations! <br/>Your new Wallet has been successfully activated!<br>this is an automated mail.pls  don't reply to this mail. ";

                 wp_mail( $user->user_email, "Wallet Activation!", $message, $headers );
             } else {
                 wp_send_json([
                     'response' => $json_response,
                 ]);
             }
         }

         if($json_response->status === 'success') {
             set_transient('first_time_active', true, 60);
             wp_send_json_success();
         }
        wp_die();
    }


    /**
     * Handle Creation of the UUID
     *
     * @since    1.0.0
     */
    public function merkai_ajax_registration()
    {
        $nonce = isset( $_POST['ajax-registration-nonce'] ) ? sanitize_text_field( $_POST['ajax-registration-nonce'] ) : '';
        $user_email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
        $user_login = isset( $_POST['login'] ) ? sanitize_text_field( $_POST['login'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'ajax-registration-nonce' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

        if($user_email) {

            if(!is_email($user_email)) {
                wp_send_json_error([
                    'message' => 'Please enter valid email',
                ]);
            }

            $user_pass = wp_generate_uuid4();

            $info = array(
                'user_login'  =>  $user_login,
                'user_pass'  =>  $user_pass,
                'user_email' => $user_email,
            );
            $result = wp_insert_user( $info );

            if(is_wp_error( $result)) {
                wp_send_json_error([
                    'message' => $result->get_error_message()
                ]);

            } else {

                //wp_set_current_user($result);
                //wp_set_auth_cookie($result);

                wp_send_new_user_notifications($result, 'user');

                wp_send_json_success();
            }

        }

        wp_die();
    }

    public function merkai_ajax_login() {
        check_ajax_referer( 'merkai-ajax-login-nonce', 'nonce' );

        // Nonce is checked, get the POST data and sign user on
        $info = array();
        $info['user_login'] = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember'] = $_POST['remember'];

        $user_signon = wp_signon( $info, false );
        if ( is_wp_error($user_signon) ){
            echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
        } else {
            echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
        }

        die();
    }

    /**
     * Top Up Wallet
     *
     * @since    1.0.0
     */
    public function merkai_ajax_top_up()
    {
        $nonce = isset( $_POST['ajax-top-up-nonce'] ) ? sanitize_text_field( $_POST['ajax-top-up-nonce'] ) : '';
        $amount = isset( $_POST['amount'] ) ? floatval(sanitize_text_field( $_POST['amount'] )) : '';
        $redirect_url = isset( $_POST['redirect_url'] ) ? sanitize_text_field( $_POST['redirect_url'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'merkai_ajax_top_up' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

        if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
            $wallet = new Woocommerce_Merkai_Wallet($this->get_uuid());
            $wallet_response = $wallet->topUpWallet(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true), $amount, $redirect_url);
        }

        wp_send_json([
            'response' => $wallet_response,
            'amount' => $amount,
        ]);
        wp_die();
    }

    /**
     * Structure Calculation
     *
     * @since    1.0.0
     */
    public function merkai_ajax_structure_calculation() {
        $amount = isset( $_GET['amount'] ) ? floatval(sanitize_text_field( $_GET['amount'] )) : 0;
        $redirect_url = isset( $_GET['operation_type'] ) ? sanitize_text_field( $_GET['operation_type'] ) : '';
        $wallet_balance_check = isset( $_GET['wallet_balance_check'] ) ? sanitize_text_field( $_GET['wallet_balance_check'] ) : false;

        $response = $this->get_merkai_structure_calculation($amount, $redirect_url, $wallet_balance_check);
        wp_send_json([
            'response' => $response,
        ]);
        wp_die();
    }

    /**
     * Top Up Wallet
     *
     * @since    1.0.0
     */
    public function merkai_ajax_set_status()
    {
        $nonce = isset( $_POST['ajax-status-nonce'] ) ? sanitize_text_field( $_POST['ajax-status-nonce'] ) : '';
        $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'merkai_ajax_set_status' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
             $wallet = new Woocommerce_Merkai_Wallet($this->get_uuid());
             $wallet_statuses = $wallet->getWalletStatuses();
             $walletId = get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true);
             $json_response = $wallet->updateWalletStatus($walletId, $wallet_statuses[$status]);

             if($json_response->status === 'success') {
                 wp_send_json_success();
             } else {
                 wp_send_json_error();
             }

             wp_die();
         }
    }

    /**
     * Delete Wallet from User Meta
     *
     * @since    1.0.0
     */
    public function merkai_ajax_delete_wallet()
    {
        $nonce = isset( $_POST['ajax-delete-nonce'] ) ? sanitize_text_field( $_POST['ajax-delete-nonce'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'merkai_ajax_delete_wallet' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {

             delete_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY);

             wp_send_json_success();

             wp_die();
         }
    }

    /**
     * Withdraw form Wallet
     *
     * @since    1.0.0
     */
    public function merkai_ajax_withdraw()
    {
        $nonce = isset( $_POST['ajax-withdraw-nonce'] ) ? sanitize_text_field( $_POST['ajax-withdraw-nonce'] ) : '';
        $amount = isset( $_POST['amount'] ) ? sanitize_text_field( $_POST['amount'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'merkai_ajax_withdraw' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

        if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
            $wallet = new Woocommerce_Merkai_Wallet($this->get_uuid());
            $wallet_response = $wallet->withdrawFromWallet(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true), $amount);
        }

        wp_send_json([
            'response' => $wallet_response
        ]);
        wp_die();
    }

    /**
     * Check Wallet Balance
     *
     * @since    1.0.0
     */
    public function merkai_ajax_get_user_wallet()
    {
         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {

             wp_send_json([
                 'walletId' => get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true)
             ]);
         } else {
             wp_send_json_error();
         }

        wp_die();
    }

    /**
     * Check Wallet Balance
     *
     * @since    1.0.0
     */
    public function merkai_ajax_check_balance()
    {
         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
             $wallet = new Woocommerce_Merkai_Wallet($this->get_uuid());
             $wallet_response = $wallet->getWalletBalance(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true));
         }
        wp_send_json([
            'response' => $wallet_response
        ]);
        wp_die();
    }

    public function merkai_ajax_wallet_info() {
        $wallet_info = $this->get_merkai_wallet_info();
        wp_send_json([
            'response' => $wallet_info
        ]);
        wp_die();
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woocommerce_Merkai_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the current User UUID.
	 *
	 * @since     1.0.0
	 * @return    string    The UUID.
	 */
	public function get_uuid() {
		return get_current_user_id() ? get_user_meta(get_current_user_id(), PAYNOCCHIO_USER_UUID_KEY, true) : wp_generate_uuid4();
	}

    /**
     * Setter for User meta
     *
     * @since     1.0.0
     * @return    void
     */
    public function set_uuid () {
	    if(!get_user_meta($this->user_id, PAYNOCCHIO_USER_UUID_KEY, true)){
            $uuid = wp_generate_uuid4();
            add_user_meta($this->user_id, PAYNOCCHIO_USER_UUID_KEY, $uuid, true);
        }
    }

    /**
     * User ID getter
     *
     * @since     1.0.0
     * @return    int
     */
    public function get_user_id(): int
    {
        return get_current_user_id();
    }

    /**
     * Get the current User Wallet data
     *
     * @since    1.0.0
     */
    public function get_merkai_wallet_info() {

            $wallet = [
                'code' => 404,
            ];

            $current_user = wp_get_current_user();
            $user_merkai_wallet_id = $current_user ? get_user_meta($current_user->ID, PAYNOCCHIO_WALLET_KEY, true) : wp_generate_uuid4();

            $wallet['user'] = [
                'first_name' => $current_user->first_name,
                'last_name' => $current_user->last_name,
            ];

            $user_merkai_wallet = new Woocommerce_Merkai_Wallet($this->get_uuid());

            $wallet['x-wallet-signature'] = $user_merkai_wallet->getSignature();
            $wallet['x-company-signature'] = $user_merkai_wallet->getSignature(true);
            $wallet['secret'] = $user_merkai_wallet->get_secret();
            $wallet['env'] = $user_merkai_wallet->get_env();
            $wallet['wallet_uuid'] = $user_merkai_wallet->wallet_uuid();
            $wallet['user_uuid'] = $this->get_uuid();

            $wallet_structure = $user_merkai_wallet->getEnvironmentStructure();
            $wallet_bal_bon = $user_merkai_wallet->getWalletBalance($user_merkai_wallet_id);
            if($wallet_bal_bon) {
                $wallet['balance'] = $wallet_bal_bon['balance'];
                $wallet['bonuses'] = $wallet_bal_bon['bonuses'];
                $wallet['card_number'] = $wallet_bal_bon['number'];
                $wallet['status'] = $wallet_bal_bon['status'];
                $wallet['code'] = $wallet_bal_bon['code'];
                $wallet['structure'] = $wallet_structure;
                $wallet['wallet_percentage_commission'] = 2.9;
                $wallet['wallet_fixed_commission'] = 0.3;
            }
            return $wallet;
    }

    /**
     * Get the current bonuses and commission calculation
     *
     * @since    1.0.0
     */
    public function get_merkai_structure_calculation($amount, $operation_type, $wallet_balance_check) :array
    {
        $wallet = new Woocommerce_Merkai_Wallet($this->get_uuid());
        return $wallet->getStructureCalculation($amount, $operation_type, $wallet_balance_check);
    }

    /**
     * Get the current bonuses and commission calculation for anonymous user
     *
     * @since    1.0.0
     */
    public function get_benefits_calculation($amount, $operation_type, bool $wallet_balance_check = false) :array
    {
        if (!is_user_logged_in()) {
            $user_uuid = wp_generate_uuid4();
        } else {
            $user_uuid = get_user_meta(get_current_user_id(), PAYNOCCHIO_USER_UUID_KEY, true) ? get_user_meta(get_current_user_id(), PAYNOCCHIO_USER_UUID_KEY, true) : wp_generate_uuid4();
        }

        $wallet = new Woocommerce_Merkai_Wallet($user_uuid);
        $wallet_benefits = $wallet->getStructureCalculation($amount, $operation_type, $wallet_balance_check);

        /*echo '<pre>';
        print_r($wallet_benefits);
        echo '</pre>';*/

        if ($wallet_benefits['conversion_rate']) {
            return [
                'conversion_rate' => $wallet_benefits['conversion_rate'],
                'operations_data' => $wallet_benefits['operations_data'],
            ];
        } else {
            return [
                'error' => '500'
            ];
        }
    }

    /**
     * Get conversion rate
     *
     * @sinse 1.0.0
     */
    public function get_benefits_calculation_conversion_rate($amount, $operation_type, $wallet_balance_check) {
        return $this->get_benefits_calculation($amount, $operation_type, $wallet_balance_check)['conversion_rate'];
    }

    /**
     * Get full amount without commission
     *
     * @sinse 1.0.0
     */
    public function get_benefits_calculation_full_amount($amount, $operation_type, $wallet_balance_check) {
        return $this->get_benefits_calculation($amount, $operation_type, $wallet_balance_check)['operations_data'][0]->full_amount;
    }

    /**
     * Get bonuses amount
     *
     * @sinse 1.0.0
     */
    public function get_benefits_calculation_bonuses_amount($amount, $operation_type, $wallet_balance_check) {
        return $this->get_benefits_calculation($amount, $operation_type, $wallet_balance_check)['operations_data'][0]->bonuses_amount;
    }

    /**
     * Get commission amount
     *
     * @sinse 1.0.0
     */
    public function get_benefits_calculation_commission_amount($amount, $operation_type, $wallet_balance_check) {
        return $this->get_benefits_calculation($amount, $operation_type, $wallet_balance_check)['operations_data'][0]->commission_amount;
    }

    /**
     * Check if woocommerce_merkai_approved is true
     */
    public function is_approved()
    {
        return get_option('woocommerce_merkai_approved');
    }
}
