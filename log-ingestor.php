<?php
/*
Plugin Name: WP Logs to Parseable
Description: Ingests WordPress logs and pushes them to Parseable.
Version: 1.0
Author: Shantanu
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include the admin settings.
require_once(plugin_dir_path(__FILE__) . 'includes/admin-settings.php');
require_once(plugin_dir_path(__FILE__) . 'includes/logging-functions.php');

// Initialize the plugin.
add_action('plugins_loaded', 'wp_logs_to_parseable_init');

function wp_logs_to_parseable_init() {
    // Schedule the log pushing to Parseable.
    if (!wp_next_scheduled('wp_logs_to_parseable_push_logs')) {
        wp_schedule_event(time(), 'hourly', 'wp_logs_to_parseable_push_logs');
    }
}

add_action('wp_logs_to_parseable_push_logs', 'wp_logs_to_parseable_push_logs_to_parseable');
