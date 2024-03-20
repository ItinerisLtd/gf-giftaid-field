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
        if ($position !== 25) {
            return;
        }
?>
        <li class="donation_total_select field_setting">

            <label for="donation_total_select_value" class="section_label">
                <?php esc_html_e("Donation Total Select", "itineris-gf-giftaid-field"); ?>
            </label>

            <select name="donation_total" id="donation_total_select_value" onchange="SetFieldProperty('donationTotalSelect', this.value);">
                <option value="query_parameter"><?php esc_html_e("Query Parameter", "itineris-gf-giftaid-field"); ?></option>
                <option value="ginput_total"><?php esc_html_e("ginput_total Input", "itineris-gf-giftaid-field"); ?></option>
            </select>
        </li>
<?php
    }
}
