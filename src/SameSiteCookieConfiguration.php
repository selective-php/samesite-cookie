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
     * @var bool prevents cookies from being read by scripts
     */
    public $httpOnly = true;

    /**
     * @var bool Provide cookies only via ssl. Should be enabled in production.
     */
    public $secure = true;

    /**
     * The constructor.
     *
     * @param array<mixed> $settings The settings
     */
    public function __construct(array $settings = [])
    {
        if (isset($settings['start_session'])) {
            $this->startSession = (bool)$settings['start_session'];
        }

        if (isset($settings['same_site'])) {
            $this->sameSite = (string)$settings['same_site'];
        }

        if (isset($settings['http_only'])) {
            $this->httpOnly = (bool)$settings['http_only'];
        }
    }
}
