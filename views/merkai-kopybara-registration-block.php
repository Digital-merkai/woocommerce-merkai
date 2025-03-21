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

    <section class="merkai <?php echo $merkai_classes; ?>">
        <div id="merkai_auth_block" class="merkai-mx-auto merkai-p-4 merkai-mb-4 merkai-flex merkai-flex-col merkai-flex-gap-8 merkai-items-center merkai-justify-between">

            <div class="merkai-flex merkai-flex-col merkai-gap-8 merkai-items-top merkai-p-8 merkai-w-full">
                <h2 class="!merkai-text-3xl">How to earn bonuses</h2>
                <div class="merkai-grid merkai-grid-cols-[50px_1fr] merkai-gap-6 merkai-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_1.svg' ?>" alt="" />
                    </div>
                    <div>
                        <div class="merkai-font-bold merkai-text-lg !merkai-mb-0 !merkai-text-left">Be a member</div>
                        <p class="merkai-text-base">Login or register</p>
                    </div>
                </div>
                <div class="merkai-grid merkai-grid-cols-[50px_1fr] merkai-gap-6 merkai-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_2.svg' ?>" alt="" />
                    </div>
                    <div>
                        <div class="merkai-font-bold merkai-text-lg  !merkai-mb-0 !merkai-text-left">Activate your wallet</div>
                        <p class="merkai-text-base">to get the rewards</p>
                    </div>
                </div>

                <div class="merkai-grid merkai-grid-cols-[50px_1fr] merkai-gap-6 merkai-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_3.svg' ?>" alt="" />
                    </div>
                    <div>
                        <div class="merkai-font-bold merkai-text-lg !merkai-mb-0 !merkai-text-left">Earn bonuses</h3>
                            <p class="merkai-text-base">for purchases and topups</p>
                        </div>
                    </div>
                </div>

                <div class="merkai-grid merkai-grid-cols-[50px_1fr] merkai-gap-6 merkai-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_MERKAI_BASENAME ) . 'assets/img/icon_4.svg' ?>" alt="" />
                    </div>
                    <div>
                        <div class="merkai-font-bold merkai-text-lg  !merkai-mb-0 !merkai-text-left">Buy profitable</div>
                        <p class="merkai-text-base">spending bonuses for purchases</p>
                    </div>
                </div>
            </div>

            <div id="login-signup-forms" class="merkai-w-full merkai-bg-gray-100 merkai-rounded-lg">
                <div class="merkai_form merkai_login_form visible">
                    <h2 class="merkai_form_title">Log In</h2>
                    <p class="merkai-mb-8 merkai-text-center merkai-text-balance">Enter your email address and password to log in.</p>
                    <form name="loginform" id="merkai_loginform"  method="post">
                        <div class="row">
                            <div class="col">
                                <label for="log">Login</label>
                                <input type="text" name="log" class="merkai_input border" id="user_name" />
                            </div>
                            <div class="col">
                                <label for="pwd">Password</label>
                                <div class="merkai_pwd">
                                    <input type="password" name="pwd" class="merkai_input" id="user_pass"  value="" autocomplete="current-password" spellcheck="false" required="required" />
                                    <button type="button" class="" id="show_password" data-toggle="0" aria-label="Show password" title="Show password">
                                        <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p>
                            <label><input name="rememberme" type="checkbox" id="merkai_rememberme" value="forever" /> Remember me</label>
                        </p>
                        <p>
                            <input type="submit" name="wp-submit" id="merkai_wp-submit" class="merkai_button merkai-w-full merkai_colored" value="Log in" />
                            <input type="hidden" name="cfps_cookie" value="1" />
                            <?php wp_nonce_field( 'merkai-ajax-login-nonce', 'loginsecurity' ); ?>
                        </p>
                    </form>
                    <div id="login_messages" style="display: none;"></div>
                    <p class="merkai-mt-8 merkai-text-center">Forgot your password? <a class="merkai-underline" href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>lost-password/">Recover</a></p>
                    <p class="merkai-mt-4 merkai-text-center">First time here? <a class="form-toggle-a">Sign Up</a></p>
                </div>

                <div class="merkai_form merkai_register_form">
                    <h2 class="merkai-text-center">Sign Up</h2>
                    <p class="merkai-mb-8 merkai-text-center">Enter your username and <br>email address to sign up.</p>
                    <form name="registerform" id="registerform" novalidate="novalidate">
                        <div class="row">
                            <div class="col">
                                <label for="user_login" class="for_input">Username</label>
                                <input type="text" name="user_login" id="user_login" class="merkai_input" value="" autocapitalize="off" autocomplete="username" required="required">
                            </div>
                            <div class="col">
                                <label for="user_email" class="for_input">Email</label>
                                <input type="email" name="user_email" id="user_email" class="merkai_input" value="" autocomplete="email" required="required">
                            </div>
                        </div>
                        <p id="reg_passmail" class="!merkai-text-sm !merkai-text-gray-400 merkai-text-center">
                            Registration confirmation will be emailed to you. By clicking Sign Up, You agree to the <a href="/privacy-policy" class="merkai-text-blue-500">privacy policy and the processing of personal data</a>.
                        </p>
                        <input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?><?php echo $attr['register_redirect'] ?? ''; ?>">
                        <?php wp_nonce_field( 'ajax-registration-nonce', 'ajax-registration-nonce' ); ?>
                        <p class="submit">
                            <button type="button" id="wp-submit-registration" class="merkai_button merkai-w-full merkai-cursor-pointer merkai_colored">
                                Sign Up
                            </button>
                        </p>
                    </form>
                    <div id="register_messages" style="display: none;"></div>
                    <p class="merkai-text-center !merkai-mt-8">Already have an account? <a class="form-toggle-a">Log in</a></p>
                </div>
            </div>
        </div>
    </section>
<?php