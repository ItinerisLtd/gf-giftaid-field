document.addEventListener('DOMContentLoaded', initGiftAid);

/**
 * Updates the gift aid display, based on a chosen field changing.
 */
function initGiftAid() {
  const gravityForm = document.querySelector(".gform_wrapper");
  if (!(gravityForm instanceof HTMLElement)) {
    return;
  }
  const totalSelector = totalFieldSelector(gravityForm, ".ginput_amount");
  if (!totalSelector) {
    return;
  }
  window.gform.addAction("gform_input_change", function (elem) {
      if (!(elem instanceof HTMLInputElement) || !elem.closest(totalSelector) || !elem.value) {
        return;
      }
      const regex = new RegExp('[^0-9.]', 'g');
      const sanitizedValue = elem.value.replace(regex, '');
      const donationValue = parseFloat(sanitizedValue);
      const giftAidValue = donationValue * 1.25;
      if (!donationValue || !giftAidValue) {
        return;
      }
      const donationRounded = donationValue.toFixed(2);
      const giftAidRounded = giftAidValue.toFixed(2);
      if (!donationRounded || !giftAidRounded) {
        return;
      }
      updateGiftAidDisplay(gravityForm, donationRounded, giftAidRounded);
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
  if (!(giftAidComponent instanceof HTMLElement)) {
    return fallback;
  }
  const selectedPriceFieldId = giftAidComponent.dataset.selectedPriceFieldId;
  if (!selectedPriceFieldId) {
    return fallback;
  }
  return `#${selectedPriceFieldId}`;
}

/**
 * Updates the gift aid display.
 * @param {HTMLElement} gravityForm The form element.
 * @param {object} The donation and gift aid total.
 * @returns {void}
 */
function updateGiftAidDisplay(gravityform, donation, giftAidAmount) {
  if (!donation || !giftAidAmount || !(gravityform instanceof HTMLElement)) {
    return;
  }
  const spanTotal = gravityform.querySelector('.gform_donation_total');
  if (!(spanTotal instanceof HTMLSpanElement)) {
    return;
  }
  const spanTotalGift = gravityform.querySelector('.gform_donation_gifttotal');
  if (!(spanTotalGift instanceof HTMLSpanElement)) {
    return;
  }
  spanTotal.innerHTML = donation;
  spanTotalGift.innerHTML = giftAidAmount;
}
