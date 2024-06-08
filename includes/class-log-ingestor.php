<?php

include_once plugin_dir_path(__FILE__) . 'class-buffer-ingester.php';
include_once plugin_dir_path(__FILE__) . 'class-parseable-client.php';

class Log_Ingestor {
    private $bufferIngester;
    private $parseableClient;

    public function init() {
        $this->setup_parseable_client();
        $this->setup_buffer_ingester();
        add_action('wp_footer', array($this, 'ingest_logs'));
    }

    private function setup_parseable_client() {
        $url = get_option('parseable_url');
        $username = get_option('username');
        $password = get_option('password');
        $logstream = get_option('logstream');
        $tags = array(); // Add any required tags here

        $this->parseableClient = new ParseableClient([
            'url' => $url,
            'logstream' => $logstream,
            'username' => $username,
            'password' => $password,
            'tags' => $tags,
        ]);
    }

    private function setup_buffer_ingester() {
        $this->bufferIngester = new BufferIngester([
            'onFlush' => array($this->parseableClient, 'sendEvents'),
            'onError' => array($this, 'handle_error'),
            'flushInterval' => 5000,
        ]);
    }

    public function ingest_logs() {
        // Collect and push logs here
        $logs = $this->collect_logs();
        $this->bufferIngester->push($logs);
    }

    private function collect_logs() {
        // Collect logs from various sources (system, PHP, theme, plugins)
        return array(); // Replace with actual log collection logic
    }

    public function handle_error($error) {
        error_log($error->getMessage());
    }
}
?>
