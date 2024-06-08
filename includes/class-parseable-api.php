<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Parseable_API {
    private $url;
    private $username;
    private $password;

    public function __construct($url, $username, $password) {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
    }

    public function fetch_log_streams() {
        $response = wp_remote_get($this->url . '/api/v1/logstream', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)
            )
        ));

        if (is_wp_error($response)) {
            return array();
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data) && is_array($data)) {
            return $data;
        }

        return array();
    }
}
?>
