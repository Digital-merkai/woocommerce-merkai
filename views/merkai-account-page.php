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
        $embleme_link = plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/merkai_';
        $embleme_link .= array_key_exists('darkmode', $settings) && $settings['darkmode'] == 'yes' ? 'white.svg' : 'black.svg';

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
    <section class="merkai  <?php echo $merkai_classes; ?>">
        <div class="merkai-account">
            <div class="merkai-embleme">
                <img src="<?php echo $embleme_link; ?>" />
            </div>
            <div class="merkai-profile-info">
                <div class="merkai-profile-img">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/profile.png' ?>" />
                </div>
                <div class="merkai-profile-text">
                    <h2>
                        <?php echo $wallet['user']['first_name']. ' ' .$wallet['user']['last_name']; ?>
                    </h2>
                    <p>
                        <?php echo $current_user->user_email; ?>
                    </p>
                    <a href="#">
                        Start earning bonuses
                    </a>
                </div>
                <?php if(isset($wallet['status'])) { ?>
                    <div class="merkai-profile-actions">
                        <div class="merkai-mb-4 merkai-text-gray-500">Wallet Status:
                            <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="wallet_status" class="merkai-font-bold"><?php echo $wallet['status'] ?></span></div>
                        <?php if($wallet['status'] !== 'BLOCKED') { ?>
                            <label class="dropdown">
                                <div class="action-button">
                                    Manage wallet
                                </div>

                                <input type="checkbox" class="wallet-input" />

                                <ul class="wallet-menu">
                                    <?php if($wallet['status'] === "ACTIVE") { ?>
                                        <li><a href="#" data-modal=".suspendModal">Suspend wallet</a></li>
                                    <?php }
                                    if ($wallet['status'] === "SUSPEND") { ?>
                                        <li><a href="#" data-modal=".reactivateModal">Reactivate wallet</a></li>
                                    <?php } ?>

                                    <li><a class="merkai-text-red-500" href="#" data-modal=".blockModal">Block wallet</a></li>
                                </ul>
                            </label>
                        <?php } ?>

                        <?php if($wallet['status'] === 'BLOCKED') { ?>
                            <button data-modal=".deleteModal" class="merkai-btn-primary">Delete Wallet</button>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <div class="merkai-tab-selector">
                <a class="tab-switcher choosen" id="profile_toggle">
                    Profile
                </a>
                <a class="tab-switcher" id="my-orders_toggle">
                    My orders
                </a>
            </div>
            <div class="merkai_tabs">
                <div class="merkai-profile-body merkai-tab-body visible">
                    <?php if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY, true)) { ?>
                        <div class="merkai-profile-block">
                            <div class="merkai-grid merkai-grid-cols-[24px_1fr] merkai-gap-x-6">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/i.png' ?>" />
                                <div class="">
                                    <p class="merkai-mb-4">Join Merkai.Pay program. Save document info to make quicker purchases, earn cashback bonuses, and buy premium tickets.</p>
                                    <button id="merkai_activation_button" type="button" class="merkai_button merkai_colored merkai-btn-primary" value="">
                                        Activate Merkai.Pay
                                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                    <?php wp_nonce_field( 'merkai_ajax_activation', 'ajax-activation-nonce' ); ?>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="merkai-profile-block merkai-blue-badge merkai_colored">
                            <div class="merkai-flex merkai-flex-col lg:merkai-flex lg:merkai-flex-row merkai-justify-between merkai-gap-4">
                                <div>
                                    Merkai.Pay
                                </div>
                                <div>
                                    $<span class="merkai-numbers merkai-balance-value"><?php echo $wallet['balance'] ?? 0 ?></span>
                                </div>
                                <div>
                                    Bonuses: <span class="merkai-numbers merkai-bonus-value"><?php echo $wallet['bonuses'] ?? 0 ?></span>
                                </div>
                                <div>
                                    <a class="tab-switcher merkai-cursor-pointer" id="wallet_toggle">
                                        <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pjxzdmcgdmlld0JveD0iMCAwIDk2IDk2IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjx0aXRsZS8+PHBhdGggZD0iTTY5Ljg0MzcsNDMuMzg3NiwzMy44NDIyLDEzLjM4NjNhNi4wMDM1LDYuMDAzNSwwLDAsMC03LjY4NzgsOS4yMjNsMzAuNDcsMjUuMzktMzAuNDcsMjUuMzlhNi4wMDM1LDYuMDAzNSwwLDAsMCw3LjY4NzgsOS4yMjMxTDY5Ljg0MzcsNTIuNjEwNmE2LjAwOTEsNi4wMDkxLDAsMCwwLDAtOS4yMjNaIi8+PC9zdmc+" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="merkai-profile-block">
                        <h2 class="merkai-font-bold merkai-text-xl merkai-mb-4">Personal Info</h2>
                        <div class="merkai-grid merkai-grid-cols-[1fr_1fr] merkai-gap-x-6">
                            <div class="">
                                <span class="merkai-font-semibold">Birthdate</span>: 11/11/1987
                            </div>
                            <div class="">
                                <span class="merkai-font-semibold">Gender</span>: Male
                            </div>
                        </div>
                    </div>
                </div>

                <div class="merkai-my-orders-body merkai-tab-body">
                    <div class="merkai-profile-block">
                        <h2>My orders</h2>
                        <div class="history-list">

                            <?php

                            $orders = wc_get_orders([
                                'numberposts' => -1,
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'customer_id'  => get_current_user_id(),
                            ]);

                            foreach ($orders as $order) {?>


                                <div class="history-item">
                                    <div class="history-info merkai-text-gray-500">
                                        #<?php echo $order->get_id(); ?>
                                    </div>
                                    <div class="merkai-uppercase">
                                        <?php echo $order->get_status(); ?>
                                    </div>
                                    <div class="history-info merkai-text-gray-500">
                                        <?php
                                        if( $date_completed = $order->get_date_completed() ){
                                            // Display the localized formatted date
                                            echo $date_completed->date_i18n('j F, Y');
                                        }?>
                                    </div>
                                    <div class="history-amount">
                                        <?php echo $order->get_total(); ?>
                                        <?php echo $order->get_currency(); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                 <?php if(isset($wallet['status']) && $wallet['status'] === 'ACTIVE') { ?>
                <div class="merkai-wallet-body merkai-tab-body">
                    <div class="merkai-max-w-5xl merkai-mx-auto merkai-mt-8">
                        <a class="merkai_colored merkai_button btn-back tab-switcher" id="profile_toggle">
                            < Profile
                        </a>
                    </div>
                    <div class="merkai-profile-block">
                        <h2>Merkai.Pay</h2>
                        <div class="merkai-card-container">
                            <div class="merkai-card-face visible">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/card-face.png' ?>" />
                                <a class="card-toggle"></a>
                                <div class="merkai-balance-bonuses">
                                    <div class="merkai-balance">
                                        <div>
                                            Balance
                                        </div>
                                        <div class="amount">
                                            $<span class="merkai-numbers merkai-balance-value"><?php echo $wallet['balance'] ?></span>
                                        </div>
                                    </div>
                                    <div class="merkai-bonuses">
                                        <div>
                                            Bonuses
                                        </div>
                                        <div class="amount">
                                            <span class="merkai-numbers merkai-bonus-value"><?php echo $wallet['bonuses'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="merkai-card-number-mask">
                                    <span class="merkai-text-gray-300 merkai-mr-4">● ● ● ●</span> <?php echo substr(strval($wallet['card_number']), -4); ?>
                                </div>
                            </div>
                            <div class="merkai-card-back">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/card-back.png' ?>" />
                                <a class="card-toggle"></a>
                                <div class="merkai-card-number">
                                    <div><?php echo chunk_split(strval($wallet['card_number']), 4, '</div><div>'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="merkai-actions-btns merkai-mt-8 lg:merkai-mt-16">
                            <div class="autodeposit merkai-flex merkai-flex-row merkai-items-center merkai-gap-x-2">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/i-gr.png' ?>"
                                     class="merkai-h-[18px] merkai-w-[16px] merkai-mr-1 merkai-inline-block" />
                                Autodeposit
                                <div class="toggle-autodeposit">
                                    <p>ON</p>
                                    <div class="toggler"></div>
                                    <p>OFF</p>
                                </div>
                                <input type="hidden" value="0" name="autodeposit" id="autodeposit" />
                            </div>
                            <div class="merkai-actions-btns">
                                <a href="#" class="merkai_button btn-blue merkai_colored" data-modal=".topUpModal">
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

                    <div class="merkai-profile-block">
                        <h2>Payments methods</h2>
                        <a href="" class="merkai_button btn-blue merkai_colored" data-modal=".paymentMethodModal">Add payment method</a>
                    </div>

                    <div class="merkai-profile-block">
                        <h2>History</h2>
                        <div class="history-list">
                            <div class="history-item">
                                <div class="history-info merkai-text-gray-500">
                                    Today
                                </div>
                                <div class="history-amount">
                                    $0
                                </div>
                            </div>
                            <div class="history-item">
                                <div class="history-info">
                                    <p class="merkai-font-semibold">NY - LA</p>
                                    <p>January 3</p>
                                </div>
                                <div class="history-amount">
                                    - $235.29
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
            <div class="merkai-consents">
                <div class="merkai-text-slate-500"><a href="#" class="merkai-text-slate-500 merkai-underline">Merkai Terms & Conditions</a> • <a href="#" class="merkai-text-slate-500 merkai-underline">Rules of Merkai.Pay Priority program</a></div>
            </div>
        </div>
    </section>

    <?php
    /** Suspension and Deletion modals */
    ?>
    <div class="modal suspendModal">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Suspend Merkai Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p>Suspension blocks all transactions until further actions.</p>
                <p>Lorem ipsum.</p>
            </div>
            <div class="footer">
                <div>
                    <strong>Are you sure you want to suspend your wallet?</strong>
                    <button id="suspend_button"
                            type="button"
                            class="merkai-btn-primary close">
                        Yes
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'merkai_ajax_set_status', 'ajax-status-nonce' ); ?>

                    <button
                            class="merkai-btn-primary close"
                            type="button">No</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal reactivateModal">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Reactivate Merkai Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p>After reactivating the wallet you can continue shopping.</p>
            </div>
            <div class="footer">
                <div>
                    <strong>Are you sure you want to reactivate your wallet?</strong>
                    <button id="reactivate_button"
                            type="button"
                            class="merkai-btn-primary close">
                        Yes
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'merkai_ajax_set_status', 'ajax-status-nonce' ); ?>

                    <button
                            class="merkai-btn-primary close"
                            type="button">No</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal blockModal">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Block Merkai Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="merkai-font-bold merkai-text-red-500">Attention!</p>
            </div>
            <div class="footer">
                <div>
                    <strong>Are you sure you want to BLOCK your wallet?</strong>
                    <button id="block_button"
                            type="button"
                            class="merkai-btn-primary close">
                        Yes
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'merkai_ajax_set_status', 'ajax-status-nonce' ); ?>

                    <button
                            class="merkai-btn-primary close"
                            type="button">No</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal deleteModal">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Delete Merkai Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="merkai-font-bold merkai-text-red-500">Attention!</p>
            </div>
            <div class="footer">
                <div>
                    <strong>Are you sure you want to DELETE your wallet?</strong>
                    <button id="delete_button"
                            type="button"
                            class="merkai-btn-primary close">
                        Yes
                        <svg class="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="merkai-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'merkai_ajax_delete_wallet', 'ajax-delete-nonce' ); ?>

                    <button
                            class="merkai-btn-primary close"
                            type="button">No</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    echo do_shortcode('[merkai_registration_block]');
} ?>

<?php
// TODO
/* PLEASE do not delete it yet, I will finalize it later
$string = simplexml_load_file("https://www.artlebedev.ru/country-list/xml/");
//Обратите внимание на вложенность с помощью
// echo '<pre>';  print_r($xml);  echo '</pre>';

function xml2array ( $xmlObject, $out = array () )
{
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

    return $out;
}

$array = xml2array($string);
$array = $array['country'];

foreach($array as $item){
    $item = xml2array($item);
}

//   print_r($array);

foreach($array['country'] as $item){
   // print_r($item);
    print_r($item['english']);
    //echo '<option value="">'.$item['iso'].'</option>';
} */
?>

<?php echo do_shortcode('[merkai_modal_forms]'); ?>