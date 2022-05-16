<?php

class pluginClass
{
    public function wpActivateFunction()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $sql = 'CREATE TABLE IF NOT EXISTS`' . $prefix . 'banner_size` (
            `id` int(255) NOT NULL AUTO_INCREMENT,
            `banner_name` varchar(50) NOT NULL,
            `height` int(10) NOT NULL,
            `width` int(10) NOT NULL,
            PRIMARY KEY (`id`)
          )';
        dbDelta($sql);
        $this->wpCreatePost("User Dashboard", "[custom-user-dashboard]", "pluginPage");
        $this->wpCreatePost("User Login", "[custom-user-login]", "pluginLoginpage");
        $this->wpCreatePost("User Notification", "[custom-user-notification]", "pluginNotificationpage");
        $this->wpCreatePost("Logout", "[custom-user-logout]", "pluginLogoutpage");
    }
    public function wpDeactivatePage()
    {
        $this->wpDeletePost("pluginPage");
        $this->wpDeletePost("pluginLoginpage");
        $this->wpDeletePost("pluginNotificationpage");
        $this->wpDeletePost("pluginLogoutpage");
    }
    public function wpCreatePost($pageName, $shortcode, $optionKey)
    {
        $page = array(
            "post_title" => $pageName,
            "post_content" => $shortcode,
            "post_status" => "publish",
            "post_author" => 1,
            "post_type" => "page",
        );
        $postID = wp_insert_post($page);
        update_option($optionKey, $postID);

    }
    public function wpDeletePost($optionKey)
    {
        $postID = get_option($optionKey);
        wp_delete_post($postID);
    }
    public function add_css_js_function()
    {

        wp_enqueue_style("toastr-style", "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css", "", "1.0");
        wp_enqueue_style("plugin-style", plugins_url() . "/custom-user/css/style.css", "", "1.0");
        wp_enqueue_script('jquery');
        wp_enqueue_script("script-js", plugins_url() . "/custom-user/js/script.js", "", "1.0", true);
        wp_enqueue_script("plugin-ajax", plugins_url() . "/custom-user/js/ajax.js", "", "1.0", true);

        wp_enqueue_script("toaster-js", "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js", "", "1.0", true);
        wp_localize_script('plugin-ajax', 'frontend_ajax_object',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );

    }
    public function add_admin_css_js_function()
    {
        wp_enqueue_style("toastr-style", "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css", "", "1.0");
        wp_enqueue_style("datatable-style", "http://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css", "", "1.0");
        wp_enqueue_style("admin-style", plugins_url() . "/custom-user/admin/css/style.css", "", "1.0");
        wp_enqueue_script("toaster-js", "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js", "", "1.0", true);
        wp_enqueue_script("datatable-js", "http://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js", "", "1.0", true);
        wp_enqueue_script("ajax-js", plugins_url() . "/custom-user/admin/js/ajax.js", "", "1.0", true);
        wp_enqueue_script("script-js", plugins_url() . "/custom-user/admin/js/script.js", "", "1.0", true);
    }
    public function do_output_buffer()
    {
        ob_start();
    }
    public function hide_admin_bar($show)
    {
        if (current_user_can('ad-manager')):
            return false;
        endif;
        return $show;
    }

}

$pluginObj = new pluginClass();
add_action("wp_enqueue_scripts", array($pluginObj, "add_css_js_function"));
add_action("admin_enqueue_scripts", array($pluginObj, "add_admin_css_js_function"));
add_action('init', array($pluginObj, "do_output_buffer"));
add_filter('show_admin_bar', array($pluginObj, "hide_admin_bar"));
