<?php

declare(strict_types=1);

/**
 * File: ClientFactory.php
 *
 * @author Bartosz Juszczyk <b.juszczyk@bjuszczyk.pl>
 * @copyright Copyright (C) 2026 Bartosz Juszczyk
 */

namespace Juszczyk\WhatsApp\Factory;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Juszczyk\WhatsApp\Client;
use Juszczyk\WhatsApp\Config;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ClientFactory
{
    /**
     * @param string $accessToken
     * @param string $phoneNumberId
     * @param ClientInterface|null $httpClient
     * @param RequestFactoryInterface|null $requestFactory
     * @param StreamFactoryInterface|null $streamFactory
     * @return Client
     */
    public static function create(
        string $accessToken,
        string $phoneNumberId,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null
    ): Client {
        $config = new Config($accessToken, $phoneNumberId);
        $httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();

        return new Client($config, $httpClient, $requestFactory, $streamFactory);
    }


}