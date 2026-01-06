# WhatsApp Cloud API PHP SDK

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-777bb4.svg)](https://www.php.net/)
[![Build Status](https://img.shields.io/github/actions/workflow/status/bartoszjuszczyk/whatsapp-php-client/tests.yml)](https://github.com/bartoszjuszczyk/whatsapp-php-client/actions)

PHP library for the **WhatsApp API**.

Designed for modern PHP (8.2+) with clean architecture in mind. This library is HTTP client-agnostic—it works seamlessly
with Guzzle, Symfony HTTP Client, or any other PSR-18 implementation.

## 🚀 Features (MVP)

* ✅ **Send Text Messages**
* ✅ **Full PSR-7, PSR-17, and PSR-18 compliance**
* ✅ **Strict Types & Readonly Properties** (PHP 8.2)
* ✅ **Zero-config instantiation** (via HTTP Discovery)
* ✅ **Immutability** (Immutable DTOs)

## 📦 Requirements

* PHP ^8.2
* Composer
* An HTTP Client library (e.g., Guzzle, Symfony HttpClient)

## 📥 Installation

Install the library via Composer:

```bash
composer require juszczyk/whatsapp-php-client
```

### Installing an HTTP Client

This library relies on the `php-http/discovery` abstraction. If your project does not strictly require a specific HTTP
client yet, we recommend installing Guzzle:

```bash
composer require guzzlehttp/guzzle
```

## ⚡ Quick Start

The easiest way to instantiate the client is using the `ClientFactory`. It automatically discovers the installed HTTP
client and factories.

```php
use Juszczyk\WhatsApp\Factory\ClientFactory;
use Juszczyk\WhatsApp\Message\TextMessage;

require 'vendor/autoload.php';

// 1. Configuration (Token and Phone Number ID from Meta Developers panel)
$token = 'EAAG...'; 
$phoneId = '105...';

$whatsapp = ClientFactory::create($token, $phoneId);

// Recipient's phone number (with country code, no plus sign)
$message = new TextMessage('48123456789', 'Hello! This is a test from PHP SDK.');

try {
    $response = $whatsapp->send($message);
    echo "Message sent! ID: " . $response['messages'][0]['id'];
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## 🏗️ Architecture & Dependency Injection

If you are using a framework (like Laravel or Symfony) and want to inject your own configured HTTP client (e.g., with a
Logger or Retry Middleware), you can bypass the Factory and use the Constructor directly.

```php
use Juszczyk\WhatsApp\Client;
use Juszczyk\WhatsApp\Config;

$config = new Config($token, $phoneId);

// You can inject any PSR-18 Client & PSR-17 Factory implementation here
$client = new Client($config, $httpClient, $requestFactory, $streamFactory);
```

## 🧪 Testing

This library comes with a set of unit tests using PHPUnit.

```bash
composer test
# or
vendor/bin/phpunit
```

Static analysis (PHPStan):

```bash
vendor/bin/phpstan analyse
```

## 🗺️ Roadmap

Current version is an MVP. Planned features:

* [ ] Media Messages (Images, Documents)
* [ ] Template Messages
* [ ] Interactive Messages (Buttons, Lists)
* [ ] Webhooks support

## 📄 License

This library is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.