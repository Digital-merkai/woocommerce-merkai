<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
    global $woocommerce;
    $amount = WC()->cart->cart_contents_total ?? 0;

    $merkai_classes = '';
    $settings = get_option( 'woocommerce_merkai_settings');
    if($settings) {
        $merkai_classes .= array_key_exists('darkmode', $settings) && $settings['darkmode'] == 'yes' ? 'merkai_dark_mode ' : '';
        $merkai_classes .= array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'merkai_rounded ' : '';
    }
?>

<div class="merkai-cart-wallet-widget merkai-flex merkai-flex-row merkai-items-center merkai-relative merkai-text-black <?php echo $merkai_classes; ?>">
    <!--<div class="cart">
        <a href="<?php /*echo wc_get_cart_url(); */?>" alt="Checkout" title="Checkout">
            <div class="merkai-flex merkai-flex-row merkai-items-center merkai-flex-nowrap">
                <img src="<?php /*echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/cart.svg' */?>" class="!merkai-h-[25px] merkai-w-auto merkai-mr-2"/>
                <?php /*if (!is_user_logged_in()) { */?>
                    <div>Your order: <span class="merkai-font-semibold">$<?php /*echo $amount; */?></span></div>
                <?php /*} else { */?>
                    <div class="merkai-font-semibold">$<?php /*echo $amount; */?></div>
                <?php /*} */?>
            </div>
        </a>
    </div>-->

    <?php if (is_user_logged_in()) { ?>
        <div class="cart merkai-bg-white merkai-rounded-lg merkai-p-2">
            <a href="<?php echo wc_get_page_permalink('myaccount') . WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG ?>" alt="Account" title="Account">
                <div class="merkai-flex merkai-flex-row merkai-items-center">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/profile.svg' ?>" class="!merkai-h-[25px] merkai-w-auto"/>
                </div>
            </a>
        </div>
    <?php } else { ?>
        <div class="cart !merkai-bg-blue-500 !merkai-text-white merkai-rounded-lg merkai-p-2">
            <a href="<?php echo wc_get_page_permalink('myaccount') . WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG ?>" alt="Account" title="Account">
                <div class="merkai-flex merkai-flex-row merkai-items-center merkai-gap-2">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/profile-white.svg' ?>" class="!merkai-h-[25px] merkai-w-auto"/>
                    <div class="!merkai-text-white !merkai-font-normal">Log In</div>
                </div>
            </a>
        </div>
    <?php } ?>

    <?php if (is_user_logged_in() && get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
        $merkai = new Woocommerce_Merkai();
        $wallet = $merkai->get_merkai_wallet_info();
        $wallet_balance = round($wallet['balance'], 2);
        $wallet_bonuses = $wallet['bonuses'];
        ?>

    <?php if ($wallet['code'] !== 500) { ?>
        <div class="wallet merkai-wallet <?php if ($wallet['status'] !== 'ACTIVE') { ?>merkai-disabled<?php } ?>"  alt="Balance" title="Balance">
            <div class="merkai-flex merkai-flex-row merkai-items-center merkai-pr-2 merkai-gap-x-2">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/wallet.svg' ?>" class="!merkai-h-[25px] merkai-w-auto"/>
                <p class="merkai-font-semibold">$<span class="merkai-numbers merkai-balance-value" data-balance="<?php echo $wallet_balance ?? 0 ?>"><?php echo $wallet_balance ?? 0 ?></span></p>
            </div>
            <div class="merkai-flex merkai-flex-row merkai-items-center merkai-gap-x-2" alt="Bonuses" title="Bonuses">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/bonuses.svg' ?>" class="!merkai-h-[25px] merkai-w-auto"/>
                <p class="merkai-font-semibold"><span class="merkai-numbers merkai-bonus-value" data-bonus="<?php echo $wallet_bonuses ?? 0 ?>"><?php echo $wallet_bonuses ?? 0 ?></span></p>
            </div>
        </div>
        <?php } else {?>
            <div class="wallet merkai-wallet merkai-gap-2">Error. Please contact <strong><?php echo get_bloginfo('name') ?></strong> support</div>
        <?php } ?>
    <?php } ?>
</div>
