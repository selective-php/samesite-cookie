<?php

namespace Selective\SameSiteCookie\Test;

use PHPUnit\Framework\TestCase;
use Selective\SameSiteCookie\SameSiteCookieConfiguration;
use Selective\SameSiteCookie\SameSiteCookieMiddleware;
use Selective\SameSiteCookie\SameSiteSessionMiddleware;

/**
 * Test.
 */
class SameSiteCookieMiddlewareTest extends TestCase
{
    use MiddlewareTestTrait;

    /**
     * Test.
     */
    public function testDefaultConfiguration(): void
    {
        $configuration = new SameSiteCookieConfiguration();

        session_id('v3absd19o9pi6cjvhb5pkmsfo9');

        $response = $this->runQueue([
            new SameSiteSessionMiddleware(),
            new SameSiteCookieMiddleware($configuration),
        ]);

        $cookie = $response->getHeaderLine('Set-Cookie');
        $this->assertSame('PHPSESSID=v3absd19o9pi6cjvhb5pkmsfo9; path=/; Secure; HttpOnly; SameSite=Lax;', $cookie);
        $this->assertSame('', (string)$response->getBody());
    }

    /**
     * Test with own settings.
     */
    public function testDefaultConfigurationWithOwnSettings(): void
    {
        $settings = [
            'start_session' => true,
            'same_site' => 'Strict',
            'http_only' => false,
        ];

        $configuration = new SameSiteCookieConfiguration($settings);

        session_id('v3absd19o9pi6cjvhb5pkmsfo9');

        $response = $this->runQueue([
            new SameSiteSessionMiddleware(),
            new SameSiteCookieMiddleware($configuration),
        ]);

        $cookie = $response->getHeaderLine('Set-Cookie');
        $this->assertSame('PHPSESSID=v3absd19o9pi6cjvhb5pkmsfo9; path=/; Secure; SameSite=Strict;', $cookie);
        $this->assertSame('', (string)$response->getBody());
    }
}
