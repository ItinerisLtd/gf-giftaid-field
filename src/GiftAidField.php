<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use GF_Field;

class GiftAidField extends GF_Field
{
    public string $type = 'gift_aid';

    public function get_form_editor_field_title(): string
    {
        return esc_attr__('Gift aid field', 'itineris-gf-giftaid-field');
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
            'conditional_logic_field_setting',
            'checkbox_label_setting',
        ];
    }

    public function is_value_submission_array(): bool
    {
        return true;
    }

    /**
     * @param array        $form
     * @param string|array $value
     * @param null|array   $entry
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
    public function get_field_input($form, $value = '', $entry = null): string
    {
        $id = (int) $this->id;
        $giftaidImage = ITINERIS_GF_GIFT_AID_FIELD_URI . '/public/img/giftaid.svg';

        ob_start();
        ?>
        <div class="gift-box-wrapper bg-gray-50 rounded-br-4 p-7.5">
            <div class="gift-aid-logo mb-2">
                <img src="<?php echo esc_url($giftaidImage); ?>" alt="GiftAid logo">
            </div>
            <div class="description text-primary font-medium text-xl mb-2">
                <?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo wpautop(wp_kses_post($this->get_calculated_gift())); ?>
            </div>
            <div class="gift-box-form-wrapper my-6 pb-6 border-b border-b-gray-200">
                <div class="ginput_container ginput_container_checkbox mb-6">
                    <div class="gfield_checkbox" id="input_<?php echo esc_attr($id); ?>">
                        <div class="gchoice gchoice_<?php echo esc_attr($id); ?>">
                            <input
                                class="gfield-choice-input"
                                id="gift-check-<?php echo esc_attr($id); ?>"
                                name="input_<?php echo esc_attr($id); ?>"
                                type="checkbox"
                                value="Yes"
                            >
                            <label for="gift-check-<?php echo esc_attr($id); ?>">
                                <?php echo wp_kses_post($this->checkboxLabel); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="details-description"><?php echo wp_kses_post($this->description); ?></div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * @param string|array $value
     * @param bool         $force_frontend_label
     * @param array        $form
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
    public function get_field_content($value, $force_frontend_label, $form): string
    {
        $form_id = $form['id'];
        $admin_buttons = $this->get_admin_buttons();
        $is_entry_detail = $this->is_entry_detail();
        $is_form_editor = $this->is_form_editor();
        $is_admin = $is_entry_detail || $is_form_editor;
        $field_label = $this->get_field_label($force_frontend_label, $value);
        $field_id = ($is_admin || 0 === $form_id) ? "input_{$this->id}" : "input_{$form_id}_{$this->id}";

        if ($is_admin) {
            return sprintf(
                '%s<label class="gfield_label gform-field-label" for="%s">%s</label>{FIELD}',
                $admin_buttons,
                $field_id,
                esc_html($field_label),
            );
        }

        return '{FIELD}';
    }

    public function get_calculated_gift(): string
    {
        // phpcs:ignore Generic.Files.LineLength.TooLong
        $calculationText = 'With Gift Aid, your donation of {{ donation }} would be worth {{ calculated_gift_aid }} at no extra cost to you.';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $donation = absint($_GET['amount'] ?? 0);
        $amount = ($donation * 25) / 100;
        $totalAmt = $donation + $amount;
        $displayNum = number_format((float) $totalAmt, 2, '.', '');
        $searchReplace = [
            '{{ donation }}' => "<span class=\"gform_donation_total\">£{$donation}</span>",
            // phpcs:ignore Generic.Files.LineLength.TooLong
            '{{ calculated_gift_aid }}' => "<strong>£<span class=\"gform_donation_gifttotal\">{$displayNum}</span></strong>",
        ];

        return str_replace(
            array_keys($searchReplace),
            array_values($searchReplace),
            $calculationText,
        );
    }

    public function sanitize_settings(): void
    {
        parent::sanitize_settings();
        $this->checkboxLabel = $this->maybe_wp_kses($this->checkboxLabel);
    }
}
