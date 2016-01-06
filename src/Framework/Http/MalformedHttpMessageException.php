<?php

namespace Framework\Http;

class MalformedHttpMessageException extends \RuntimeException
{
    private $httpMessage;

    public function __construct($httpMessage, $message = '', \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->httpMessage = $httpMessage;
    }

    public function getHttpMessage()
    {
        return $this->httpMessage;
    }
}
