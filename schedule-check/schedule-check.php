<?php
/*
Plugin Name: Schedule Checker
Description: This plugin adds schedule info to a custom database table.
Author: Roomi Merchant
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Version: 1.0.0
*/

namespace ScheduleCheck;

if (!defined('ABSPATH')) {
    exit;
}

function activate_schedule_check()
{
    global $wpdb, $table_prefix;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $table_name = $table_prefix . 'schedule_check';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
        ID INT NOT NULL AUTO_INCREMENT,
        Name VARCHAR(255) NOT NULL,
        CNIC BIGINT NULL, 
        `Start Time` VARCHAR(255) NOT NULL,
        `End Time` VARCHAR(255) NOT NULL,
        PRIMARY KEY (ID)
    ) {$charset_collate};";

    dbDelta($sql);

    $data = [
        'Name' => 'Roomi',
        'CNIC' => null,
        'Start Time' => '10:00 AM',
        'End Time' => '02:00 PM',
    ];

    $wpdb->insert($table_name, $data);
}
register_activation_hook(__FILE__, __NAMESPACE__ . '\\activate_schedule_check');


function deactivate_schedule_check()
{
    global $wpdb, $table_prefix;

    $table_name = $table_prefix . 'schedule_check';
    $wpdb->query("TRUNCATE TABLE {$table_name}");
}
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\deactivate_schedule_check');


function schedule_check_menu()
{
    add_menu_page(
        'Schedule Check',
        'Schedule Check',
        'manage_options',
        'schedule-check',
        __NAMESPACE__ . '\\scheduleChecker', // ✅ FIXED: namespaced callback
        'dashicons-clock',
        15
    );

    add_submenu_page(
        'schedule-check',
        'User Schedule',
        'User Schedule',
        'manage_options',
        'schedule-check',
        __NAMESPACE__ . '\\scheduleChecker' // ✅ FIXED: namespaced callback
    );
    add_submenu_page(
        'schedule-check',
        'Add User Schedule',
        'Add User Schedule',
        'manage_options',
        'add-user-schedule',
        __NAMESPACE__ . '\\addUserSchedule' // ✅ FIXED: namespaced callback
    );
}
add_action('admin_menu', __NAMESPACE__ . '\\schedule_check_menu');


function scheduleChecker()
{
    include plugin_dir_path(__FILE__) . 'admin/schedule-check-settings.php';
}

function addUserSchedule()
{
    include plugin_dir_path(__FILE__) . 'admin/add-user-schedule.php';
}


add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\add_sc_styles');

function add_sc_styles()
{
    wp_enqueue_style('sc-custom-style', plugin_dir_url(__FILE__) . 'assets/css/sc-admin.css', array(), '1.0.0', 'all');
    wp_enqueue_script('sc-custom-script', plugin_dir_url(__FILE__) . 'assets/js/sc-admin-script.js', array('jquery'), '1.0.0', 'all');
}
