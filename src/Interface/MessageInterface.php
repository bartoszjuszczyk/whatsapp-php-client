<?php

declare(strict_types=1);

/**
 * File: MessageInterface.php
 *
 * @author Bartosz Juszczyk <b.juszczyk@bjuszczyk.pl>
 * @copyright Copyright (C) 2026 Bartosz Juszczyk
 */

namespace Juszczyk\WhatsApp\Interface;

interface MessageInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}