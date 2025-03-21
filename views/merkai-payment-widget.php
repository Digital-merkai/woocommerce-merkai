<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php

global $woocommerce;
$cart = $woocommerce->cart;
$cart_total = floatval($woocommerce->cart->total);

if (is_user_logged_in()) {
    $merkai = new Woocommerce_Merkai();
    $wallet = $merkai->get_merkai_wallet_info();
?>

<?php
    $merkai_classes = '';
    $accent_color = '#3b82f6';
    $accent_text_color = '#ffffff';

    $settigns = get_option( 'woocommerce_merkai_settings');
    if($settigns) {
        $merkai_classes .= array_key_exists('darkmode', $settigns) && $settigns['darkmode'] == 'yes' ? 'merkai_dark_mode ' : '';
        $merkai_classes .= array_key_exists('rounded', $settigns) && $settigns['rounded'] == 'yes' ? 'merkai_rounded ' : '';
        $embleme_link = plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/merkai_';
        $embleme_link .= array_key_exists('darkmode', $settigns) && $settigns['darkmode'] == 'yes' ? 'white.svg' : 'black.svg';

        if (array_key_exists('accent_color', $settigns)) {
            $accent_color = get_option( 'woocommerce_merkai_settings')['accent_color'];
        }

        if (array_key_exists('accent_text_color', $settigns)) {
            $accent_text_color = get_option( 'woocommerce_merkai_settings')['accent_text_color'];
        }
    }
?>

<style>
    .merkai_colored {
        background-color: <?php echo $accent_color; ?>!important;
        color: <?php echo $accent_text_color; ?>!important;
    }
</style>

<?php if ($wallet['status'] !== 'ACTIVE') { ?>
    <div>
        <p>Current Wallet is <?php echo $wallet['status'] ?></p>
        <?php if ($wallet['status'] !== 'BLOCKED') { ?>
            <p>You can manage it at your wallet <a href="/<?php echo WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG ?>">account page</a>.</p>
        <?php } ?>
    </div>

<?php } else { ?>

<section class="merkai <?php echo $merkai_classes; ?>">
    <?php if(get_transient('first_time_active')) { ?>
        CONGRATS! You have activated your Bonuses Wallet!
    <?php } ?>
    <div class="merkai-payment-block">
        <div class="merkai-embleme">
            <img src="<?php echo $embleme_link; ?>" />
        </div>
        <div class="merkai_tiles">
            <div class="merkai-card-simulator">
                <h3>Your Wallet</h3>
                <div class="merkai-flex merkai-flex-row merkai-items-center merkai-text-white merkai-gap-x-8 merkai-text-xl">
                    <div>
                        <p>Balance</p>
                        <p>$<span class="merkai-numbers merkai-balance-value"><?php echo $wallet['balance'] ?? 0; ?></span></p>
                    </div>
                    <div>
                        <p>Bonuses</p>
                        <p><span class="merkai-numbers merkai-bonus-value"><?php echo $wallet['bonuses'] ?? 0 ?></span></p>
                    </div>
                </div>

                <div class="merkai-flex merkai-flex-row merkai-items-center merkai-gap-2 merkai-flex-wrap">
                    <a href="#" class="btn-blue merkai_button" data-modal=".topUpModal">Add money</a>
                    <a href="#" class="btn-white merkai_button" data-modal=".withdrawModal">Withdraw</a>
                </div>
            </div>
            <div class="merkai-promo-badge">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/cashback_ill.png' ?>"
                     class="!merkai-h-[100px] !merkai-max-h-[100%] !merkai-float-right hidden lg:block" />
                <h3>Ultimate Cashback</h3>
                <p>Make three purchases and get an increased cashback on everything!</p>
                <div class="merkai-flex merkai-flex-row merkai-items-center merkai-gap-x-2 merkai-mt-4">
                    <a class="btn-white merkai_button" href="#">Read more</a>
                </div>
            </div>
        </div>
        <?php if($wallet['bonuses']) {
             if($wallet['bonuses'] < $cart_total) {
                 $max_bonus = $wallet['bonuses'];
             } else {
                 $max_bonus = $cart_total / $wallet['structure']['bonus_conversion_rate'];
             }
            ?>
        <div class="merkai-conversion-rate">
            <h3>
                How much do you want to pay in bonuses?
            </h3>
            <?php
            woocommerce_form_field( 'bonusesvalue', [
                'type'        => 'number',
                'id'          => 'bonuses-value',
                'label'       => '',
                'placeholder' => '',
                'default'     => '',
                'input_class' => ['short focus:!merkai-outline-none'],
            ] );
            ?>
            <input id="bonuses-input" type="range" min="0" max="<?php echo $max_bonus; ?>" step="1" value="0" class="styled-slider slider-progress" />
        </div>
    <?php } ?>
        <div class="merkai-flex merkai-flex-row merkai-gap-x-4 merkai-mt-8 merkai-text-sm merkai-flex-wrap">
            <a href="/merkai-account">Merkai Account</a>
            <a href="<?php echo get_privacy_policy_url(); ?>">Privacy Policy</a>
            <a href="<?php echo get_permalink( wc_terms_and_conditions_page_id() ); ?>">Terms and Conditions</a>
        </div>
        <div class="checkout_pricing">
            <div class="price">
                <h4>Total:</h4>
                <h2><?php wc_cart_totals_order_total_html(); ?></h2>
            </div>
            <div class="bonus">
                <h4>You earning for this purchase:</h4>
                <h2>+146 bonuses</h2>
            </div>
        </div>
    </div>
</section>
    <?php }  ?>

<?php } ?>
<?php echo do_shortcode('[merkai_modal_forms]'); ?>