<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use Itineris\GfGiftaidField\GiftAidField;
use GF_Fields;

class GfGiftAidField
{
    public static function load()
    {
        static::hooks();

        GF_Fields::register(new GiftAidField());
    }

    public static function hooks()
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
            GIFT_AID_URI . '/public/js/gift-aid.js',
            [],
            null,
            true,
        );
    }
}
