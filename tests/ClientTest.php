<?php

declare(strict_types=1);

/**
 * File: ClientTest.php
 *
 * @author Bartosz Juszczyk <b.juszczyk@bjuszczyk.pl>
 * @copyright Copyright (C) 2026 Bartosz Juszczyk
 */

namespace Tests;

use Juszczyk\WhatsApp\Client;
use Juszczyk\WhatsApp\Config;
use Juszczyk\WhatsApp\Exception\WhatsAppException;
use Juszczyk\WhatsApp\Message\TextMessage;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class ClientTest extends TestCase
{
    /**
     * @var ClientInterface
     */
    private ClientInterface $httpClient;
    /**
     * @var RequestFactoryInterface
     */
    private RequestFactoryInterface $requestFactory;
    /**
     * @var StreamFactoryInterface
     */
    private StreamFactoryInterface $streamFactory;
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);
        $this->streamFactory = $this->createMock(StreamFactoryInterface::class);

        $this->config = new Config('access_token', '123456');
    }

    /**
     * @return void
     * @throws WhatsAppException
     * @throws Exception
     */
    public function test_it_sends_text_message_successfully(): void
    {
        $message = new TextMessage('48123456789', 'Hello World');

        $responseBody = json_encode(['messages' => [['id' => 'wamid.HBg.7hgeak']]]);
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getBody')->willReturn($responseBody);

        $mockStream = $this->createMock(StreamInterface::class);
        $this->streamFactory->method('createStream')->willReturn($mockStream);

        $mockRequest = $this->createMock(RequestInterface::class);
        $mockRequest->method('withHeader')->willReturnSelf();
        $mockRequest->method('withBody')->willReturnSelf();

        $this->requestFactory->method('createRequest')
            ->with('POST', 'https://graph.facebook.com/v17.0/123456/messages')
            ->willReturn($mockRequest);

        $this->httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($mockRequest)
            ->willReturn($mockResponse);

        $client = new Client(
            $this->config,
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory
        );

        $result = $client->send($message);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertEquals('wamid.HBg.7hgeak', $result['messages'][0]['id']);
    }

    /**
     * @return void
     * @throws WhatsAppException
     * @throws Exception
     */
    public function test_it_throws_exception_on_api_error(): void
    {
        $message = new TextMessage('48123456789', 'Error Msg');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(400);
        $mockResponse->method('getBody')->willReturn('{"error": "Invalid parameter"}');

        $this->streamFactory->method('createStream')
            ->willReturn($this->createMock(StreamInterface::class));

        $mockRequest = $this->createMock(RequestInterface::class);
        $mockRequest->method('withHeader')->willReturnSelf();
        $mockRequest->method('withBody')->willReturnSelf();
        $this->requestFactory->method('createRequest')->willReturn($mockRequest);

        $this->httpClient->method('sendRequest')->willReturn($mockResponse);

        $client = new Client($this->config, $this->httpClient, $this->requestFactory, $this->streamFactory);

        $this->expectException(WhatsAppException::class);
        $this->expectExceptionMessage('API WhatsApp error [400]');

        $client->send($message);
    }
}