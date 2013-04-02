<?php

namespace Muse\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

class Error
{
    public function exceptionAction(FlattenException $exception)
    {
        $msg = 'Something went wrong! (' . $exception->getMessage() . ')';

        return new Response($msg, $exception->getStatusCode());
    }
}