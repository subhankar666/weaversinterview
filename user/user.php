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
        if (!$_REQUEST["fname"]) {
            print_r(json_encode(["success" => false, "message" => "First Name is required"]));
        } else if (!$_REQUEST["lname"]) {
            print_r(json_encode(["success" => false, "message" => "Last Name is required"]));
        } else if (!$_REQUEST["email"]) {
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
                update_user_meta($userID, "first_name", $_REQUEST['fname']);
                update_user_meta($userID, "last_name", $_REQUEST['lname']);
                $user = new WP_User($userID);
                $user->set_role("student");
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
    public function applyCourseFunction()
    {
        $userId = get_current_user_id();
        $addedCourses = get_user_meta($userId, "courses");
        if (in_array($_REQUEST['couseId'], $addedCourses)) {
            print_r(json_encode(["success" => false, "message" => "Already Added"]));
        } else {
            add_user_meta($userId, "courses", $_REQUEST['couseId']);
            print_r(json_encode(["success" => true, "message" => "Course Added Successfully"]));
        }

        wp_die();
    }

    public function courseView()
    {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $wpObj = new WP_Query(array(
            "post_type" => "courses",
            "posts_per_page" => 6,
            'paged' => $paged,
        ));
        $userId = get_current_user_id();
        $addedCourses = get_user_meta($userId, "courses");
        if ($wpObj->have_posts()) {
            $dashView = "<div class='dashboard-container row'>";
            while ($wpObj->have_posts()) {

                $wpObj->the_post();
                $courseId = get_the_id();
                $applyButton = in_array($courseId, $addedCourses) ? "APPLIED" : "APPLY";
                $dashView .= "<div class='dashboard-item column'>";
                $dashView .= "<img class='dashboard-image' src='" . get_the_post_thumbnail_url() . "'>";
                $dashView .= "<p class='dashboard-title'>" . get_the_title() . "</p>";
                $dashView .= "<p class='dashboard-content'>" . wp_trim_words(get_the_content(), 5, '...') . "</p>";
                $dashView .= "<button class='dashboard-button' data-id=" . get_the_id() . ">$applyButton</button>";
                $dashView .= "</div>";

            }
            $dashView .= "</div>";
            $dashView .= "<div class='clearfix'></div>";
            $total_pages = $wpObj->max_num_pages;
            if ($total_pages > 1) {
                $current_page = max(1, get_query_var('paged'));
                $dashView .= paginate_links(array(
                    'base' => get_pagenum_link(1) . '%_%',
                    'format' => '/page/%#%',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => __('« prev'),
                    'next_text' => __('next »'),
                ));
            }

            echo $dashView;
        }

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
add_action('wp_ajax_applyCourse', array($userObj, "applyCourseFunction"));
add_action('wp_ajax_nopriv_applyCourse', array($userObj, "applyCourseFunction"));

add_action('wp_ajax_loginAction', array($userObj, "loginActionFunction"));
add_action('wp_ajax_nopriv_loginAction', array($userObj, "loginActionFunction"));

add_action('wp_ajax_uploadFile', array($userObj, "uploadFileFunction"));
add_action('wp_ajax_nopriv_uploadFile', array($userObj, "uploadFileFunction"));
