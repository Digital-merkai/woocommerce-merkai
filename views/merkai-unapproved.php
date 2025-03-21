<?php
if (!defined('ABSPATH')) {
    die;
}


$current_user = wp_get_current_user();
if (in_array('administrator', $current_user->roles)) {
?>
    <section class="merkai">
        Please <a href="/wp-admin/admin.php?page=wc-settings&tab=checkout&section=merkai">approve</a> the Merkai Payment Gateway
    </section>
<?php
}