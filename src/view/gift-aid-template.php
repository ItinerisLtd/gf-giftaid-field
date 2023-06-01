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
                        name="gift-check-<?php echo esc_attr($id); ?>"
                        type="checkbox"
                        value="1"
                    >
                    <label for="gift-check-<?php echo esc_attr($id); ?>">
                        <?php echo esc_html($this->label); ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="details-description"><?php echo esc_html($this->description); ?></div>
</div>
