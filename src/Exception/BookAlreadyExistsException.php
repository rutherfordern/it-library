<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class BookAlreadyExistsException extends ConflictHttpException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
