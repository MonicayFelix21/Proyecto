// resources/js/modal.js

document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('playModal');
  if (!modalEl) return;

  const bsModal = new bootstrap.Modal(modalEl);

  document.querySelectorAll('.song-card .play-button').forEach(btn => {
    btn.addEventListener('click', () => {
      const card   = btn.closest('.song-card'),
            imgSrc = card.querySelector('img').src;
      document.getElementById('playModalImg').src = imgSrc;
      bsModal.show();
    });
  });
});
