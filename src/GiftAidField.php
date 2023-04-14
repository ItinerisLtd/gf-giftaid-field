<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use GF_Field;

class GiftAidField extends GF_Field {

    public string $type = 'gift_aid';

    public function get_form_editor_field_title(): string
    {
        return esc_attr__('Gift aid field', 'txtdomain');
    }

    public function get_form_editor_button(): array
    {
        return [
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        ];
    }

    public function get_form_editor_field_settings(): array
    {
        return [
            'label_setting',
            'description_setting',
            'rules_setting',
            'error_message_setting',
            'css_class_setting',
            'conditional_logic_field_setting'
        ];
    }

    public function is_value_submission_array(): bool
    {
        return true;
    }

    public function get_field_input($form, $value = '', $entry = null)
    {
        $id = (int) $this->id;
        $giftaidImage = GIFT_AID_URI . '/assets/giftaid.svg';

        ob_start();
        ?>
        <div class="gift-box-wrapper">
            <div class="gift-aid-logo mb-2">
                <img src="<?php echo $giftaidImage; ?>" alt="GiftAid logo">
            </div>
            <div class="description text-primary font-medium text-20px mb-2">
                <p><?php echo $this->get_calculated_gift(); ?></p>
            </div>
            <div class="gift-box-form-wrapper">
                <div class="ginput_container ginput_container_checkbox mb-6">
                    <div class="gfield_checkbox" id="input_<?php echo $id; ?>">
                        <div class="gchoice gchoice_<?php echo $id; ?>">
                            <input
                                class="gfield-choice-input"
                                id="gift-check-<?php echo $id; ?>"
                                name="gift-check-<?php echo $id; ?>"
                                type="checkbox"
                                value="1"
                            >
                            <label for="gift-check-<?php echo $id; ?>"><?php echo $this->label; ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="details-description"><?php echo $this->description; ?></div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function get_field_content( $value, $force_frontend_label, $form ) {
        $form_id         = $form['id'];
        $admin_buttons   = $this->get_admin_buttons();
        $is_entry_detail = $this->is_entry_detail();
        $is_form_editor  = $this->is_form_editor();
        $is_admin        = $is_entry_detail || $is_form_editor;
        $field_label     = $this->get_field_label($force_frontend_label, $value);
        $field_id        = $is_admin || $form_id == 0 ? "input_{$this->id}" : 'input_' . $form_id . "_{$this->id}";
        $field_content   = ! $is_admin ? '{FIELD}' : $field_content = sprintf("%s<label class='gfield_label
gform-field-label' for='%s'>%s</label>{FIELD}", $admin_buttons, $field_id, esc_html($field_label));

        return $field_content;
    }

    public function get_calculated_gift(): string
    {
        $calculationText = 'With Gift Aid, your donation of {{ donation }} would be worth {{ calculated_gift_aid }}
        at no extra cost to you.';
        $donation = (isset($_GET['amount']) ? sanitize_text_field(wp_unslash($_GET['amount'])) : 0);
        $amount = ($donation * 25) / 100;
        $totalAmt = $donation + $amount;
        $displayNum = number_format((float) $totalAmt, 2, '.', '');
        $searchReplace = [
            '{{ donation }}' => "<span class=\"gform_donation_total\">£{$donation}</span>",
            '{{ calculated_gift_aid }}' => "<strong>£<span class=\"gform_donation_gifttotal\">{$displayNum}
</span></strong>",
        ];

        return str_replace(
            array_keys($searchReplace),
            array_values($searchReplace),
            $calculationText,
        );
    }
}
