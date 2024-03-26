document.addEventListener('DOMContentLoaded', () => {
  initGiftAid();
});

/**
 * Updates the gift aid display, based on a chosen field changing.
 */
function initGiftAid() {
  const gravityForm = document.querySelector('.gform_wrapper');
  if (! (gravityForm instanceof HTMLElement)) {
    return;
  }
  const totalSelector = totalFieldSelector(gravityForm, '.ginput_amount');
  if (!totalSelector) {
    return;
  }
  window.gform.addAction('gform_input_change', (elem) => {
    const total = getTotalAmount(elem, totalSelector);
    if (! total || !total.donation || !total.giftAidTotal) {
      return;
    }
    updateGiftAidDisplay(gravityForm, total);
  }, 10);
}

/**
 * Gets the selector of the total field, selected in the gift aid field settings.
 * Will be in the format 'field_<form_id>_<field_id>' and will be an id so the selector will be prefixed by #.
 * @param {HTMLElement} gravityForm The form element.
 * @param {string} fallback fallback selector if the field is not found.
 * @returns {string} The class of the total field.
 */
function totalFieldSelector(gravityForm, fallback) {
  const giftAidComponent = gravityForm.querySelector('.gift-box-wrapper');
  if (! (giftAidComponent instanceof HTMLElement)) {
    return fallback;
  }
  const selectedPriceFieldId = giftAidComponent.dataset.selectedPriceFieldId;
  if (!selectedPriceFieldId) {
    return fallback;
  }
  return `#${selectedPriceFieldId}`;
}

/**
 * Gets the total amount donated and updates the Gift Aid total.
 * @param {HTMLElement} elem The element that has changed.
 * @param {string} totalSelector selector of the chosen total field.
 * @returns {object} The donation and gift aid total.
 */
function getTotalAmount(elem, totalSelector) {
  if (! (elem instanceof HTMLInputElement) || ! elem.closest(totalSelector)) {
    return '';
  }
  const totalValueStr = elem.value;
  const totalValueFloat = parseFloat(totalValueStr);
  if (! totalValueFloat) {
    return '';
  }
  const giftAidAmount = totalValueFloat * 1.25;

  return {
    donation: totalValueFloat.toFixed(2),
    giftAidTotal: giftAidAmount.toFixed(2)
  };
}

/**
 * Updates the gift aid display.
 * @param {HTMLElement} gravityForm The form element.
 * @param {object} The donation and gift aid total.
 * @returns {void}
 */
function updateGiftAidDisplay(gravityform, total) {
  if (!total || !total.donation || !total.giftAidTotal) {
    return;
  }
  const spanTotal = gravityform.querySelector('.gform_donation_total');
  if (! (spanTotal instanceof HTMLSpanElement)) {
    return;
  }
  const spanTotalGift = gravityform.querySelector('.gform_donation_gifttotal');
  if (! (spanTotalGift instanceof HTMLSpanElement)) {
    return;
  }
  spanTotal.innerHTML = total.donation;
  spanTotalGift.innerHTML = total.giftAidTotal;
}
