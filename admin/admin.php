<?php
class AdminUser
{
    public function adminMenuFunction()
    {
        add_menu_page("Students", "Students", "manage_options", "students", array($this, "studentsFunction"), "", 8);
    }
    public function studentsFunction()
    {
        include "students-page.php";
    }
    public function loadViewStudentsList()
    {
        $users = get_users(array(
            "role" => "student",
        ));
        $trTable = "";
        foreach ($users as $user) {
            $userId = $user->ID;
            $userEmail = $user->user_email;
            $coursesArr = get_user_meta($userId, "courses");
            $coursesTitle = array();
            foreach ($coursesArr as $cr) {
                $coursesTitle[] = get_the_title($cr);
            }
            $courses = implode(",", $coursesTitle);
            $trTable .= "<tr>";
            $trTable .= "<td>$userId</td>";
            $trTable .= "<td>$userEmail</td>";
            $trTable .= "<td>$courses</td>";
        }
        echo $trTable;
    }
    public function addNewRoleFunction()
    {
        add_role("student", "Student", array());
    }

}
$adminObj = new AdminUser();

add_action("admin_menu", array($adminObj, "adminMenuFunction"));
add_action("init", array($adminObj, "addNewRoleFunction"));
