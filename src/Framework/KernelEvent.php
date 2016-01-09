<?php

namespace Framework;

use Framework\EventManager\Event;
use Framework\Http\Request;
use Framework\Http\Response;

class KernelEvent extends Event
{
    private $request;
    private $response;

    public function __construct(Request $request, Response $response = null)
    {
        parent::__construct();

        $this->request = $request;
        $this->response = $response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function hasResponse()
    {
        return null !== $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
        $this->stop();
    }
}
