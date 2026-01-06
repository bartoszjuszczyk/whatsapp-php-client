<?php

declare(strict_types=1);

/**
 * File: Config.php
 *
 * @author Bartosz Juszczyk <b.juszczyk@bjuszczyk.pl>
 * @copyright Copyright (C) 2026 Bartosz Juszczyk
 */

namespace Juszczyk\WhatsApp;

readonly class Config
{
    public const API_VERSION = 'v17.0';
    public const BASE_URL = 'https://graph.facebook.com';

    /**
     * @param string $accessToken
     * @param string $phoneNumberId
     */
    public function __construct(
        private string $accessToken,
        private string $phoneNumberId
    ) {
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return sprintf('%s/%s/%s/messages', self::BASE_URL, self::API_VERSION, $this->phoneNumberId);
    }
}