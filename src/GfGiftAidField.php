<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

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

        add_action('gform_field_standard_settings', [static::class, 'add_donation_total_select_setting'], 10, 2);
    }

    /**
     * Custom field to enable the user to choose where they would like to pull the total value from.
     * Add 'donation_total_select' to the array of strings in the get_form_editor_field_settings method of your GF_Field Class to use.
     */
    public static function add_donation_total_select_setting($position, $form_id)
    {
        if ($position !== 25 || empty($form_id)) {
            return;
        }
        $field_options = static::getFormFields($form_id);
        if (empty($field_options)) {
            return;
        }
?>
        <li class="donation_total_select field_setting">
            <label for="donation_total_select_value" class="section_label">
                <?php esc_html_e("Donation Total Select", "itineris-gf-giftaid-field"); ?>
            </label>

            <select name="donation_total" id="donation_total_select_value" onchange="SetFieldProperty('donationTotalSelect', this.value);">
                <?php foreach ($field_options as $field_id => $field_label) : ?>
                    <?php if (empty($field_id) || empty($field_label)) continue; ?>

                    <option value="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($field_label); ?></option>
                <?php endforeach; ?>
            </select>
        </li>
<?php
    }

    /**
     * Get all the fields in a gravity form in the format:
     * ['field class' => 'field_label', ...]
     */
    public static function getFormFields(mixed $form_id): array
    {
        $exists = GFAPI::form_id_exists($form_id);
        if (empty($exists)) {
            return [];
        }

        $form = GFAPI::get_form($form_id);
        $fields = $form['fields'] ?? [];
        if (empty($fields) || !is_array($fields)) {
            return [];
        }

        $field_options = array_reduce($fields, function ($carry, $field) use ($form_id) {
            if (empty($field) || empty($field->id) || empty($field->label)) {
                return $carry;
            }
            $class = "field_{$form_id}_{$field->id}";
            $carry[$class] = $field->label;
            return $carry;
        }, []);
        if (empty($field_options)) {
            return [];
        }

        return $field_options;
    }
}
