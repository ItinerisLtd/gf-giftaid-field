<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use GF_Fields;
use GFAddOn;

class AddOn extends GFAddOn
{
    private static ?self $_instance = null;

    protected $_version = GfGiftAidField::VERSION;
    protected $_slug = 'gf-giftaid-field';
    protected $_path = 'gf-giftaid-field/gf-giftaid-field.php';
    protected $_full_path = __DIR__;
    protected $_title = 'GF GiftAid Field';
    protected $_short_title = 'GF GiftAid Field';
    protected $_url = 'https://github.com/ItinerisLtd/gf-giftaid-field';

    public static function get_instance(): self
    {
        if (! self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct()
    {
        $this->_min_gravityforms_version = MinimumRequirements::GRAVITY_FORMS_VERSION->value;

        parent::__construct();
    }

    public function minimum_requirements(): array
    {
        return MinimumRequirements::toArray();
    }

    public function pre_init(): void
    {
        parent::pre_init();

        if ($this->is_gravityforms_supported() && class_exists('GF_Field')) {
            GF_Fields::register(new GiftAidField());
        }
    }

    public function scripts(): array
    {
        return [
            ...parent::scripts(),
            [
                'handle' => "{$this->_slug}-frontend",
                'src' => $this->get_base_url() . "/public/js/{$this->_slug}-frontend.js",
                'version' => $this->_version,
                'deps' => [],
                'in_footer' => true,
                'enqueue' => [
                    'callback' => fn (): bool => ! is_admin(),
                ],
            ],
            [
                'handle' => "{$this->_slug}-admin",
                'src' => $this->get_base_url() . "/public/js/{$this->_slug}-admin.js",
                'version' => $this->_version,
                'deps' => [],
                'in_footer' => true,
                'enqueue' => [
                    [
                        'admin_page' => ['form_editor'],
                    ],
                ],
            ],
        ];
    }
}
