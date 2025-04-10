document.addEventListener('DOMContentLoaded', () => {
    // Select the sidebar and buttons
    const sidebar = document.querySelector('.sidebar');
    const toggleSidebarButton = document.querySelector('.toggle-sidebar');
    const mobileMenuButton = document.querySelector('.mobile-menu-button');

    // Minimize/Expand sidebar for desktop
    toggleSidebarButton?.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
    });

    // Toggle sidebar visibility on mobile
    mobileMenuButton.addEventListener('click', () => {
      sidebar.classList.toggle('show');
    });
  });