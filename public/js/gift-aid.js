document.addEventListener("DOMContentLoaded", () => {
  window.gform.addAction('gform_input_change', function(elem) {
    if (! (elem instanceof HTMLElement) || ! elem.classList.contains('ginput_amount')) {
      return;
    }

    const parentForm = elem.closest('form');
    if (! (parentForm instanceof HTMLFormElement)) {
      return;
    }

    var spanTotal = parentForm.querySelectorAll('.gform_donation_total');
    if (! (spanTotal instanceof NodeList) || 0 === spanTotal.length) {
      return;
    }

    var spanTotalGift = parentForm.querySelectorAll('.gform_donation_gifttotal');
    if (! (spanTotalGift instanceof NodeList) || 0 === spanTotalGift.length) {
      return;
    }

    var val = elem.value.replace(/[$€£]/g, '');
    var total = parseFloat(val).toFixed(2);
    var totalGift = parseFloat(val * 1.25).toFixed(2);

    Array.from(spanTotal).forEach(function (item) {
      item.innerHTML = total;
    });

    Array.from(spanTotalGift).forEach(function (item) {
      item.innerHTML = totalGift;
    });
  }, 10);
});
