<?php
if (!defined('ABSPATH')) {
    die;
}

$wallet_bal = 0;
?>

<?php
$merkai_classes = '';
$accent_color = '#3b82f6';
$accent_text_color = '#ffffff';

$settings = get_option( 'woocommerce_merkai_settings');
if($settings) {
    $merkai_classes .= array_key_exists('darkmode', $settings) && $settings['darkmode'] == 'yes' ? 'merkai_dark_mode ' : '';
    $merkai_classes .= array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'merkai_rounded ' : '';
    $merkai_rounded_class = array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'merkai-rounded-lg' : '';
    $merkai_embleme_url = array_key_exists('embleme_url', $settings) && $settings['embleme_url'] ? $settings['embleme_url'] : '';

    if (array_key_exists('accent_color', $settings)) {
        $accent_color = get_option( 'woocommerce_merkai_settings')['accent_color'];
    }

    if (array_key_exists('accent_text_color', $settings)) {
        $accent_text_color = get_option( 'woocommerce_merkai_settings')['accent_text_color'];
    }
}
?>

    <section class="merkai">
        <div class="article-body merkai-max-w-4xl merkai-mx-auto merkai-mt-4">
            <div class="merkai-my-10">
               <!-- <img
                    class="merkai-block !merkai-mx-auto !merkai-w-full !merkai-max-w-[350px]"
                    src="<?php /*echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/card.webp' */?>"
                    alt="" />-->
                <div class="merkai-card-container">
                    <div class="merkai-card" style="color:<?php echo $accent_text_color; ?>; background:<?php echo $accent_color; ?>">
                        <?php if ($merkai_embleme_url) { ?>
                            <img src="<?php echo $merkai_embleme_url; ?>" class="on_card_embleme" />
                        <?php } ?>
                        <div class="merkai-balance-bonuses">
                            <div class="merkai-balance">
                                <div>
                                    Balance
                                </div>
                                <div class="amount">
                                    $0
                                </div>
                            </div>
                            <div class="merkai-bonuses">
                                <div>
                                    Bonuses
                                </div>
                                <div class="amount">
                                    0
                                </div>
                            </div>
                        </div>
                        <div class="merkai-card-number">
                            <div>• • • •</div>
                            <div>• • • •</div>
                            <div>• • • •</div>
                            <div>• • • •</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="merkai-grid merkai-grid-cols-[1fr_1fr] merkai-gap-x-6 merkai-gap-y-12 merkai-mb-10 merkai-p-8 merkai-items-top">
                <div class="merkai-grid merkai-grid-cols-[50px_1fr] merkai-gap-6 merkai-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_buy.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h2 class="merkai-h2 merkai-mb-0">Purchase bonus</h2>
                        <p class="merkai-text-base">Earn 1 bonus for every 1$ purchase.</p>
                    </div>
                </div>
                <div class="merkai-grid merkai-grid-cols-[50px_1fr] merkai-gap-6 merkai-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_topup.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h2 class="merkai-h2 merkai-mb-0">Add money</h2>
                        <p class="merkai-text-base">Earn an additional bonuses for each adding a money to wallet.</p>
                    </div>
                </div>
            </div>
            <?php if(is_user_logged_in()) { ?>
                <?php if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) { ?>
                    <div class="merkai-mb-10">
                        <div class="merkai-flex merkai-justify-center">
                            <button id="merkai_activation_button"
                                    type="button"
                                    class="merkai-btn-primary merkai-rounded-lg">
                                Activate your Wallet
                                <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                            <?php wp_nonce_field( 'merkai_ajax_activation', 'ajax-activation-nonce' ); ?>
                        </div>
                        <div class="merkai-text-center" id="response_message"></div>
                    </div>
                <?php } else { ?>
                    <div class="merkai-flex merkai-justify-center merkai-my-10">
                        <a href="/<?php echo WOOCOMMERCE_MERKAI_ACCOUNT_PAGE_SLUG; ?>" class="merkai-btn-primary">Go to Account</a>
                    </div>
                <?php } ?>

                <div class="merkai-pl-10 merkai-pb-6 merkai-text-center">
                    <p class="merkai-text-slate-500">I agree to <a href="#" class="merkai-text-slate-500 merkai-underline">Terms & Conditions</a> and <a href="#" class="merkai-text-slate-500 merkai-underline">Rules of Priority program</a> </p>
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