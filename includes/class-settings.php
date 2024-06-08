<?php

class Log_Ingestor_Settings {
    public function init() {
        add_settings_section('log_ingestor_section', 'Log Ingestor Settings', null, 'log-ingestor');
        
        add_settings_field('parseable_url', 'Parseable URL', array($this, 'parseable_url_callback'), 'log-ingestor', 'log_ingestor_section');
        register_setting('log_ingestor_settings', 'parseable_url');

        add_settings_field('username', 'Username', array($this, 'username_callback'), 'log-ingestor', 'log_ingestor_section');
        register_setting('log_ingestor_settings', 'username');

        add_settings_field('password', 'Password', array($this, 'password_callback'), 'log-ingestor', 'log_ingestor_section');
        register_setting('log_ingestor_settings', 'password');

        add_settings_field('logstream', 'Log Stream', array($this, 'logstream_callback'), 'log-ingestor', 'log_ingestor_section');
        register_setting('log_ingestor_settings', 'logstream');

        add_settings_field('auth_credentials', 'Authorization Credentials', array($this, 'auth_credentials_callback'), 'log-ingestor', 'log_ingestor_section');
        register_setting('log_ingestor_settings', 'auth_credentials');
    }

    public function parseable_url_callback() {
        printf('<input type="text" id="parseable_url" name="parseable_url" value="%s" />', esc_attr(get_option('parseable_url')));
    }

    public function username_callback() {
        printf('<input type="text" id="username" name="username" value="%s" />', esc_attr(get_option('username')));
    }

    public function password_callback() {
        printf('<input type="password" id="password" name="password" value="%s" />', esc_attr(get_option('password')));
    }

    public function logstream_callback() {
        printf('<input type="text" id="logstream" name="logstream" value="%s" />', esc_attr(get_option('logstream')));
    }

    public function auth_credentials_callback() {
        printf('<input type="text" id="auth_credentials" name="auth_credentials" value="%s" />', esc_attr(get_option('auth_credentials')));
    }
}
?>
