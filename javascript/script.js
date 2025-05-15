function toggleDropdown() {
    const menu = document.getElementById('dropdown-menu');
    if (menu) {
      menu.classList.toggle('hidden');
    }
  }
  
document.addEventListener('DOMContentLoaded', function () {
  const avatar = document.getElementById('profile-avatar');
  const dropdown = document.getElementById('dropdown-menu');

  if (!avatar || !dropdown) return;

  // Show/hide on click
  avatar.addEventListener('click', function (event) {
    event.stopPropagation();
    dropdown.classList.toggle('hidden');
  });

  // Hide when clicking outside
  document.addEventListener('click', function (event) {
    if (!dropdown.classList.contains('hidden') && !dropdown.contains(event.target)) {
      dropdown.classList.add('hidden');
    }
  });
});

function scrollRight() {
    const container = document.getElementById('scroll-container');
    if (container) {
      container.scrollBy({ left: 300, behavior: 'smooth' });
    }
  }
  
document.getElementById('video').addEventListener('change', function(e) {
    if (this.files[0].size > 50 * 1024 * 1024) {
        alert('File is too large! Max 50MB allowed');
        this.value = '';
    }
});

document.addEventListener('DOMContentLoaded', () => {
  const thumbnails = document.querySelectorAll('.gallery-thumbnail');
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightbox-img');
  const closeBtn = document.querySelector('.close-btn');

  thumbnails.forEach(thumb => {
    thumb.addEventListener('click', () => {
      lightboxImg.src = thumb.dataset.src || thumb.src;
      lightbox.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
  });

  closeBtn.addEventListener('click', () => {
    lightbox.style.display = 'none';
    document.body.style.overflow = 'auto';
  });

  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) {
      lightbox.style.display = 'none';
      document.body.style.overflow = 'auto';
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      lightbox.style.display = 'none';
      document.body.style.overflow = 'auto';
    }
  });
});