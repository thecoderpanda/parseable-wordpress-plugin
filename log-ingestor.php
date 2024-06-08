<?php
/*
Plugin Name: Log Ingestor
Description: Ingests system logs, PHP logs, theme logs, plugin logs, and sends them to a Parseable instance.
Version: 1.0
Author: Shantanu Vishwanadha
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include necessary files
include_once plugin_dir_path(__FILE__) . 'includes/class-log-ingestor.php';
include_once plugin_dir_path(__FILE__) . 'includes/class-settings.php';

// Initialize the plugin
function log_ingestor_init() {
    $log_ingestor = new Log_Ingestor();
    $log_ingestor->init();
}
add_action('plugins_loaded', 'log_ingestor_init');

// Initialize settings
function log_ingestor_settings_init() {
    $settings = new Log_Ingestor_Settings();
    $settings->init();
}
add_action('admin_init', 'log_ingestor_settings_init');
