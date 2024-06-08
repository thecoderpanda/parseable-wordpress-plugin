<?php

class BufferIngester {
    private $values = array();
    private $maxEntries;
    private $maxRetries;
    private $flushInterval;
    private $errorCodesToRetry;
    private $onFlush;
    private $onError;
    private $debug;
    private $flushTimer;

    public function __construct($options) {
        $this->onFlush = $options['onFlush'];
        $this->onError = $options['onError'];
        $this->maxEntries = isset($options['maxEntries']) ? $options['maxEntries'] : 250;
        $this->maxRetries = isset($options['maxRetries']) ? $options['maxRetries'] : 3;
        $this->flushInterval = isset($options['flushInterval']) ? $options['flushInterval'] : 5000;
        $this->errorCodesToRetry = isset($options['errorCodesToRetry']) ? $options['errorCodesToRetry'] : array('UND_ERR_CONNECT_TIMEOUT', 'UND_ERR_SOCKET', 'ECONNRESET', 'EPIPE');
        $this->debug = isset($options['debug']) ? $options['debug'] : function() {};
        $this->flushTimer = setInterval(array($this, 'flush'), $this->flushInterval);
    }

    public function push($value) {
        $this->values[] = $value;
        if (count($this->values) >= $this->maxEntries) {
            $this->flush();
        }
    }

    public function flush() {
        $values = $this->values;
        if (empty($values)) {
            return;
        }

        $doFlushAttempt = function($attempt = 1) use ($values, &$doFlushAttempt) {
            ($this->debug)("flushing " . count($values) . " entries" . ($attempt > 1 ? " attempt $attempt" : ''));

            try {
                $this->values = array();
                call_user_func($this->onFlush, $values);
            } catch (Exception $error) {
                if (in_array($error->getCode(), $this->errorCodesToRetry) && $attempt < $this->maxRetries) {
                    usleep(250 * 1000); // wait for 250ms
                    $doFlushAttempt($attempt + 1);
                } else {
                    call_user_func($this->onError, $error);
                }
            }
        };

        $doFlushAttempt();
    }

    public function close() {
        clearInterval($this->flushTimer);
        $this->flush();
    }
}

function setInterval($f, $milliseconds) {
    $seconds = (int)$milliseconds / 1000;
    return pcntl_fork() == 0 ? (usleep($seconds * 1000000), $f(), exit) : 1;
}

function clearInterval($timer) {
    posix_kill($timer, SIGKILL);
}
?>
