const menu = () => {
  const menu = document.querySelector('.mobil_menu');
  const menuToggle = document.querySelector('.header__menu--mobile');

  if (!menu || !menuToggle) {
    console.warn('Menu or Menu Toggle is not found in the DOM');
    return;
  }

  const toggleMenu = () => {
    menu.classList.toggle('active-menu');
  };

  const closeMenu = () => {
    menu.classList.remove('active-menu');
  };

  menuToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleMenu();
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.mobil_menu')) {
      closeMenu();
    }
  });

  menu.addEventListener('click', (e) => {
    if (e.target.matches('ul>li>a') || e.target.classList.contains('close-button')) {
      closeMenu();
    }
  });
};

export default menu;
