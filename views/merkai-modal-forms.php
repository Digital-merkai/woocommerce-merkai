<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
if (is_user_logged_in()) {

    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_Error( 'no_user', 'Invalid user', array( 'status' => 404 ) );
    }
    $user_uuid = get_user_meta($user_id, PAYNOCCHIO_USER_UUID_KEY, true);
    $wallet_uuid = get_user_meta($user_id, PAYNOCCHIO_WALLET_KEY, true);

    $merkai = new Woocommerce_Merkai();
    $wallet_info = $merkai->get_merkai_wallet_info();

    $wallet_structure = $wallet_info['structure'] ?? null;
    $wallet_balance = $wallet_info['balance'] ?? 0;
    $wallet_bonuses = $wallet_info['bonuses'] ?? 0;
    $minimum_topup_amount = $wallet_structure['minimum_topup_amount'] ?? 0;
    $minimum_withdraw_amount = 1; //TODO: Minimum withdraw amount is not set
    $card_balance_limit = $wallet_structure['card_balance_limit'] ?? 0;
    $rewarding_rules = $wallet_structure['rewarding_group']->rewarding_rules ?? null;

    // STYLING OPTIONS //
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

<div class="modal topUpModal <?php echo $merkai_classes; ?>">
    <div class="close-modal close"></div>
    <div class="container">
        <div class="header">
            <h3>Deposit</h3>
            <button class="close">&times;</button>
        </div>
        <div class="content">
            <div class="merkai-mb-4 merkai-text-lg">
                Please enter amount you want to add to you wallet. After clicking "Add money" you will be redirected to Stripe for secure money transfer.
            </div>
            <div class="merkai-mb-4">The minimum top up amount for this card is $<span id="minimum_topup_amount"><?php echo $minimum_topup_amount; ?></span>.</div>
            <div class="merkai-mb-6">Card limit is $<span id="card_balance_limit"><?php echo $card_balance_limit; ?></span>.</div>
            <div class="top-up-amount-container merkai-mt-8 lg:merkai-mt-12 merkai-flex merkai-flex-row merkai-justify-between">
                <div>
                    <span class="merkai-text-3xl">$</span>
                    <input type="number" name="amount" id="top_up_amount"
                           min="<?php echo $minimum_topup_amount; ?>"
                           value="" step="0.01"
                           placeholder="<?php echo $minimum_topup_amount; ?>"
                    />
                    <input type="hidden" name="redirect_url" value="" />
                    <?php wp_nonce_field( 'merkai_ajax_top_up', 'ajax-top-up-nonce' ); ?>
                </div>
            </div>

            <div class="top-up-variants">
                <a id="variant_1000">
                    $1000
                </a>
                <a id="variant_2000">
                    $2000
                </a>
                <a id="variant_5000">
                    $5000
                </a>
                <a id="variant_10000">
                    $10 000
                </a>
                <a id="variant_15000">
                    $15 000
                </a>
            </div>
            <!--<p class="merkai-flex merkai-flex-row merkai-items-center merkai-mt-4 merkai-gap-x-0.5">
                The sending bank may charge a fee.<a href="#">Here's how to avoid it.</a> >
            </p>-->

           <!-- <div class="autodeposit merkai-flex merkai-flex-row merkai-items-center merkai-mt-8 lg:merkai-mt-12 merkai-gap-x-2">
                <img src="<?php /*echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/i-gr.png' */?>"
                     class="merkai-h-[18px] merkai-w-[16px] merkai-mr-1 merkai-inline-block" />
                Autodeposit
                <div class="toggle-autodeposit">
                    <p>ON</p>
                    <div class="toggler"></div>
                    <p>OFF</p>
                </div>
                <input type="hidden" value="0" name="autodeposit" id="autodeposit" />
            </div>-->

        </div>
        <div class="footer">
            <div>
                <button id="top_up_button"
                        type="button" class="merkai-btn-primary merkai_button merkai_colored">
                    Add money
                    <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <div class="message merkai-text-balance" id="topup_message">Please enter the amount to top up your wallet.</div>
            </div>
        </div>
    </div>
    </form>
</div>

<div class="modal withdrawModal <?php echo $merkai_classes; ?>">
    <div class="close-modal close"></div>
    <div class="container">
        <div class="header">
            <h3>Withdraw</h3>
            <button class="close">&times;</button>
        </div>
        <div id="witdrawForm" class="content">
            <div class="merkai-mb-4 merkai-text-lg">
                Please enter the amount you want to withdraw from your wallet. After clicking the "Withdraw" button, the funds will be credited to your Stripe account.
            </div>
            <div class="merkai-mb-4">The minimum withdraw amount for this card is $<span id="minimum_topup_amount"><?php echo $minimum_withdraw_amount; ?></span>.</div>
            <div class="merkai-mb-8 merkai-text-xl">Current balance: <span class="merkai-font-semibold">$<span class="merkai-numbers merkai-balance-value" data-balance="<?php echo $wallet_balance; ?>"><?php echo $wallet_balance; ?></span></span></div>
            <div class="withdraw-amount-container merkai-mb-8 merkai-flex merkai-items-center">
                <div class="merkai-text-3xl">$</div>
                <input type="number" step="0.01" class="!merkai-bg-transparent !merkai-border-0 !merkai-shadow-none merkai-text-3xl !merkai-p-0 focus:!merkai-outline-none"
                       name="amount" id="withdraw_amount" placeholder="0" value="" />
                <?php wp_nonce_field( 'merkai_ajax_withdraw', 'ajax-withdraw-nonce' ); ?>
            </div>
        </div>
        <div class="footer">
            <div>
                <button id="withdraw_button"
                        type="button"
                        class="merkai-btn-primary merkai_button merkai_colored disabled" disabled="disabled">
                    Withdraw
                    <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <div class="message merkai-text-balance" id="withdraw_message">Please enter the amount to withdraw.</div>
            </div>
        </div>
    </div>
</div>

    <?php
    /** Suspension and Deletion modals */
    ?>
    <?php wp_nonce_field( 'merkai_ajax_set_status', 'ajax-status-nonce' ); ?>
    <div class="modal suspendModal <?php echo $merkai_classes; ?>">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Suspend your Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="merkai-mb-4">Suspension blocks all transactions until further actions.</p>
                <p>
                    <strong>Are you sure you want to suspend your wallet?</strong>
                </p>
            </div>
            <div class="footer">
                <div>
                    <button id="suspend_button"
                            type="button"
                            class="merkai-btn-primary close merkai-rounded-lg">
                        Suspend
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button
                            class="merkai-btn-primary close merkai-rounded-lg btn-gray"
                            type="button">Cancel</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal reactivateModal <?php echo $merkai_classes; ?>">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Reactivate your Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="merkai-mb-4">After reactivating the wallet you can continue shopping.</p>
                <p>
                    <strong>Are you sure you want to reactivate your wallet?</strong>
                </p>
            </div>
            <div class="footer">
                <div>
                    <button id="reactivate_button"
                            type="button"
                            class="merkai-btn-primary close merkai-rounded-lg">
                        Reactivate
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button
                            class="merkai-btn-primary close merkai-rounded-lg btn-gray"
                            type="button">Cancel</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal blockModal <?php echo $merkai_classes; ?>">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Block your Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="merkai-mb-4">Are you sure you want to BLOCK your wallet?</p>
                <p class="merkai-font-bold">Attention! This action cannot be undone.</p>
            </div>
            <div class="footer">
                <div>
                    <button id="block_button"
                            type="button"
                            class="merkai-btn-primary close merkai-rounded-lg">
                        Block
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button
                            class="merkai-btn-primary close merkai-rounded-lg btn-gray"
                            type="button">Cancel</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal deleteModal <?php echo $merkai_classes; ?>">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Delete your Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="merkai-mb-4">Are you sure you want to DELETE your wallet?</p>
                <p class="merkai-font-bold">Attention! If you have blocked your wallet by accident, please contact <a href="mailto:support@kopybara.com" class="merkai-text-blue-500">technical support</a> before deleting your wallet!</p>
            </div>
            <div class="footer">
                <div>

                    <button id="delete_button"
                            type="button"
                            class="merkai-btn-primary close merkai-rounded-lg">
                        Delete
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'merkai_ajax_delete_wallet', 'ajax-delete-nonce' ); ?>

                    <button
                            class="merkai-btn-primary close merkai-rounded-lg btn-gray"
                            type="button">Cancel</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>