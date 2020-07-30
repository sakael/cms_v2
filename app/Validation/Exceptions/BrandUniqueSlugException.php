<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class BrandUniqueSlugException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'URL moet uniek zijn',
        ],
    ];
}
