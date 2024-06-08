<?php

class ParseableClient {
    private $baseurl;
    private $logstream;
    private $username;
    private $password;
    private $tags;
    private $disableTLSCerts;
    private $http2;
    private $debug;

    public function __construct($options) {
        $this->baseurl = $options['url'];
        $this->logstream = $options['logstream'];
        $this->username = $options['username'];
        $this->password = $options['password'];
        $this->tags = isset($options['tags']) ? $options['tags'] : array();
        $this->disableTLSCerts = isset($options['disableTLSCerts']) ? $options['disableTLSCerts'] : false;
        $this->http2 = isset($options['http2']) ? $options['http2'] : true;
        $this->debug = isset($options['debug']) ? $options['debug'] : function() {};
    }

    private function get_url() {
        $url = rtrim($this->baseurl, '/') . '/' . ltrim($this->logstream, '/');
        return $url;
    }

    public function sendEvents($events) {
        $auth = base64_encode($this->username . ':' . $this->password);
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $auth
        );

        foreach ($this->tags as $key => $value) {
            $headers[] = "X-P-TAG-$key: $value";
        }

        $data = json_encode($events);

        $options = array(
            'http' => array(
                'header' => implode("\r\n", $headers),
                'method' => 'POST',
                'content' => $data,
                'ignore_errors' => true
            )
        );

        if ($this->disableTLSCerts) {
            $options['ssl'] = array(
                'verify_peer' => false,
                'verify_peer_name' => false
            );
        }

        $context = stream_context_create($options);
        $result = file_get_contents($this->get_url(), false, $context);

        if ($result === FALSE) {
            $error = error_get_last();
            throw new Exception($error['message']);
        }

        if (isset($http_response_header)) {
            $status_line = $http_response_header[0];
            preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
            $status = $match[1];
            if ($status >= 400) {
                throw new Exception("Error $status: $result");
            }
        }
    }
}
?>
