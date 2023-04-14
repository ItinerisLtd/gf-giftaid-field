<?php
/**
 * Plugin Name:     Gravity Forms Gift Aid field
 * Plugin URI:      https://www.itineris.co.uk/
 * Description:     Gift aid field for Gravity Forms
 * Version:         0.1.0
 * Author:          Itineris Limited
 * Author URI:      https://www.itineris.co.uk/
 * Text Domain:     itineris-gf-giftaid-field
 */

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use Itineris\GfGiftaidField\GfGiftAidField;

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

if (! defined('GIFT_AID_URI')) {
    define('GIFT_AID_URI', plugins_url(basename(__DIR__)));
}

add_action('gform_loaded', [GfGiftAidField::class, 'load'], 5);
