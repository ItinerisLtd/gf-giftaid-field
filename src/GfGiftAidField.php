<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use GF_Field;
use GFAddOn;
use GFAPI;
use GFForms;

class GfGiftAidField
{
    public const VERSION = '0.1.0';

    public static function run(): void
    {
        if (!method_exists('GFForms', 'include_addon_framework')) {
            return;
        }

        GFForms::include_addon_framework();
        GFAddOn::register(AddOn::class);

        add_action('gform_field_standard_settings', [static::class, 'addSelectedPriceFieldSetting'], 10, 2);
    }

    /**
     * Custom field to enable the user to choose where they would like to pull the total value from.
     */
    public static function addSelectedPriceFieldSetting(int $position, int $form_id): void
    {
        if (25 !== $position || empty($form_id)) {
            return;
        }
        $exists = GFAPI::form_id_exists($form_id);
        if (empty($exists)) {
            return;
        }
        $field_options = static::getFormFields($form_id);
        if (empty($field_options)) {
            return;
        }

?>
        <li class="selected_price_field_setting field_setting">
            <label for="selected_price_field_dropdown" class="section_label">
                <?php esc_html_e('Price Field', 'itineris-gf-giftaid-field'); ?>
            </label>

            <select name="donation_total" id="selected_price_field_dropdown" onchange="SetFieldProperty('selectedPriceField', this.value);">
                <option value="">--Please select a field--</option>

                <?php foreach ($field_options as $field_id => $field_label) : ?>
                    <?php
                    if (empty($field_id) || empty($field_label)) {
                        continue;
                    }
                    ?>

                    <option value="<?php echo esc_attr($field_id); ?>">
                        <?php echo esc_html($field_label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </li>
<?php
    }

    /**
     * Get all the fields in a gravity form in the format:
     * ['field class' => 'field_label', ...]
     */
    public static function getFormFields(int $form_id): array
    {
        $form = GFAPI::get_form($form_id);
        $fields = $form['fields'] ?? [];
        if (empty($fields) || !is_array($fields)) {
            return [];
        }

        return array_reduce($fields, function (array $carry, GF_Field $field) use ($form_id): array {
            if (empty($field) || empty($field->id) || empty($field->label)) {
                return $carry;
            }
            $class = "field_{$form_id}_{$field->id}";
            $carry[$class] = $field->label;
            return $carry;
        }, []);
    }
}
