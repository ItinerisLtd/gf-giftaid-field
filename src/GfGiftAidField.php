<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use GFAddOn;
use GFForms;

class GfGiftAidField
{
    public const VERSION = '0.1.0';

    public static function run(): void
    {
        if (! method_exists('GFForms', 'include_addon_framework')) {
            return;
        }

        GFForms::include_addon_framework();
        GFAddOn::register(AddOn::class);
    }
}
