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
  