function gfGiftAidOnInputChange(elem) {
  if (!(elem instanceof HTMLElement)) {
    return;
  }

  const wrapperField = elem.closest('.gfield');
  if (!(wrapperField instanceof HTMLElement)) {
    return;
  } else {
    if (
      !wrapperField.classList.contains('giftaid-value') &&
      !elem.classList.contains('ginput_amount')
    ) {
      return;
    }
  }

  const parentForm = elem.closest('form');
  if (!(parentForm instanceof HTMLFormElement)) {
    return;
  }

  const spanTotal = parentForm.querySelectorAll('.gform_donation_total');
  if (!(spanTotal instanceof NodeList) || 0 === spanTotal.length) {
    return;
  }

  const spanTotalGift = parentForm.querySelectorAll(
    '.gform_donation_gifttotal',
  );
  if (!(spanTotalGift instanceof NodeList) || 0 === spanTotalGift.length) {
    return;
  }

  const extractFloat = (str) => {
    // Regular expression to match floating-point numbers
    const regex = /[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?/g;

    // Use match() to find all matching numbers in the string
    const matches = str.match(regex);

    // If there are matches, convert them to floats and return them; otherwise, return the original str.
    return matches ? matches.map(Number) : str;
  };

  const val = extractFloat(elem.value);
  const total = parseFloat(val).toFixed(2);
  const totalGift = parseFloat(val * 1.25).toFixed(2);

  Array.from(spanTotal).forEach((item) => {
    item.innerHTML = total;
  });

  Array.from(spanTotalGift).forEach((item) => {
    item.innerHTML = totalGift;
  });
}
document.addEventListener('DOMContentLoaded', () => {
  window.gform.addAction('gform_input_change', gfGiftAidOnInputChange, 10);
});
