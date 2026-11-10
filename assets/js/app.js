document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', () => {
      const button = form.querySelector('button[type="submit"]');
      if (button && form.checkValidity()) {
        button.dataset.originalText = button.textContent;
        button.textContent = 'Traitement…';
        button.disabled = true;
      }
    });
  });
});
