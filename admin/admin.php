<?php
class AdminUser
{
    public function adminMenuFunction()
    {
        add_menu_page("Ad Manager", "Ad Manager", "manage_options", "ad-manager", array($this, "adManagerFunction"), "", 8);
    }
    public function adManagerFunction()
    {
        include "ad-manager-page.php";
    }
    public function addRecords($tableName, $addArray)
    {
        global $wpdb;
        $wpdb->insert($tableName, $addArray);
    }
    public function updateRecords($tableName, $updateArray, $triggerArray)
    {
        global $wpdb;
        $wpdb->update($tableName, $addArray, $triggerArray);
    }
    public function deleteRecords($tableName, $array)
    {
        global $wpdb;
        $wpdb->delete($tableName, $array);
    }
    public function fetchData($sql)
    {
        global $wpdb;
        $results = $wpdb->get_results($sql);
        return $results;
    }
    public function addNewRoleFunction()
    {
        add_role("ad-manager", "Ad Manager", array());
    }
    public function addBannerActionFunction()
    {

        global $wpdb;
        $tableName = $wpdb->prefix . "banner_size";
        if (!$_REQUEST['bannerName']) {
            print_r(json_encode(["success" => false, "message" => "Banner Name Required"]));
        } elseif (!$_REQUEST['bannerWidth']) {
            print_r(json_encode(["success" => false, "message" => "Banner Width Required"]));
        } elseif (!$_REQUEST['bannerHeight']) {
            print_r(json_encode(["success" => false, "message" => "Banner Height Required"]));
        } else {
            if ($_REQUEST["actionType"] == "add") {
                $param = array("banner_name" => $_REQUEST['bannerName'], "height" => $_REQUEST['bannerHeight'], "width" => $_REQUEST['bannerWidth']);
                $this->addRecords($tableName, $param);
                print_r(json_encode(["success" => true, "message" => "Added Successfully"]));
            } elseif ($_REQUEST["actionType"] == "update") {
                $param = array("banner_name" => $_REQUEST['bannerName'], "height" => $_REQUEST['bannerHeight'], "width" => $_REQUEST['bannerWidth']);
                $triggerParam = array("id" => $_REQUEST["bannerId"]);
                $wpdb->update($tableName, $param, $triggerParam);

                print_r(json_encode(["success" => true, "message" => "Updated Successfully"]));
            }

        }
        wp_die();
    }
    public function removeBannerActionFunction()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . "banner_size";
        $param = array("id" => $_REQUEST["id"]);
        $this->deleteRecords($tableName, $param);
        print_r(json_encode(["success" => true, "message" => "Removed Successfully"]));
        wp_die();
    }

    public function loadViewBannerList()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . "banner_size";
        $sql = "select * from $tableName";
        $results = $this->fetchData($sql);
        if ($results):
            $trView = "";
            foreach ($results as $result):
                $trView .= "<tr>";
                $trView .= "<td>$result->banner_name</td>";
                $trView .= "<td>$result->height</td>";
                $trView .= "<td>$result->width</td>";
                $trView .= "<td><a  class='updateBanner' href='javascript:void(0)' data-id=$result->id>Update</a></td>";
                $trView .= "<td><a class='deleteBanner' href='javascript:void(0)' data-id=$result->id>Delete</a></td>";
                $trView .= "</tr>";
            endforeach;
            return $trView;
        endif;
    }

}
$adminObj = new AdminUser();

add_action("admin_menu", array($adminObj, "adminMenuFunction"));
add_action("init", array($adminObj, "addNewRoleFunction"));
add_action("wp_ajax_addBannerAction", array($adminObj, "addBannerActionFunction"));
add_action("wp_ajax_removeBannerAction", array($adminObj, "removeBannerActionFunction"));
