<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class TypeUniqueSlugException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'URL moet uniek zijn',
        ],
    ];
}
