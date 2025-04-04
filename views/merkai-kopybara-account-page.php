<?php
if (!defined('ABSPATH')) {
    die;
}
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

    <style>
        .merkai_colored {
            background-color: <?php echo $accent_color; ?>!important;
            color: <?php echo $accent_text_color; ?>!important;
        }
    </style>

<?php if (is_user_logged_in()) {

    $merkai = new Woocommerce_Merkai();
    $wallet = $merkai->get_merkai_wallet_info();

    global $current_user;
    ?>
    <section class="merkai <?php echo $merkai_classes; ?>">
        <div class="merkai-new-account">
            <div class="merkai-account-menu">
                <div class="merkai-profile-info merkai-p-8">
                    <div class="merkai-profile-text">
                        <h2>
                            <?php echo $wallet['user']['first_name']. ' ' .$wallet['user']['last_name']; ?>
                        </h2>
                        <p>
                            <?php echo $current_user->user_email; ?>
                        </p>
                    </div>
                </div>
                <menu class="merkai_tab_switchers">
                    <li>
                        <a class="tab-switcher choosen" id="bonuses_toggle">Bonuses</a>
                    </li>
                    <li>
                        <a class="tab-switcher" id="history_toggle">History</a>
                    </li>
                    <li>
                        <a class="tab-switcher" id="account_details_toggle">Account Details</a>
                    </li>
                    <li>
                        <a href="<?php echo wp_logout_url('/') ?>">Log out</a>
                    </li>
                </menu>
            </div>
            <div class="merkai-account-content">
                <?php
                /**
                 * Debug
                 */
                /*print_r($wallet);

                echo '<br/><br/>secret: ' .$wallet['secret'];
                echo '<br/><br/>env_uuid: ' .$wallet['env'];
                echo '<br/><br/>user_uuid: ' .$wallet['user_uuid'];

                echo '<br/><br/>HASH: '.hash("sha256", $wallet['secret'] . "|" . $wallet['env'] . "|" . $wallet['user_uuid']);*/

                ?>
                <div class="merkai_tab merkai-tab-body visible" id="merkai_bonuses_body">
                    <?php if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY, true)) { ?>
                        <div class="merkai-profile-block">
                            <?php echo do_shortcode('[merkai_activation_block]'); ?>
                        </div>
                    <?php } else { ?>
                        <div class="merkai-profile-block <?php if ($wallet['status'] !== 'ACTIVE') { ?>merkai-disabled<?php } ?>">
                            <div class="merkai-card-container">
                                <div class="merkai-card" style="color:<?php echo $accent_text_color; ?>; background:<?php echo $accent_color; ?>">
                                    <?php if ($merkai_embleme_url) { ?>
                                    <img src="<?php echo $merkai_embleme_url; ?>" class="on_card_embleme" />
                                    <?php }
                                    if ($wallet['status'] == 'SUSPEND') {
                                        echo '<div class="wallet_status">SUSPENDED</div>';
                                    } elseif ($wallet['status'] == 'BLOCKED') {
                                        echo '<div class="wallet_status">BLOCKED</div>';
                                    }
                                    ?>
                                    <div class="merkai-balance-bonuses">
                                        <div class="merkai-balance">
                                            <div>
                                                Balance
                                            </div>
                                            <div class="amount">
                                                $<span class="merkai-numbers merkai-balance-value" data-balance="<?php echo $wallet['balance'] ?>"><?php echo $wallet['balance'] ?></span>
                                            </div>
                                        </div>
                                        <div class="merkai-bonuses">
                                            <div>
                                                Bonuses
                                            </div>
                                            <div class="amount">
                                                <span class="merkai-numbers merkai-bonus-value" data-bonus="<?php echo $wallet['bonuses'] ?>"><?php echo $wallet['bonuses'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="merkai-card-number">
                                        <div><?php echo chunk_split(strval($wallet['card_number']), 4, '</div><div>'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="merkai-actions-btns merkai-mt-8">
                                <div class="autodeposit merkai-flex merkai-flex-row merkai-items-center merkai-gap-x-2">
                                   <!-- <img src="<?php /*echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/i-gr.png' */?>"
                                         class="merkai-h-[18px] merkai-w-[16px] merkai-mr-1 merkai-inline-block" />
                                    Autodeposit
                                    <div class="toggle-autodeposit">
                                        <p>ON</p>
                                        <div class="toggler"></div>
                                        <p>OFF</p>
                                    </div>
                                    <input type="hidden" value="0" name="autodeposit" id="autodeposit" />-->
                                </div>
                                <div class="merkai-actions-btns">
                                    <a href="#" class="merkai_button merkai_colored" data-modal=".topUpModal">
                                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/plus.png' ?>"
                                             class="merkai-h-[24px] merkai-w-[24px] merkai-mr-1 merkai-inline-block" />
                                        Add money
                                    </a>
                                    <a href="#" class="merkai_button btn-gray" data-modal=".withdrawModal">
                                        Withdraw
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php if(isset($wallet['status'])) { ?>
                            <div class="merkai-profile-block">
                                <div class="merkai-profile-actions">
                                    <div class="merkai-mb-4 merkai-flex merkai-flex-row merkai-gap-2">
                                        <div>Wallet Status:</div>
                                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span id="wallet_status" class="merkai-font-bold"><?php echo $wallet['status'] ?></span>
                                    </div>
                                    <?php if ($wallet['status'] !== 'BLOCKED') { ?>
                                        <div class="merkai-actions-btns !merkai-justify-start">
                                            <?php if($wallet['status'] === "ACTIVE") { ?>
                                                <a href="#" class="merkai_button btn-blue merkai_colored" data-modal=".suspendModal">Suspend wallet</a>
                                            <?php } elseif ($wallet['status'] === "SUSPEND") { ?>
                                                <a href="#" class="merkai_button btn-blue merkai_colored" data-modal=".reactivateModal">Reactivate wallet</a>
                                            <?php } ?>
                                            <a href="#" class="merkai_button btn-gray" data-modal=".blockModal">
                                                Block wallet
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php if($wallet['status'] === 'BLOCKED') { ?>
                                            <div class="merkai-flex merkai-flex-row merkai-gap-8 merkai-items-center">
                                                <button data-modal=".deleteModal" class="merkai-btn-primary !merkai-rounded-lg merkai-whitespace-nowrap">Delete Wallet</button>
                                                <div class="">If you have blocked your wallet by accident, please contact <a href="mailto:support@kopybara.com" class="merkai-text-blue-500">technical support</a> before deleting your wallet!</div>
                                            </div>

                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="merkai_tab merkai-tab-body" id="merkai_history_body">
                    <div class="merkai-profile-block">
                        <h2>History</h2>
                        <table class="history_table">
                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>Date</td>
                                <td>Status</td>
                                <td>Total</td>
                                <td>Bonuses<br>spent</td>
                                <td>Bonuses<br>earned</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $args = array(
                                'customer_id' => get_current_user_id(),
                                'limit' => -1, // to retrieve _all_ orders by this user

                            );
                            $orders = wc_get_orders($args);

                            // print_r($orders);

                            foreach($orders as $order) {

                                $date = strtotime($order->date_created);
                                $newformatdate = date('F j, Y',$date);

                                $bonuses_spent = 0;
                                $bonuses_spent_base = $order->get_meta( 'bonuses_value' );
                                if ($bonuses_spent_base) {
                                    $bonuses_spent = $bonuses_spent_base;
                                } else {
                                    $bonuses_spent = '';
                                }

                                $item_count = $order->get_item_count() - $order->get_item_count_refunded();

                                echo '<tr>
                                        <td>#'.$order->get_id().'</td>
                                        <td>'.$newformatdate.'</td>
                                        <td class="merkai-capitalize">'.$order->status.'</td>
                                        <td>'.wp_kses_post( sprintf( _n( '%1$s', '%1$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) ).'</td>
                                        <td class="merkai-text-gray-700">'.$bonuses_spent.'</td>
                                        <td class="merkai-text-green-700">+ '.$order->total * 0.1.'</td>
                                      </tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="merkai_tab merkai-tab-body" id="merkai_account_details_body">
                    <div class="merkai-profile-block woocommerce">
                        <h2>Account Details</h2>

                        <?php do_action( 'woocommerce_before_edit_account_form' ); ?>

                        <form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

                            <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

                            <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                                <label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $current_user->first_name ); ?>" />
                            </p>
                            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                                <label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $current_user->last_name ); ?>" />
                            </p>
                            <div class="clear"></div>

                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $current_user->display_name ); ?>" /> <span><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
                            </p>
                            <div class="clear"></div>

                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                                <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" />
                            </p>

                            <fieldset>
                                <legend><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
                                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
                                </p>
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-first">
                                    <label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
                                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
                                </p>
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-last">
                                    <label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
                                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
                                </p>
                            </fieldset>
                            <div class="clear"></div>

                            <?php do_action( 'woocommerce_edit_account_form' ); ?>

                            <p>
                                <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
                                <button type="submit" class="!merkai-bg-blue-500 !merkai-rounded-lg !merkai-text-white hover:!merkai-bg-blue-500/80 !merkai-mt-4 woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
                                <input type="hidden" name="action" value="save_account_details" />
                            </p>

                            <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
                        </form>

                        <?php do_action( 'woocommerce_after_edit_account_form' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php } else {
    echo do_shortcode('[merkai_registration_block]');
} ?>

<script>
    jQuery('a.tab-switcher').click(function() {
        let link = jQuery(this);
        let id = link.get(0).id;
        id = id.replace('_toggle','');

        let elem = jQuery('#merkai_' + id + '_body');
        if (!elem.hasClass('visible')) {
            jQuery('.merkai_tab_switchers a').removeClass('choosen');
            link.addClass('choosen');
            elem.siblings('.merkai-tab-body').removeClass('visible').fadeOut('fast', function () {
                elem.fadeIn('fast').addClass('visible');
            });
        }
    });
</script>

<?php echo do_shortcode('[merkai_modal_forms]'); ?>