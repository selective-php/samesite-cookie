<?php

namespace Selective\SameSiteCookie;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * SameSite Cookie Middleware.
 */
final class SameSiteCookieMiddleware implements MiddlewareInterface
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
     * @param SameSiteCookieConfiguration|null $configuration The configuration
     * @param SessionHandlerInterface|null $sessionHandler The session handler
     */
    public function __construct(
        SameSiteCookieConfiguration $configuration = null,
        SessionHandlerInterface $sessionHandler = null
    ) {
        $this->configuration = $configuration ?: new SameSiteCookieConfiguration();
        $this->sessionHandler = $sessionHandler ?: new PhpSessionHandler();
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @throws RuntimeException
     *
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $sessionId = $this->sessionHandler->getId();
        $sessionName = $this->sessionHandler->getName();
        $params = $this->sessionHandler->getCookieParams();

        if (!$sessionId || !$sessionName || !$params) {
            throw new RuntimeException('The session must be started before samesite cookie can be generated.');
        }

        $cookieValues = [
            sprintf('%s=%s;', $sessionName, $sessionId),
            sprintf('path=%s;', $params['path']),
        ];

        if ($this->configuration->secure) {
            $cookieValues[] = 'Secure;';
        }

        if ($this->configuration->httpOnly) {
            $cookieValues[] = 'HttpOnly;';
        }

        if ($this->configuration->sameSite) {
            $cookieValues[] = sprintf('SameSite=%s;', $this->configuration->sameSite);
        }

        return $response->withHeader('Set-Cookie', implode(' ', $cookieValues));
    }
}
