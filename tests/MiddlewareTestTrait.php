<?php

namespace Selective\SameSiteCookie\Test;

use Middlewares\Utils\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * Test.
 */
trait MiddlewareTestTrait
{
    /**
     * Run middleware stack.
     *
     * @param array<MiddlewareInterface> $queue The middleware queue
     *
     * @return ResponseInterface The response
     */
    protected function runQueue(array $queue): ResponseInterface
    {
        $queue[] = new ResponseFactoryMiddleware();

        return Dispatcher::run($queue);
    }

    /**
     * Factory.
     */
    protected function createRequest(): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest('GET', '/');
    }
}
