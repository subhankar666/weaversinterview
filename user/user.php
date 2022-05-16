<?php

include ABSPATH . "wp-content/plugins/custom-user/smtp/PHPMailerAutoload.php";
class UserCLass
{

    public function dashboardShortcode()
    {
        require plugin_dir_path(__FILE__) . 'user-dashboard.php';
    }
    public function loginShortcode()
    {
        require plugin_dir_path(__FILE__) . 'user-login.php';
    }

    public function NotificationShortcode()
    {
        require plugin_dir_path(__FILE__) . 'user-notification.php';
    }
    public function LogoutShortcode()
    {
        wp_logout();
        wp_safe_redirect(site_url('/'));
        $current_user = wp_get_current_user();
        if (in_array("ad-manager", $current_user->roles)) {
            wp_safe_redirect($redirect_url);
            exit;
        }
    }
    public function sessionCheck()
    {
        global $post;
        $postID = $post->ID;
        if (is_user_logged_in() && ($postID == get_option("pluginLoginPage") || $title = $postID == get_option("pluginRegisterPage"))) {
            wp_redirect(get_the_permalink(get_option("pluginPage")));
        } elseif (!is_user_logged_in() && ($postID == get_option("pluginPage") || $postID == get_option("pluginLogoutpage"))) {
            wp_redirect(get_the_permalink(get_option("pluginLoginPage")));
        }

    }
    public function smtp_mailer($to, $subject, $msg)
    {
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Username = "subhankar09111989@gmail.com";
        $mail->Password = "Subhankar8055_";
        $mail->SetFrom("subhankar09111989@gmail.com");
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AddAddress($to);
        $mail->SMTPOptions = array('ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false,
        ));
        $mail->send();
        // if (!$mail->Send()) {
        //     return $mail->ErrorInfo;
        // } else {
        //     return 'Sent';
        // }

    }
    public function registerActionFunction()
    {
        if (!$_REQUEST["email"]) {
            print_r(json_encode(["success" => false, "message" => "Email is required"]));
        } elseif (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
            print_r(json_encode(["success" => false, "message" => "Invalid Email Format"]));
        } elseif (!$_REQUEST["psw"]) {
            print_r(json_encode(["success" => false, "message" => "Password is required"]));
        } elseif (strlen($_REQUEST["psw"]) < 8) {
            print_r(json_encode(["success" => false, "message" => "Password must be atleast 8 characters"]));
        } elseif (!$_REQUEST["psw-repeat"]) {
            print_r(json_encode(["success" => false, "message" => "Confirm password is required"]));
        } elseif ($_REQUEST["psw"] != $_REQUEST["psw-repeat"]) {
            print_r(json_encode(["success" => false, "message" => "Password and confirm password must be same"]));
        } else {
            if (!username_exists($_REQUEST["email"])) {
                $userID = wp_create_user($_REQUEST["email"], $_REQUEST["psw-repeat"], $_REQUEST["email"]);
                $user = new WP_User($userID);
                $user->set_role("ad-manager");
                $generatedKey = sha1(mt_rand(10000, 99999) . time() . $_REQUEST["email"]);
                $pageUrl = get_the_permalink(get_option("pluginNotificationPage"));
                $html = "<p>Please activate the account by click on this link <a href='$pageUrl?activation_key=$generatedKey'>$pageUrl?activation_key=$generatedKey</a></p>";
                add_user_meta($userID, "activation_token", $generatedKey);
                $this->smtp_mailer($_REQUEST["email"], 'Activation Link', $html);
                print_r(json_encode(["success" => true, "message" => "Registered successfully"]));
            } else {
                print_r(json_encode(["success" => false, "message" => "Username already exists"]));
            }

        }
        wp_die();
    }
    public function loginActionFunction()
    {
        $login_data = array();
        $login_data['user_login'] = $_REQUEST["email"];
        $login_data['user_password'] = $_REQUEST["psw"];
        $login_data['remember'] = true;
        $user_verify = wp_signon($login_data, true);
        if (is_wp_error($user_verify)) {
            print_r(json_encode(["success" => false, "message" => "Invalid Username and password"]));
        } else {
            print_r(json_encode(["success" => true, "message" => "Login successfully"]));
        }

        wp_die();
    }
    public function uploadFileFunction()
    {
        $fileName = $_REQUEST['filename'];
        $data = $_REQUEST['base64data'];
        $userID = $_REQUEST['userID'];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $upload = wp_upload_bits($fileName, null, $data);
        if (!$upload['error']) {
            add_user_meta($userID, "bannerImageUrl", $upload['url']);
            print_r(json_encode(["success" => true, "message" => "Uploaded successfully"]));
        } else {
            print_r(json_encode(["success" => false, "message" => "Upload error"]));
        }

        wp_die();
    }
    public function imageShow()
    {
        $imageDivOpen = '<div class="banner-container" ><div class="gallery"id="showBanner">';
        $imageReturn = "";

        $imageUrls = get_user_meta(get_current_user_id(), "bannerImageUrl");

        foreach ($imageUrls as $imageUrl) {
            $imageReturn .= '<div class="gallery-item">';
            $imageReturn .= '<img class="gallery-image" src="' . $imageUrl . '">';
            $imageReturn .= '</div>';

        }
        $imageDivClose = '</div></div>';
        echo $imageDivOpen . $imageReturn . $imageDivClose;
    }

    public function selectboxDashboardShow()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . "banner_size";
        $sql = "select * from $tableName";
        $results = $wpdb->get_results($sql);
        if ($results):
            $selectView = '<select name="bannerSize" id="bannerSize" style="width:43%">';
            foreach ($results as $result):
                $bnrName = $result->banner_name;
                $bnrWidth = $result->width;
                $bnrHeight = $result->height;
                $selectView .= '<option value="' . $result->width . 'x' . $result->height . '">' . $result->banner_name . ' (' . $result->width . 'x' . $result->height . ') px</option>';

            endforeach;
            $selectView .= '</select>';
            return $selectView;
        endif;

    }

}

$userObj = new UserClass();
add_shortcode('custom-user-dashboard', array($userObj, "dashboardShortcode"));
add_shortcode('custom-user-login', array($userObj, "loginShortcode"));
add_shortcode('custom-user-notification', array($userObj, "NotificationShortcode"));
add_shortcode('custom-user-logout', array($userObj, "LogoutShortcode"));
add_action('wp_head', array($userObj, "sessionCheck"));
add_action('wp_ajax_registerAction', array($userObj, "registerActionFunction"));
add_action('wp_ajax_nopriv_registerAction', array($userObj, "registerActionFunction"));
add_action('wp_ajax_loginAction', array($userObj, "loginActionFunction"));
add_action('wp_ajax_nopriv_loginAction', array($userObj, "loginActionFunction"));

add_action('wp_ajax_uploadFile', array($userObj, "uploadFileFunction"));
add_action('wp_ajax_nopriv_uploadFile', array($userObj, "uploadFileFunction"));
