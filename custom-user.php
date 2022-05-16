<?php
/*
Plugin Name: Custom User
Author Name: Subhankar Saha
 */
global $wpdb;
require "admin/admin.php";
require "plugin-class/plugin-class.php";
require "user/user.php";
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

//CREATING PAGES WHILE ACTIVATING
register_activation_hook(__FILE__, array($pluginObj, "wpActivateFunction"));

//DELETING PAGES WHILE DEACTIVATING
register_deactivation_hook(__FILE__, array($pluginObj, "wpDeactivatePage"));
