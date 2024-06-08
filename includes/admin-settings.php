<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('admin_menu', 'wp_logs_to_parseable_admin_menu');
add_action('admin_init', 'wp_logs_to_parseable_settings_init');

function wp_logs_to_parseable_admin_menu() {
    add_options_page(
        'WP Logs to Parseable Settings',
        'WP Logs to Parseable',
        'manage_options',
        'wp-logs-to-parseable',
        'wp_logs_to_parseable_options_page'
    );
}

function wp_logs_to_parseable_settings_init() {
    register_setting('wp_logs_to_parseable_settings', 'wp_logs_to_parseable_options');

    add_settings_section(
        'wp_logs_to_parseable_section',
        __('Parseable Settings', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_settings_section_callback',
        'wp_logs_to_parseable_settings'
    );

    add_settings_field(
        'wp_logs_to_parseable_url',
        __('Parseable URL', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_url_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section'
    );

    add_settings_field(
        'wp_logs_to_parseable_username',
        __('Username', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_username_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section'
    );

    add_settings_field(
        'wp_logs_to_parseable_password',
        __('Password', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_password_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section'
    );

    add_settings_field(
        'wp_logs_to_parseable_stream',
        __('Stream Name', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_stream_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section'
    );

    add_settings_field(
        'wp_logs_to_parseable_auth',
        __('Authorization Credentials', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_auth_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section'
    );
}

function wp_logs_to_parseable_url_render() {
    $options = get_option('wp_logs_to_parseable_options');
    ?>
    <input type="text" name="wp_logs_to_parseable_options[wp_logs_to_parseable_url]" value="<?php echo isset($options['wp_logs_to_parseable_url']) ? esc_attr($options['wp_logs_to_parseable_url']) : ''; ?>">
    <?php
}

function wp_logs_to_parseable_username_render() {
    $options = get_option('wp_logs_to_parseable_options');
    ?>
    <input type="text" name="wp_logs_to_parseable_options[wp_logs_to_parseable_username]" value="<?php echo isset($options['wp_logs_to_parseable_username']) ? esc_attr($options['wp_logs_to_parseable_username']) : ''; ?>">
    <?php
}

function wp_logs_to_parseable_password_render() {
    $options = get_option('wp_logs_to_parseable_options');
    ?>
    <input type="password" name="wp_logs_to_parseable_options[wp_logs_to_parseable_password]" value="<?php echo isset($options['wp_logs_to_parseable_password']) ? esc_attr($options['wp_logs_to_parseable_password']) : ''; ?>">
    <?php
}

function wp_logs_to_parseable_stream_render() {
    $options = get_option('wp_logs_to_parseable_options');
    ?>
    <input type="text" name="wp_logs_to_parseable_options[wp_logs_to_parseable_stream]" value="<?php echo isset($options['wp_logs_to_parseable_stream']) ? esc_attr($options['wp_logs_to_parseable_stream']) : ''; ?>">
    <?php
}

function wp_logs_to_parseable_auth_render() {
    $options = get_option('wp_logs_to_parseable_options');
    ?>
    <input type="text" name="wp_logs_to_parseable_options[wp_logs_to_parseable_auth]" value="<?php echo isset($options['wp_logs_to_parseable_auth']) ? esc_attr($options['wp_logs_to_parseable_auth']) : ''; ?>">
    <?php
}

function wp_logs_to_parseable_settings_section_callback() {
    echo __('Configure the settings to push logs to Parseable.', 'wp-logs-to-parseable');
}

function wp_logs_to_parseable_options_page() {
    ?>
    <form action='options.php' method='post'>
        <h2>WP Logs to Parseable</h2>
        <?php
        settings_fields('wp_logs_to_parseable_settings');
        do_settings_sections('wp_logs_to_parseable_settings');
        submit_button();
        ?>
    </form>
    <?php
}
?>
