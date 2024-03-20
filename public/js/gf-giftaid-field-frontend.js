/**
 * The donation total object.
 * @typedef {Object} Donation
 * @property {string} donation - The amount donated as a string.
 * @property {string} giftAidTotal - The amount including taxback as a string.
 */

document.addEventListener('DOMContentLoaded', () => {
  initGiftAid();
});

/**
 * Updates the gift aid display, based on a chosen field changing.
 */
function initGiftAid() {
  const totalSelector = totalFieldSelector() || '.ginput_amount';
  window.gform.addAction('gform_input_change', (elem) => {
    const total = getTotalAmount(elem, totalSelector);
    if (! total || !total.donation || !total.giftAidTotal) {
      return;
    }
    updateGiftAidDisplay(total);
  }, 10);
}

/**
 * Gets the selector of the total field, selected in the gift aid field settings.
 * Will be in the format 'field_<form_id>_<field_id>' and will be an id so the selector will be prefixed by #.
 * @returns {string} The class of the total field.
 */
function totalFieldSelector() {
  const giftAidComponent = document.querySelector('.gift-box-wrapper');
  if (! (giftAidComponent instanceof HTMLElement)) {
    return '';
  }
  const donationTotalInput = giftAidComponent.querySelector('input.donation-total-select');
  if (!(donationTotalInput instanceof HTMLInputElement)) {
    return '';
  }
  const fieldId = donationTotalInput.value;
  if (!fieldId) {
    return '';
  }
  return `#${fieldId}`;
}

/**
 * Gets the total amount donated and updates the Gift Aid total.
 * @param {HTMLElement} elem The element that has changed.
 * @param {string} totalSelector selector of the chosen total field.
 * @returns {Donation} The total amount donated.
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
 * @param {Donation} total
 * @returns {void}
 */
function updateGiftAidDisplay(total) {
  if (!total || !total.donation || !total.giftAidTotal) {
    return;
  }
  const gravityForm = document.querySelector('.gform_wrapper');
  if (! (gravityForm instanceof HTMLElement)) {
    return;
  }
  const spanTotal = gravityForm.querySelector('.gform_donation_total');
  if (! (spanTotal instanceof HTMLSpanElement)) {
    return;
  }
  const spanTotalGift = gravityForm.querySelector('.gform_donation_gifttotal');
  if (! (spanTotalGift instanceof HTMLSpanElement)) {
    return;
  }
  spanTotal.innerHTML = total.donation;
  spanTotalGift.innerHTML = total.giftAidTotal;
}
