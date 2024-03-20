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

            <label for="donation_total_select_value" style="display:inline;">
                <?php _e("Donation Total Select", "itineris-gf-giftaid-field"); ?>
                <?php gform_tooltip("form_field_donation_total_select_value") ?>
            </label>

            <select name="donation_total" id="donation_total_select_value">
                <option value="query_parameter"><?php _e("Query Parameter", "itineris-gf-giftaid-field"); ?></option>
                <option value="ginput_total"><?php _e("ginput_total Input", "itineris-gf-giftaid-field"); ?></option>
            </select>
            <!-- <input type="checkbox" id="field_encrypt_value" onclick="SetFieldProperty('encryptField', this.checked);" /> -->

        </li>
<?php
    }
}
