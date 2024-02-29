document.addEventListener('DOMContentLoaded', () => {
  updateGiftAidTotal();
});

function updateGiftAidTotal() {
  console.log( window.gform);
  window.gform.addAction('gform_input_change', updateGFFromTotal, 10);
}

function updateGFFromTotal(elem) {
  const total = getTotalAmount(elem);
  if (! total) return;

  updateGiftAidDisplay(total);
}

/**
 * Gets the total amount donated and updates the Gift Aid total.
 * @param {HTMLElement} elem
 * @returns {string} The total amount donated.
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
  const roundedTwoDP = totalValueFloat.toFixed(2);
  if (! roundedTwoDP) {
    return '';
  }
  return roundedTwoDP;
}

/**
 * updates the gift aid display.
 * @param {string} total
 */
function updateGiftAidDisplay(total) {
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
  spanTotal.innerHTML = total;

  const totalGift = parseFloat(total * 1.25).toFixed(2);
  spanTotalGift.innerHTML = totalGift;
}
