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

<section class="merkai <?php echo $merkai_classes; ?>">
    <div id="merkai_auth_block" class="merkai-max-w-4xl merkai-mx-auto merkai-mb-4">
        <div class="merkai-embleme">
            <img src="<?php echo $embleme_link; ?>" />
        </div>
        <div id="login-signup-forms">
            <div class="merkai_form merkai_login_form visible">
                <h2 class="merkai_form_title">Please, login before contunue!</h2>
                <p class="!merkai-mb-8 merkai-text-center">Not registered? <a class="form-toggle-a">Sign up!</a></p>
                <form name="loginform" id="merkai_loginform" action="<?php bloginfo('url') ?>/wp-login.php" method="post">
                    <div class="row">
                        <div class="col">
                            <label for="log">Login</label>
                            <input type="text" name="log" class="merkai_input" id="merkai_user_login" />
                        </div>
                        <div class="col">
                            <label for="pwd">Password</label>
                            <input type="password" name="pwd" class="merkai_input" id="merkai_user_pass" />
                        </div>
                    </div>
                    <p>
                        <label><input name="rememberme" type="checkbox" id="merkai_rememberme" value="forever" /> Remember me</label>
                    </p>
                    <p>
                        <input type="submit" name="wp-submit" id="merkai_wp-submit" class="merkai_button merkai_colored" value="Log in" />
                        <input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?><?php echo $attr['login_redirect'] ?? ''; ?>" />
                        <input type="hidden" name="cfps_cookie" value="1" />
                    </p>
                </form>
            </div>

            <div class="merkai_form merkai_register_form">
                <h2 class="merkai-text-center">Join "<?php bloginfo('title') ?>" for your convenience!</h2>
                <p class="merkai-text-center !merkai-mb-8">Already registered? <a class="form-toggle-a">Log in!</a></p>
                <form name="registerform" id="registerform">
                    <div class="row">
                        <div class="col">
                            <label for="user_login" class="for_input">Username</label>
                            <input type="text" name="user_login" id="user_login" class="merkai_input" value="" autocapitalize="off" autocomplete="username" required>
                        </div>
                        <div class="col">
                            <label for="user_email" class="for_input">Email</label>
                            <input type="email" name="user_email" id="user_email" class="merkai_input" value="" autocomplete="email" required>
                        </div>
                    </div>
                    <p id="reg_passmail">
                        Registration confirmation will be emailed to you.
                    </p>
                    <input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?><?php echo $attr['register_redirect'] ?? ''; ?>">
                    <p class="submit">
                        <button id="wp-submit-registration" class="merkai_button merkai-w-full merkai-cursor-pointer merkai_colored">
                            Register
                        </button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
<?php