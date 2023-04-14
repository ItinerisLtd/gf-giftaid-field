document.addEventListener("DOMContentLoaded", () => {
  window.gform.addAction('gform_input_change', function(elem) {
    if (elem.classList.contains('ginput_amount')) {
      const parentForm = elem.closest('form');
      const spanTotal = parentForm.querySelectorAll('.gform_donation_total');
      const spanTotalGift = parentForm.querySelectorAll('.gform_donation_gifttotal');

      if (0 === spanTotal.length || 0 === spanTotalGift.length) {
        return;
      }

      const val = elem.value.replace(/[$€£]/g, "");
      const total = parseFloat(val).toFixed(2);
      const totalGift = parseFloat(val * 1.25).toFixed(2);

      spanTotal.forEach((item) => {
        item.innerHTML = total;
      });

      spanTotalGift.forEach((item) => {
        item.innerHTML = totalGift;
      });
    }
  }, 10);
});
