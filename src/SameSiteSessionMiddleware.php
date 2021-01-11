<?php

namespace Selective\SameSiteCookie;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * SameSite Session Middleware.
 */
final class SameSiteSessionMiddleware implements MiddlewareInterface
{
    /**
     * @var SameSiteCookieConfiguration
     */
    private $configuration;

    /**
     * @var SessionHandlerInterface
     */
    private $sessionHandler;

    /**
     * The constructor.
     *
     * @param SameSiteCookieConfiguration $configuration The configuration
     * @param SessionHandlerInterface|null $sessionHandler The session handler
     */
    public function __construct(
        SameSiteCookieConfiguration $configuration,
        SessionHandlerInterface $sessionHandler = null
    ) {
        $this->configuration = $configuration;
        $this->sessionHandler = $sessionHandler ?: new PhpSessionHandler();
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Start session
        if ($this->configuration->startSession === true && !$this->sessionHandler->isStarted()) {
            $this->sessionHandler->start();
        }

        $request = $request
            ->withAttribute('session_id', $this->sessionHandler->getId())
            ->withAttribute('session_name', $this->sessionHandler->getName())
            ->withAttribute('session_cookie_params', $this->sessionHandler->getCookieParams());

        return $handler->handle($request);
    }
}
