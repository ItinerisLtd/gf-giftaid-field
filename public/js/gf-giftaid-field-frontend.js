/**
 * The donation total object.
 * @typedef {Object} Donation
 * @property {string} donation - The amount donated as a string.
 * @property {string} giftAidTotal - The amount including taxback as a string.
 */

document.addEventListener('DOMContentLoaded', () => {
  window.gform.addAction('gform_input_change', updateGFFromTotal, 10);
});

function updateGFFromTotal(elem) {
  const total = getTotalAmount(elem);
  if (! total || !total.donation || !total.giftAidTotal) {
    return;
  }

  updateGiftAidDisplay(total);
}

/**
 * Gets the total amount donated and updates the Gift Aid total.
 * @param {HTMLElement} elem
 * @returns {Donation} The total amount donated.
 */
function getTotalAmount(elem) {
  if (! (elem instanceof HTMLInputElement) || ! elem.classList.contains('ginput_total')) {
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
