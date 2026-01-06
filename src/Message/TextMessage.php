<?php

declare(strict_types=1);

/**
 * File: TextMessage.php
 *
 * @author Bartosz Juszczyk <b.juszczyk@bjuszczyk.pl>
 * @copyright Copyright (C) 2026 Bartosz Juszczyk
 */

namespace Juszczyk\WhatsApp\Message;

use Juszczyk\WhatsApp\Enum\MessageType;
use Juszczyk\WhatsApp\Interface\MessageInterface;

readonly class TextMessage implements MessageInterface
{
    public function __construct(
        private string $recipient,
        private string $body,
        private bool $previewUrl = false
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return MessageType::TEXT->value;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->recipient,
            'type' => $this->getType(),
            'text' => [
                'preview_url' => $this->previewUrl,
                'body' => $this->body
            ]
        ];
    }


}