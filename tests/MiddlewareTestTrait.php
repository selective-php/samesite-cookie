<?php

namespace Selective\SameSiteCookie\Test;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Relay\Relay;

/**
 * Test.
 */
trait MiddlewareTestTrait
{
    /**
     * Run middleware stack.
     *
     * @param array $queue The queue
     *
     * @return ResponseInterface The response
     */
    protected function runQueue(array $queue): ResponseInterface
    {
        $queue[] = new ResponseFactoryMiddleware();

        $request = $this->createRequest();
        $relay = new Relay($queue);

        return $relay->handle($request);
    }

    /**
     * Factory.
     *
     * @return ServerRequestInterface
     */
    protected function createRequest(): ServerRequestInterface
    {
        return (new Psr17Factory())->createServerRequest('GET', '/');
    }
}
