<?php

namespace Simplex\Event;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\EventDispatcher\Event;

class Response extends Event
{
    private $request;
    private $response;

    public function __construct(HttpResponse $response, HttpRequest $request)
    {
        $this->response = $response;
        $this->request = $request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }
}