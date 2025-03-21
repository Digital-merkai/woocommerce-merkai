<?php
if (!defined('ABSPATH')) {
    die;
}

$wallet_bal = 0;
?>

<section class="merkai">
    <div class="article-body merkai-max-w-4xl merkai-mx-auto merkai-mt-4">
        <div class="merkai-mb-10 lg:merkai-mb-20">
            <img
                    class="merkai-block !merkai-mx-auto"
                    src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/debit_card_example.png' ?>"
                    alt="" />
        </div>
        <div class="merkai-grid merkai-grid-cols-[40px_1fr] merkai-gap-x-6 merkai-gap-y-12 merkai-mb-10">
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_tickets.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="merkai-h2 merkai-mb-0">Fly on the right day. Always.</h2>
                <p class="merkai-text-base">We will book a ticket and notify you in advance if there is a seat available on the plane. Refund the money for the ticket if you do not fly.</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_dollar.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="merkai-h2 merkai-mb-0">Ultimate cashback</h2>
                <p class="merkai-text-base">Make 3 purchases and get an increased cashback on everything</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_coupon.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="merkai-h2 merkai-mb-0 merkai-tab-selector">Pay with bonuses</h2>
                <p class="merkai-text-base">Make 5 purchases and get 500 bonuses that can be spent on flights.</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_magic.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="merkai-h2 merkai-mb-0">Unique card design from AI</h2>
                <p class="merkai-text-base">Our AI will generate your individual unique map design for you.</p>
            </div>
        </div>
        <?php if(is_user_logged_in()) { ?>
            <?php if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) { ?>
                <div class="merkai-flex merkai-justify-center merkai-mb-10">
                    <button id="merkai_activation_button"
                            type="button"
                            class="merkai-btn-primary">
                        Activate Merkai.Pay
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'merkai_ajax_activation', 'ajax-activation-nonce' ); ?>
                </div>
            <?php } else { ?>
                <div class="merkai-flex merkai-justify-center merkai-my-10">
                    <a href="/<?php echo WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG; ?>" class="merkai-btn-primary">Go to Merkai.Pay</a>
                </div>
            <?php } ?>

            <div class="merkai-pl-10 merkai-pb-6 merkai-text-center">
                <p class="merkai-text-slate-500">I agree to <a href="#" class="merkai-text-slate-500 merkai-underline">Merkai.Pay Terms & Conditions</a> and <a href="#" class="merkai-text-slate-500 merkai-underline">Rules of Merkai.Pay Priority program</a> </p>
            </div>
        <?php } else { ?>
            <button id="merkai_anonimous_activation_button"
                    type="button"
                    class="merkai-btn-primary">
                Activate Merkai.Pay
                <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
            <input id="is_merkai_user_exist" type="hidden" name="is_merkai_user_exist" value="false" />
            <?php wp_nonce_field( 'merkai_ajax_activation', 'ajax-activation-nonce' ); ?>

            <div class="merkai-payment-block" id="merkai-payment-block" style="display: none">
                <div class="merkai-grid merkai-grid-cols-[1fr_1fr] merkai-gap-x-8 merkai-items-stretch">
                    <div class="merkai-card-simulator">
                        <h3 class="!merkai-mb-0">Merkai.Pay</h3>
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

                        <div class="merkai-flex merkai-flex-row merkai-items-center merkai-gap-x-2">
                            <a href="#" class="btn-blue" data-modal=".topUpModal">Add money</a>
                            <a href="#" class="btn-white" data-modal=".withdrawModal">Withdraw</a>
                        </div>
                    </div>
                    <div class="merkai-promo-badge">
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/cashback_ill.png' ?>" class="merkai-max-h-full merkai-float-right" />
                        <h3 class="!merkai-mb-0">Ultimate Cashback</h3>
                        <p>Make three purchases and get an increased cashback on everything!</p>
                        <a class="btn-white merkai-absolute merkai-bottom-4 merkai-left-4" href="#">Read more</a>
                    </div>
                </div>
                <?php if($wallet['bonuses']) {
                    if($wallet['bonuses'] < $cart_total) {
                        $max_bonus = $wallet['bonuses'];
                    } else {
                        $max_bonus = $cart_total;
                    }
                    ?>
                    <div class="merkai-conversion-rate merkai-mt-8">
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
                            'input_class' => ['short'],
                        ] );
                        ?>
                        <input id="bonuses-input" type="range" min="0" max="<?php echo $max_bonus; ?>" step="1" value="0" class="styled-slider slider-progress" />

                    </div>
                <?php } ?>
            </div>
            <?php
            /*$register_redirect = $attr['register_redirect'] ?? '';
            $login_redirect = $attr['login_redirect'] ?? '';
            echo do_shortcode('[merkai_registration_block 
            register_redirect="'.$register_redirect.'" 
            login_redirect="'.$login_redirect.'"]');*/
            ?>
        <?php } ?>
    </div>
</section>
<?php