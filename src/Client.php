<?php

declare(strict_types=1);

/**
 * File: Client.php
 *
 * @author Bartosz Juszczyk <b.juszczyk@bjuszczyk.pl>
 * @copyright Copyright (C) 2026 Bartosz Juszczyk
 */

namespace Juszczyk\WhatsApp;

use Juszczyk\WhatsApp\Exception\WhatsAppException;
use Juszczyk\WhatsApp\Interface\MessageInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Throwable;

readonly class Client
{
    public function __construct(
        private Config $config,
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory
    ) {
    }

    /**
     * @param MessageInterface $message
     * @return array
     * @throws WhatsAppException
     */
    public function send(MessageInterface $message): array
    {
        $payload = json_encode($message->toArray());

        $request = $this->requestFactory
            ->createRequest('POST', $this->config->getApiUrl())
            ->withHeader('Authorization', 'Bearer ' . $this->config->getAccessToken())
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->streamFactory->createStream($payload));

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (Throwable $e) {
            throw new WhatsAppException("Network error: " . $e->getMessage(), 0, $e);
        }

        $statusCode = $response->getStatusCode();
        $responseBody = (string)$response->getBody();
        $data = json_decode($responseBody, true);

        if ($statusCode >= 400) {
            throw new WhatsAppException(
                sprintf('API WhatsApp error [%d]: %s', $statusCode, $responseBody)
            );
        }

        return $data;
    }
}