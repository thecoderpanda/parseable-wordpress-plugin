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
    register_setting('wp_logs_to_parseable_settings', 'wp_logs_to_parseable_options', 'wp_logs_to_parseable_options_validate');

    add_settings_section(
        'wp_logs_to_parseable_section_login',
        __('Parseable Login', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_settings_section_login_callback',
        'wp_logs_to_parseable_settings'
    );

    add_settings_field(
        'wp_logs_to_parseable_url',
        __('Parseable URL', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_url_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section_login'
    );

    add_settings_field(
        'wp_logs_to_parseable_username',
        __('Username', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_username_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section_login'
    );

    add_settings_field(
        'wp_logs_to_parseable_password',
        __('Password', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_password_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section_login'
    );

    add_settings_section(
        'wp_logs_to_parseable_section_streams',
        __('Select Log Streams', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_settings_section_streams_callback',
        'wp_logs_to_parseable_settings'
    );

    add_settings_field(
        'wp_logs_to_parseable_streams',
        __('Log Streams', 'wp-logs-to-parseable'),
        'wp_logs_to_parseable_streams_render',
        'wp_logs_to_parseable_settings',
        'wp_logs_to_parseable_section_streams'
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

function wp_logs_to_parseable_streams_render() {
    $options = get_option('wp_logs_to_parseable_options');
    $streams = isset($options['wp_logs_to_parseable_streams']) ? $options['wp_logs_to_parseable_streams'] : array();
    $available_streams = wp_logs_to_parseable_get_available_streams();
    if ($available_streams) {
        foreach ($available_streams as $stream) {
            ?>
            <label>
                <input type="checkbox" name="wp_logs_to_parseable_options[wp_logs_to_parseable_streams][]" value="<?php echo esc_attr($stream); ?>" <?php checked(in_array($stream, $streams)); ?>>
                <?php echo esc_html($stream); ?>
            </label><br>
            <?php
        }
    } else {
        echo '<p>' . __('Please login to view available log streams.', 'wp-logs-to-parseable') . '</p>';
    }
}

function wp_logs_to_parseable_settings_section_login_callback() {
    echo __('Enter your Parseable credentials to fetch available log streams.', 'wp-logs-to-parseable');
}

function wp_logs_to_parseable_settings_section_streams_callback() {
    echo __('Select the log streams you want to push to Parseable.', 'wp-logs-to-parseable');
}

function wp_logs_to_parseable_options_validate($input) {
    // Validate and sanitize input fields here.
    return $input;
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

function wp_logs_to_parseable_get_available_streams() {
    $options = get_option('wp_logs_to_parseable_options');
    $url = isset($options['wp_logs_to_parseable_url']) ? $options['wp_logs_to_parseable_url'] . '/api/v1/logstream' : '';
    $username = isset($options['wp_logs_to_parseable_username']) ? $options['wp_logs_to_parseable_username'] : '';
    $password = isset($options['wp_logs_to_parseable_password']) ? $options['wp_logs_to_parseable_password'] : '';

    if (empty($url) || empty($username) || empty($password)) {
        return false;
    }

    $response = wp_remote_get($url, array(
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode($username . ':' . $password)
        )
    ));

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['streams'])) {
        return $data['streams'];
    }

    return false;
}
?>
