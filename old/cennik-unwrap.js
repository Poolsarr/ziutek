document.addEventListener('DOMContentLoaded', function () {
  const pricingLink = document.querySelector('[href="#pricingContainer"]');

  if (pricingLink) {
    pricingLink.addEventListener('click', function() {
      const target = document.getElementById('pricingContainer');
      
      setTimeout(function() {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 350);
    });
  }
});
