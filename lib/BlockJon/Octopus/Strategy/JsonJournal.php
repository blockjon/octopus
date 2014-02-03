<?php

namespace Octopus\Strategy;

use Octopus\Exception\Strategy\Exception;

class JsonJournal extends AbstractStrategy
{

    /**
     * Holds the PHP stream to log to.
     *
     * @var null|stream
     */
    protected $_stream = null;

    /**
     * Class Constructor
     *
     * @param array|string|resource $streamOrUrl Stream or URL to open as a stream
     * @param string|null $mode Mode, only applicable if a URL is given
     * @return void
     * @throws Exception
     */
    public function __construct($streamOrUrl, $mode = null)
    {
        // Setting the default
        if (null === $mode) {
            $mode = 'a';
        }

        if (is_resource($streamOrUrl)) {
            if (get_resource_type($streamOrUrl) != 'stream') {
                throw new Exception('Resource is not a stream');
            }

            if ($mode != 'a') {
                throw new Exception('Mode cannot be changed on existing streams');
            }

            $this->_stream = $streamOrUrl;
        } else {
            if (is_array($streamOrUrl) && isset($streamOrUrl['stream'])) {
                $streamOrUrl = $streamOrUrl['stream'];
            }

            if (! $this->_stream = @fopen($streamOrUrl, $mode, false)) {
                $msg = "\"$streamOrUrl\" cannot be opened with mode \"$mode\"";
                throw new Exception($msg);
            }
        }

    }

    /**
     * Close the stream resource.
     *
     * @return void
     */
    public function shutdown()
    {
        if (is_resource($this->_stream)) {
            fclose($this->_stream);
        }
    }

    /**
     * Journal a JSON message to the log.
     *
     * @param  array $data_array
     * @return void
     * @throws Exception
     */
    public function create(array $data_array)
    {
        $envelope = array(
            'method' => 'create',
            'payload' => $data_array,
        );
        $json_string = json_encode($envelope);
        if (false === @fwrite($this->_stream, $json_string)) {
            throw new Exception("Unable to write to stream");
        }
    }

}
