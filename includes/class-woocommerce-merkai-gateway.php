<?php

class Woocommerce_Merkai_Payment_Gateway extends WC_Payment_Gateway {

    private bool $testmode;
    private Woocommerce_Merkai_Encryption $encryption;

    function __construct() {

        $this->encryption = new Woocommerce_Merkai_Encryption();

        // global ID
        $this->id = "merkai";

        // Show Title
        $this->method_title = __( "Merkai", 'merkai' );

        // Show Description
        $this->method_description = __( "Merkai Payment Gateway Plug-in for WooCommerce", 'merkai' );

        // vertical tab title
        $this->title        = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );

        $this->icon = null;

        $this->has_fields = true;

        // support default form with credit card
        $this->supports = array( 'product', 'refunds' );

        // setting defines
        $this->init_form_fields();

        // load time variable setting
        $this->init_settings();

        $this->testmode = 'yes' === $this->get_option( 'testmode' );

        $this->enabled = $this->get_option( 'enabled' );

        // further check of SSL if you want
        //add_action( 'admin_notices', array( $this,	'do_ssl_check' ) );

        // Save settings
        if ( is_admin() ) {
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        }

        add_action( 'woocommerce_api_merkai', array( $this, 'webhook' ));
        add_action( 'update_option', array( $this,	'do_health_check' ), 10, 3 );

    } // Here is the  End __construct()

    public function get_test_mode()
    {
        return $this->testmode;
    }

    public function get_option($key, $default = '') {
        $value = parent::get_option($key, $default);
        if (in_array($key, [PAYNOCCHIO_SECRET_KEY, PAYNOCCHIO_ENV_KEY])) {
            return $this->encryption->decrypt($value);
        }
        return $value;
    }

    public function process_admin_options() {
        $post_data = $this->get_post_data();
        foreach ($this->form_fields as $key => $field) {
            if (in_array($key, [PAYNOCCHIO_SECRET_KEY, PAYNOCCHIO_ENV_KEY])) {
                $value = $this->get_field_value($key, $field, $post_data);
                if ($value) {
                    $this->settings[$key] = $this->encryption->encrypt($value);
                }
            } else {
                $this->settings[$key] = $this->get_field_value($key, $field, $post_data);
            }
        }
        return update_option($this->get_option_key(), $this->settings);
    }

    // administration fields for specific Gateway
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled' => array(
                'title'		=> __( 'Enable / Disable', 'merkai' ),
                'label'		=> __( 'Enable this payment gateway', 'merkai' ),
                'type'		=> 'checkbox',
                'default'	=> 'no',
            ),
            'title' => array(
                'title'		=> __( 'Title', 'merkai' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'Payment title of checkout process.', 'merkai' ),
                'default'	=> __( 'Merkai pay', 'merkai' ),
            ),
            'description' => array(
                'title'		=> __( 'Description', 'merkai' ),
                'type'		=> 'textarea',
                'desc_tip'	=> __( 'Payment title of checkout process.', 'merkai' ),
                'default'	=> __( 'Successfully payment through Merkai.', 'merkai' ),
                'css'		=> 'max-width:450px;'
            ),
            'base_url' => array(
                'title'		=> __( 'Merkai base url', 'merkai' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the base url provided by Merkai when you signed up for an account.', 'merkai' ),
                'default'   => 'https://wallet.stage.merkai.com'
            ),
            PAYNOCCHIO_ENV_KEY => array(
                'title'		=> __( 'Merkai Environment UUID', 'merkai' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the environment_uuid provided by Merkai when you signed up for an account.', 'merkai' ),
            ),
            PAYNOCCHIO_SECRET_KEY => array(
                'title'		=> __( 'Merkai Secret UUID', 'merkai' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the Secret UUID provided by Merkai when you signed up for an account.', 'merkai' ),
            ),
            'testmode' => array(
                'title'		=> __( 'Merkai Test Mode', 'merkai' ),
                'label'		=> __( 'Enable Test Mode', 'merkai' ),
                'type'		=> 'checkbox',
                'description' => __( 'This is the test mode of gateway.', 'merkai' ),
                'default'	=> 'no',
            ),
            'darkmode' => array(
                'title'		=> __( 'Merkai Dark Mode', 'merkai' ),
                'label'		=> __( 'Enable Dark mode', 'merkai' ),
                'type'		=> 'checkbox',
                'description' => __( 'If you select this checkbox, Merkai pages and modules will be displayed with a dark theme.', 'merkai' ),
                'default'	=> 'no',
            ),
            'rounded' => array(
                'title'		=> __( 'Merkai Rounded Mode', 'merkai' ),
                'label'		=> __( 'Enable Rounded mode', 'merkai' ),
                'type'		=> 'checkbox',
                'description' => __( 'If you select this checkbox, the Merkai interface elements will be rounded off.', 'merkai' ),
                'default'	=> 'no',
            ),
            'accent_color' => array(
                'title'		=> __( 'Merkai Accent Color', 'merkai' ),
                'label'		=> __( 'Enter accent color', 'merkai' ),
                'type'		=> 'color',
                'description' => __( 'Specify the accent color of the interface elements, such as buttons. Leave blank for default.', 'merkai' ),
            ),
            'accent_text_color' => array(
                'title'		=> __( 'Merkai Text Accent Color', 'merkai' ),
                'label'		=> __( 'Enter text accent color', 'merkai' ),
                'type'		=> 'color',
                'description' => __( 'Specify the text color for accent elements of the interface, such as buttons. Leave blank for default.', 'merkai' ),
            ),
            'embleme_url' => array(
                'title'		=> __( 'Embleme URL for Cards', 'merkai' ),
                'label'		=> __( 'Embleme URL', 'merkai' ),
                'type'		=> 'url',
                'description' => __( 'Enter the URL of the logo that will be displayed on top of the card image on the payment and personal account pages. The aspect ratio is 1 to 1!', 'merkai' ),
            ),
        );
    }

    // Response handled for payment gateway
    public function process_payment( $order_id )
    {
        global $woocommerce;

        $customer_order = new WC_Order($order_id);

        $order_uuid = wp_generate_uuid4();
        $customer_order->update_meta_data('order_uuid', $order_uuid);

        $user_wallet_id = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);
        $user_uuid = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);
        $user_merkai_wallet = new Woocommerce_Merkai_Wallet($user_uuid);

        $merkai = new Woocommerce_Merkai();
        $wallet = $merkai->get_merkai_wallet_info();
        $wallet_structure = $wallet['structure'];
        $bonuses_conversion_rate = (float) $wallet_structure['bonus_conversion_rate'];

        $fullAmount = $customer_order->total;
        $amount = $customer_order->total;

        $bonusAmount = ( isset( $_POST['bonusesvalue'] ) ) ? $_POST['bonusesvalue'] : null;

        $customer_order->update_meta_data('bonuses_value', $bonusAmount);

        if($bonusAmount) {
            $amount = $fullAmount - $bonusAmount * $bonuses_conversion_rate;
        }

        $wallet_response = $user_merkai_wallet->getWalletBalance(get_user_meta($customer_order->user_id, PAYNOCCHIO_WALLET_KEY, true));
        $response = $user_merkai_wallet->makePayment($user_wallet_id, $fullAmount, $amount, $order_uuid, $bonusAmount);
        //print_r($response);
        /*wp_send_json([
            'balance' => $wallet_response['balance'],
            'bonuses' => $wallet_response['bonuses'],
        ]);*/

        if ($wallet['code'] !== 500) {

            if ($wallet_response['balance'] + $wallet_response['bonuses'] * $bonuses_conversion_rate < $customer_order->total) {
                wc_add_notice('You balance is lack for $' . $customer_order->total - $wallet_response['balance'] . '. Please top up your wallet.', 'error');
                $customer_order->add_order_note('Error: insufficient funds');
                return;
            }

            if ($wallet_response['balance'] + $wallet_response['bonuses'] * $bonuses_conversion_rate >= $customer_order->total && ($wallet_response['balance'] + $bonusAmount * $bonuses_conversion_rate) < $customer_order->total) {
                wc_add_notice('Please TopUp or use your Bonuses.', 'error');
                $customer_order->add_order_note('Error: insufficient funds');
                return;
            }

            if ($response['status_code'] === 200 && json_decode($response['response'])->type_interactions == 'success.interaction') {

                // Payment successful
                $customer_order->add_order_note(__('Merkai complete payment.', 'merkai'));

                // paid order marked
                $customer_order->payment_complete();

                /**
                 * Set COMPLETED status for Orders
                 */
                //$customer_order->update_status( "completed" );

                // this is important part for empty cart
                $woocommerce->cart->empty_cart();
                // Redirect to thank you page
                // print_r($bonusAmount);
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($customer_order),
                );
            } else {
                $response_body = json_decode($response['response'], true);

                $error_message = 'Please try again later: ' . $response_body['detail'] ?? '';

                wc_add_notice($error_message, 'error');

                if (isset($response_body['detail'])) {
                    $customer_order->add_order_note('Error: ' . $response_body['detail']);
                }

                if (isset($response_body['type_interactions'])) {
                    $customer_order->add_order_note('Error: ' . $response_body['type_interactions'] . ', ' . ($response_body['schemas']['message'] ?? ''));
                }
            }
        } else {
            wc_add_notice('An error in the operation of the wallet system. Please contact support.', 'error');
            $customer_order->add_order_note('Error: Internal Server Error');
            return;
        }

    }

    public function process_refund($order_id, $amount = null, $reason = '') {

        $customer_order = new WC_Order($order_id);
        $order_uuid = $customer_order->get_meta( 'order_uuid' , true );
        $bonuses_value = $customer_order->get_meta( 'bonuses_value' , true );

        $user_wallet_id = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);
        $user_uuid = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);

        $user_merkai_wallet = new Woocommerce_Merkai_Wallet($user_uuid);

        $customer_order->add_order_note( 'User UUID ' . $user_uuid );
        $customer_order->add_order_note( 'Wallet UUID ' . $user_wallet_id );
        $customer_order->add_order_note( 'Order UUID ' . $order_uuid );

        if ($bonuses_value) {
            $amount = $amount - $bonuses_value;
        }

        $wallet_response = $user_merkai_wallet->chargeBack($order_uuid, $user_wallet_id, $amount);

        if ( $wallet_response['status_code'] === 200) {
            // Refund successful
            $customer_order->add_order_note( __( 'Merkai complete refund.', 'merkai' ) );
            return true;
        } else {
            // Refund fail
            $customer_order->add_order_note( 'Merkai refund error: '. json_decode($wallet_response['detail'])->msg );
            return false;
        }
    }

    // Validate fields
    public function validate_fields() {

        return true;
    }

    /**
     * You will need it if you want your custom credit card form, Step 4 is about it
     */
    public function payment_fields() {

        $merkai = new Woocommerce_Merkai();
        $wallet = $merkai->get_merkai_wallet_info();

        /*
         * Display description above form
         * */
            if( $this->description ) {
                // you can instructions for test mode, I mean test card numbers etc.
                if( $this->get_test_mode() ) {
                    $this->description .= '<p style="color:#1db954;font-weight:bold">TEST MODE ENABLED.</p>';
                }
                // display the description with <p> tags etc.
                echo wpautop( wp_kses_post( $this->description ) );
            }

            if(is_user_logged_in()) {
                if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
                    echo do_shortcode('[merkai_activation_block register_redirect="/checkout?step=2" login_redirect="/checkout?step=2"]');
                } else {
                    if($wallet['code'] !== 500) {
                        echo do_shortcode('[merkai_payment_widget]');
                    } else {
                        echo '<div class="merkai_error_notification">
                        <svg class="merkai-max-w-[100px] merkai-mx-auto merkai-mb-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm0-8v6h2V7h-2z" fill="#d4d4d4" class="fill-000000"></path>
                        </svg>
                        <div class="merkai-mb-4">
                            If you see this page, our wallet service issues an error.
                        </div>
                        <div>To solve the problem, please contact <b>'.get_bloginfo('name').'</b> support</div>
                    </div>';
                    }
                }
            } else {
                echo do_shortcode('[merkai_registration_block 
            register_redirect="/checkout?step=2&ans=checkemail" 
            login_redirect="/checkout#payment_method_merkai"]');
            }
    }

    public function do_health_check($option_name, $old_value, $new_value) {

        if($option_name === 'woocommerce_merkai_settings') {

            if(!$new_value[PAYNOCCHIO_SECRET_KEY]) {
                add_action( 'admin_notices', function(){
                    echo '<div class="notice notice-error"><p>Please check if Secret Key is correct</p></div>';
                } );
            }

            add_action( 'admin_notices', array( $this,	'make_notice') );
        }
    }

    public function make_notice() {
        $fake_uuid = wp_generate_uuid4();
        $wallet = new Woocommerce_Merkai_Wallet($fake_uuid);
        $response = $wallet->healthCheck();
        $json_response = json_decode($response);

        if($json_response->status === 'success') {
            update_option('woocommerce_merkai_approved', true);
            echo "<div class=\"notice notice-success is-dismissible\"><p>". sprintf( __( "Integration with Merkai succeeded. Response is <strong>%s</strong> and status code is <strong>%s</strong>" ), $json_response->status, $json_response->message) ."</p></div>";
        } else {

            update_option('woocommerce_merkai_approved', false);
            echo "<div class=\"notice notice-error is-dismissible\"><p>". sprintf( __( "Integration with Merkai failed. Response is <strong>%s</strong> " ), $json_response->message) ."</p></div>";
        }
    }

}