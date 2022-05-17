<?php
if ($_GET) {
    $activation_key = $_GET["activation_key"];
    global $wpdb;
    $results = $wpdb->get_results("select user_id from wp_usermeta where meta_value='$activation_key'");

    $userID = $results[0]->user_id;
    $pageID = get_option("pluginPage");
    $pageUrl = get_the_permalink($pageID);
    $activationValue = get_user_meta($userID, "activation_key", true);

    if (!empty($results) && $activationValue != "Expired") {

        update_user_meta($userID, "activation_token", "Expired");
        wp_clear_auth_cookie();
        wp_set_current_user($userID);
        wp_set_auth_cookie($userID);
        $redirect_to = user_admin_url();
        wp_safe_redirect($pageUrl);
        echo $pageUrl;
    } else {
        wp_safe_redirect(site_url('/'));
    }

}

?>
<div class="container-register dashboard-container" id="container-register">
<form class="custom-plugin-form" id="dashboardForm">
  <div class="container">
    <h1>We have sent you a confirmation email please confirm to continue</h1>


  </div>
</form>
</div>
