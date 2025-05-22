document.addEventListener('DOMContentLoaded', () => {
  console.log('homepage.js loaded');

  // --- Profile Dropdown ---
  const avatar = document.getElementById('profile-avatar');
  const dropdown = document.getElementById('dropdown-menu');


  console.log('👤 avatar:', avatar);
  console.log('⬇️ dropdown:', dropdown);
  
  if (avatar && dropdown) {
    avatar.addEventListener('click', (event) => {
      event.stopPropagation();
      dropdown.classList.toggle('hidden'); // <-- revert to 'hidden'
    });
  
    document.addEventListener('click', (event) => {
      if (!dropdown.contains(event.target) && !avatar.contains(event.target)) {
        dropdown.classList.add('hidden'); // <-- revert to 'hidden'
      }
    });
  }

  // --- Horizontal Scroll for Services ---
  const scrollContainer = document.getElementById('scroll-container');
  window.scrollRight = function () {
    if (scrollContainer) {
      scrollContainer.scrollBy({ left: 300, behavior: 'smooth' });
    }
  };

  // --- Video Upload Limit ---
  const videoInput = document.getElementById('video');
  if (videoInput) {
    videoInput.addEventListener('change', function () {
      if (this.files[0].size > 50 * 1024 * 1024) {
        alert('File is too large! Max 50MB allowed');
        this.value = '';
      }
    });
  }

  // --- Lightbox Image Viewer ---
  const thumbnails = document.querySelectorAll('.gallery-thumbnail');
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightbox-img');
  const closeBtn = document.querySelector('.close-btn');

  if (lightbox && lightboxImg && closeBtn) {
    thumbnails.forEach((thumb) => {
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
  }

  // --- Auto-submit Filters ---
  const form = document.getElementById('filterForm');
  const searchInput = document.querySelector('input[name="search"]');

  if (form) {
    let debounceTimer;

    if (searchInput) {
      searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
          form.submit();
        }, 600);
      });
    }

    ['categoryFilter', 'priceFilter', 'ratingFilter'].forEach((id) => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('change', () => {
          form.submit();
        });
      }
    });
  }
});
