<?php

/**
 * Class for rendering shortcodes
 */

class Woocommerce_Merkai_Shortcodes {

    static function merkai_activation_block($attr) {
        $plugin = new Woocommerce_Merkai();

        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-kopybara-activation-block.php');
        } else {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-unapproved.php');
        }
        return ob_get_clean();
    }

    static function merkai_registration_block($attr) {
        ob_start();
        require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-kopybara-registration-block.php');
        return ob_get_clean();
    }

    static function merkai_account_page() {
        $plugin = new Woocommerce_Merkai();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-kopybara-account-page.php');
        } else {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-unapproved.php');
        }
        return ob_get_clean();
    }

    static function merkai_payment_widget() {
        $plugin = new Woocommerce_Merkai();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-kopybara-payment-widget.php');
        } else {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-unapproved.php');
        }
        return ob_get_clean();
    }

    static function merkai_modal_forms() {
        $plugin = new Woocommerce_Merkai();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-modal-forms.php');
        } else {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-unapproved.php');
        }
        return ob_get_clean();
    }

    static function merkai_cart_wallet_widget() {
        $plugin = new Woocommerce_Merkai();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-cart-wallet-widget.php');
        } else {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-unapproved.php');
        }
        return ob_get_clean();
    }

    static function merkai_kopybara_cart_wallet_widget() {
        $plugin = new Woocommerce_Merkai();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-kopybara-cart-wallet-widget.php');
        } else {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-unapproved.php');
        }
        return ob_get_clean();
    }

    static function merkai_wallet_management_page () {
        $plugin = new Woocommerce_Merkai();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-wallet-management-page.php');
        } else {
            require(WOOCOMMERCE_MERKAI_PATH . 'views/merkai-unapproved.php');
        }
        return ob_get_clean();
    }
}