<?php

declare(strict_types=1);

/**
 * File: WhatsAppException.php
 *
 * @author Bartosz Juszczyk <b.juszczyk@bjuszczyk.pl>
 * @copyright Copyright (C) 2026 Bartosz Juszczyk
 */


namespace Juszczyk\WhatsApp\Exception;

use Exception;
use Throwable;

class WhatsAppException extends Exception
{
    public function __construct($message, $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}