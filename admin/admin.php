<?php

if(!DEFINED("WP_ABUSESHIELD_ADMIN")) die;

require_once plugin_dir_path( __FILE__ ) . "class-wp-abuseshield-admin.php";

$admin = new Wp_Abuseshield_Admin();

?>
<h1>WP AbuseShield Configuration</h1><hr>
<div class="wp-abuseshield-messages">
<?php echo $admin->DisplayMessages(); ?>
</div>

<div class="wp-abuseshield-admin">

    <table class="wp-list-table widefat">
    
    <tr><td>
    <div class="wp-abuseshield-config-group">
        <form method="post">
        <div class="wp-abuseshield-config-row">
            <label for="WP_ABUSESHIELD_ADMIN_APIKEY">AbuseIPDB API key:</label><br>
            <input type="text" name="WP_ABUSESHIELD_ADMIN_APIKEY" id="WP_ABUSESHIELD_ADMIN_APIKEY" size="40" value="<?php echo $admin->plugin->config->config["APIKey"]; ?>">
        </div>
        <div class="wp-abuseshield-config-row">
            <input type="submit" name="WP_ABUSESHIELD_ADMIN_SUBMIT" class="button button-primary" value="Save">
        </div>
        </form>
    </div>
    </td></tr>

    <tr><td>
    <div class="wp-abuseshield-config-group">
        <form method="post">
        <div class="wp-abuseshield-config-row">
            <label for="WP_ABUSESHIELD_ADMIN_SECRET">Your secret token:</label><br>
            <input type="text" id="WP_ABUSESHIELD_ADMIN_SECRET" size="40" value="<?php echo $admin->plugin->config->config["Secret"]; ?>" readonly>
        </div>
        <div class="wp-abuseshield-config-row">
            <input type="submit" name="WP_ABUSESHIELD_ADMIN_RESET_SECRET"  class="button button-primary" value="Reset Secret Token">
        </div>
        </form>
    </div>
    </td></tr>

    <tr><td>
    <div class="wp-abuseshield-config-group">
        <form method="post">
        <div class="wp-abuseshield-config-row">
            <span style="font-weight:bold">Cached IPs: </span><?php echo $admin->plugin->cache->CountCache(); ?>
        </div>
        <div class="wp-abuseshield-config-row">
            <input type="submit" name="WP_ABUSESHIELD_ADMIN_CLEAR_CACHE"  class="button button-primary" value="Clear Cache">
        </div>
        </form>
    </div>
    </td></tr>

    </td>

</div>
