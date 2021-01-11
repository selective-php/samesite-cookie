<?php

namespace Selective\SameSiteCookie;

/**
 * The SessionHandlerInterface.
 */
interface SessionHandlerInterface
{
    /**
     * Get session id.
     *
     * @return string The id
     */
    public function getId(): string;

    /**
     * Start session.
     *
     * @retrun void
     */
    public function start(): void;

    /**
     * Get session name.
     *
     * @return string|null The name
     */
    public function getName(): ?string;

    /**
     * Get cookie params.
     *
     * @return array<mixed> The params
     */
    public function getCookieParams(): array;

    /**
     * Is session started.
     *
     * @return bool The session status
     */
    public function isStarted(): bool;

    /**
     * Save session.
     *
     * @return void
     */
    public function save(): void;
}
