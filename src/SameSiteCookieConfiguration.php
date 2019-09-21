<?php

namespace Selective\SameSiteCookie;

/**
 * SameSite Cookie Configuration.
 */
final class SameSiteCookieConfiguration
{
    /**
     * @var bool Start the session
     */
    public $startSession = true;

    /**
     * @var string Send cookie only via a href link. Values: 'Lax' or 'Strict'.
     */
    public $sameSite = 'Lax';

    /**
     * Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client.
     * When set to true, the cookie will only be set if a secure connection exists.
     *
     * true = Set cookie only for HTTPS
     * false = Set cookie for HTTP and HTTPS
     *
     * @var bool Prevents cookies from being read by scripts.
     */
    public $httpOnly = true;

    /**
     * @var bool Provide cookies only via ssl. Should be enabled in production.
     */
    public $secure = true;
}
