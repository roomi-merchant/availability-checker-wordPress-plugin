<?php
// Prevent direct access
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Table name
$table_name = $wpdb->prefix . 'schedule_check';

// Drop custom table
$wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS {$table_name}"));

// Delete options
delete_option('schedule_check_settings');

// Delete multisite option if applicable
if (is_multisite()) {
    delete_site_option('schedule_check_settings');
}
