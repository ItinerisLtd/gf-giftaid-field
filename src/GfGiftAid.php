<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use Itineris\GfGiftaidField\GiftAidField;
use GF_Fields;

class GfGiftAid
{
    public static function load() {
        self::hooks();

        GF_Fields::register('GiftAidField');
    }

    public static function hooks() {
        add_action('wp_enqueue_scripts', [static::class, 'enqueueGiftAidScripts']);
        add_action('gform_enqueue_scripts', function (): void {
            wp_enqueue_script('gf-gift-aid');
        });
    }

    public static function enqueueGiftAidScripts() {
        $jsFileURI = GIFT_AID_URI . '/src/public/js/gift-aid.js';
        wp_register_script('gf-gift-aid', $jsFileURI , [], null, true);
    }
}
