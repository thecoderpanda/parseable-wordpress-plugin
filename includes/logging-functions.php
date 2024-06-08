<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function wp_logs_to_parseable_push_logs_to_parseable() {
    $options = get_option('wp_logs_to_parseable_options');
    $parseable_url = $options['wp_logs_to_parseable_url'];
    $username = $options['wp_logs_to_parseable_username'];
    $password = $options['wp_logs_to_parseable_password'];
    $streams = isset($options['wp_logs_to_parseable_streams']) ? $options['wp_logs_to_parseable_streams'] : array();
    $auth = $options['wp_logs_to_parseable_auth'];

    if (empty($streams)) {
        return;
    }

    foreach ($streams as $stream) {
        $logs = wp_logs_to_parseable_retrieve_logs($stream);
        $ch = curl_init($parseable_url . '/api/v1/logstream/' . $stream);

        $payload = json_encode($logs);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: ' . $auth
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('Error pushing logs to Parseable: ' . curl_error($ch));
        } else {
            error_log('Logs pushed to Parseable: ' . $response);
        }

        curl_close($ch);
    }
}

function wp_logs_to_parseable_retrieve_logs($stream) {
    // Customize this function to retrieve specific logs for the given stream.
    $logs = array();

    // Retrieve system logs
    if ($stream == 'system_logs') {
        $system_logs = file_get_contents('/path/to/system/logs');
        $logs['system_logs'] = $system_logs;
    }

    // Retrieve PHP logs
    if ($stream == 'php_logs') {
        $php_logs = file_get_contents(ini_get('error_log'));
        $logs['php_logs'] = $php_logs;
    }

    // Retrieve theme logs
    // Add logic to retrieve theme logs here

    // Retrieve plugin logs
    // Add logic to retrieve plugin logs here

    // Retrieve WordPress logs
    // Add logic to retrieve WordPress logs here

    return $logs;
}
?>
