<?php

namespace Selective\SameSiteCookie;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * SameSite Cookie Middleware.
 */
final class SameSiteCookieMiddlware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $sameSite;

    /**
     * @var bool
     */
    private $httpOnly;

    /**
     * @var bool
     */
    private $secure;

    /**
     * The constructor.
     *
     * @param string $sameSite Send cookie only via a href link. Values: 'Lax' or 'Strict'.
     * @param bool $httpOnly Prevents cookies from being read by scripts. Should be enabled.
     * @param bool $secure Provide cookies only via ssl. Should be enabled in production.
     */
    public function __construct(string $sameSite = 'Lax', bool $httpOnly = true, bool $secure = false)
    {
        $this->sameSite = $sameSite;
        $this->httpOnly = $httpOnly;
        $this->secure = $secure;
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
        $response = $handler->handle($request);

        $sessionId = $request->getAttribute('session_id');
        $sessionName = $request->getAttribute('session_name');
        $params = $request->getAttribute('session_cookie_params');

        if (version_compare(PHP_VERSION, '7.3.0') >= 0) {
            // Remove invalid key
            unset($params['lifetime']);

            $params['samesite'] = $this->sameSite;
            $params['httponly'] = $this->httpOnly;
            $params['secure'] = $this->secure;

            setcookie($sessionName, $sessionId, $params);

            return $response;
        }

        // For older PHP versions
        $cookieValues = [
            sprintf('%s=%s;', $sessionName, $sessionId),
            sprintf('path=%s;', $params['path']),
        ];

        if ($this->secure) {
            $cookieValues[] = 'Secure;';
        }

        if ($this->httpOnly) {
            $cookieValues[] = 'HttpOnly;';
        }

        if ($this->sameSite) {
            $cookieValues[] = sprintf('SameSite=%s;', $this->sameSite);
        }

        //$response = $response->withHeader('Set-Cookie', implode(' ', $cookieValues));
        header('Set-Cookie: ' . implode(' ', $cookieValues));

        return $response;
    }
}
