<?php

declare(strict_types=1);

namespace Itineris\GfGiftaidField;

use GF_Field;
use GFFormsModel;

class GiftAidField extends GF_Field
{
    public string $type = 'gift_aid';

    public function get_form_editor_field_title(): string
    {
        return esc_attr__('Gift aid field', 'itineris-gf-giftaid-field');
    }

    public function get_form_editor_field_description(): string
    {
        return esc_attr__(
            'Offers a “yes/no” checkbox to allow a user to give permission for you to claim GiftAid on their behalf.',
            'itineris-gf-giftaid-field',
        );
    }

    public function get_form_editor_field_icon(): string
    {
        return 'gform-icon--consent';
    }

    public function get_form_editor_button(): array
    {
        return [
            'group' => 'pricing_fields',
            'text'  => $this->get_form_editor_field_title(),
        ];
    }

    public function get_form_editor_field_settings(): array
    {
        return [
            'selected_price_field_setting',
            'label_setting',
            'label_placement_setting',
            'admin_label_setting',
            'description_setting',
            'rules_setting',
            'error_message_setting',
            'css_class_setting',
            'conditional_logic_field_setting',
            'checkbox_label_setting',
        ];
    }

    /**
     * @param array        $form
     * @param string|array $value
     * @param ?array       $entry
     *
     * @return string
     */
    public function get_field_input($form, $value = '', $entry = null): string
    {
        $id = (int) $this->id;
        $is_form_editor  = $this->is_form_editor();
        $target_input_id = $this->get_first_input_id($form);
        $extra_describedby_ids = [];
        if (! empty($this->description)) {
            $extra_describedby_ids = ["gfield_giftaid_description_{$form['id']}_{$this->id}"];
        }
        $aria_describedby = $this->get_aria_describedby($extra_describedby_ids);

        $giftaidImage = plugins_url('public/img/giftaid.svg', __DIR__);
        $selectedPriceField = $this->selectedPriceField ?? '';

        ob_start();
        ?>
        <div
            class="ginput_container ginput_container_giftaid bg-gray-50 rounded-br-4 p-7.5"
            <?php if (!empty($selectedPriceField)) : ?>
            data-selected-price-field-id="<?php echo esc_attr($selectedPriceField); ?>"
            <?php endif; ?>
        >
            <div class="giftaid-logo mb-2">
                <img src="<?php echo esc_url($giftaidImage); ?>" alt="GiftAid logo" loading="lazy">
            </div>

            <?php if (! empty($this->description) && $this->is_description_above($form)) : ?>
                <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $this->get_description($this->description, 'gfield_description'); ?>
            <?php endif; ?>

            <div class="description text-primary font-medium text-xl mb-2">
                <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo wpautop(wp_kses_post($this->get_calculated_gift())); ?>
            </div>

            <div class="gfield_giftaid gift-box-form-wrapper my-6 pb-6 border-b border-b-gray-200">
                <input
                    id="<?php echo esc_attr($target_input_id); ?>"
                    name="input_<?php echo esc_attr($id); ?>"
                    type="checkbox"
                    value="1"
                    <?php if (! $is_form_editor) : ?>
                        <?php checked('1', $value); ?>
                    <?php endif; ?>
                    <?php if ($is_form_editor) : ?>
                        disabled="disabled"
                    <?php endif; ?>
                    <?php echo esc_html($this->get_tabindex()); ?>
                    <?php if (! empty($aria_describedby)) : ?>
                        <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php echo $aria_describedby; ?>
                    <?php endif; ?>
                >
                <label
                    class="gform-field-label gform-field-label--type-inline gfield_giftaid_label"
                    <?php if (! empty($target_input_id)) : ?>
                        for="<?php echo esc_attr($target_input_id); ?>"
                    <?php endif; ?>
                >
                    <?php echo wp_kses_post($this->checkboxLabel); ?>
                </label>
            </div>

            <?php if (! empty($this->description) && ! $this->is_description_above($form)) : ?>
                <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $this->get_description($this->description, 'gfield_description'); ?>
            <?php endif; ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * @param string|array $value
     * @param bool         $force_frontend_label
     * @param array        $form
     */
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
        $calculationText = 'With Gift Aid, your donation of {{ donation }} would be worth {{ calculated_giftaid }} at no extra cost to you.';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $donation = absint($_GET['amount'] ?? 0);
        $amount = ($donation * 25) / 100;
        $totalAmt = $donation + $amount;
        $displayNum = number_format((float) $totalAmt, 2, '.', '');
        $searchReplace = [
            '{{ donation }}' => "<span>£<span class=\"gform_donation_total\">{$donation}</span></span>",
            // phpcs:ignore Generic.Files.LineLength.TooLong
            '{{ calculated_giftaid }}' => "<strong>£<span class=\"gform_donation_gifttotal\">{$displayNum}</span></strong>",
        ];

        return str_replace(
            array_keys($searchReplace),
            array_values($searchReplace),
            $calculationText,
        );
    }

    protected function isValueYes(string $value): bool
    {
        return in_array(strtolower($value), ['1', 'yes'], true);
    }

    /**
     * @param string|array $value    The field value.
     * @param array        $entry    The Entry Object currently being processed.
     * @param string       $field_id The field or input ID currently being processed.
     * @param array        $columns  The properties for the columns being displayed on the entry list page.
     * @param array        $form     The Form Object currently being processed.
     *
     * @return string
     */
    public function get_value_entry_list( $value, $entry, $field_id, $columns, $form ): string
    {
        if ($this->isValueYes($value)) {
            return esc_html__('Yes', 'itineris-gf-giftaid-field');
        }

        return esc_html__('No', 'itineris-gf-giftaid-field');
    }

    /**
     * @phpcs:disable Generic.Files.LineLength.TooLong
     *
     * @param string|array $value    The field value.
     * @param string       $currency The entry currency code.
     * @param bool         $use_text When processing choice based fields should the choice text be returned instead of the value.
     * @param string       $format   The format requested for the location the merge is being used. Possible values: html, text or url.
     * @param string       $media    The location where the value will be displayed. Possible values: screen or email.
     *
     * @return string
     */
    public function get_value_entry_detail($value, $currency = '', $use_text = false, $format = 'html', $media = 'screen'): string
    {
        // phpcs:enable Generic.Files.LineLength.TooLong
        if ($this->isValueYes($value)) {
            return esc_html__('Yes', 'itineris-gf-giftaid-field');
        }

        return esc_html__('No', 'itineris-gf-giftaid-field');
    }

    /**
     * @phpcs:disable Generic.Files.LineLength.TooLong
     *
     * @param array      $entry    The entry currently being processed.
     * @param string     $input_id The field or input ID.
     * @param bool|false $use_text When processing choice based fields should the choice text be returned instead of the value.
     * @param bool|false $is_csv   Is the value going to be used in the .csv entries export.
     *
     * @return string|array
     */
    public function get_value_export($entry, $input_id = '', $use_text = false, $is_csv = false): string|array
    {
        // phpcs:enable Generic.Files.LineLength.TooLong
        if (empty($input_id)) {
            return '';
        }

        $value = parent::get_value_export($entry, $input_id, $use_text, $is_csv);
        if ($this->isValueYes($value)) {
            return esc_html__('Yes', 'itineris-gf-giftaid-field');
        }

        return esc_html__('No', 'itineris-gf-giftaid-field');
    }

    public function sanitize_settings(): void
    {
        parent::sanitize_settings();
        $this->checkboxLabel = $this->maybe_wp_kses($this->checkboxLabel);
    }

    public function get_filter_settings(): array
    {
        $filter_settings = [
            'key' => $this->id,
            'text' => GFFormsModel::get_label($this),
            'preventMultiple' => false,
            'operators' => $this->get_filter_operators(),
        ];

        $values = $this->get_filter_values();
        if (! empty($values)) {
            $filter_settings['values'] = $values;
        }

        return $filter_settings;
    }

    public function get_filter_operators(): array
    {
        return ['is', 'isnot'];
    }

    public function get_filter_values(): array
    {
        return [
            [
                'value' => '1',
                'text'  => esc_html__('Yes', 'itineris-gf-giftaid-field'),
            ],
            [
                'value' => '0',
                'text'  => esc_html__('No', 'itineris-gf-giftaid-field'),
            ],
        ];
    }
}
