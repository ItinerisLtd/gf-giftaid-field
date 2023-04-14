<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use GF_Fields;
use Itineris\GfGiftaidField\GiftAidField;

class GfGiftAidField
{
    public static function load(): void
    {
        static::hooks();

        GF_Fields::register(new GiftAidField());
    }

    public static function hooks(): void
    {
        add_action('wp_enqueue_scripts', [static::class, 'enqueueGiftAidScripts']);
        add_action('gform_enqueue_scripts', function (): void {
            wp_enqueue_script('gf-gift-aid-field');
        });
    }

    public static function enqueueGiftAidScripts(): void
    {
        wp_register_script(
            'gf-gift-aid-field',
            ITINERIS_GF_GIFT_AID_FIELD_URI . '/public/js/gift-aid.js',
            [],
            null,
            true,
        );
    }
}
