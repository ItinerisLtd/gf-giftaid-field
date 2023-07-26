<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

enum MinimumRequirements: string
{
    case GRAVITY_FORMS_VERSION = '2.7';
    case WORDPRESS_VERSION = '6.2';
    case PHP_VERSION = '8.1';

    public static function toArray(): array
    {
        return [
            'wordpress' => [
                'version' => static::WORDPRESS_VERSION->value,
            ],
            'php' => [
                'version' => static::PHP_VERSION->value,
            ],
        ];
    }
}
