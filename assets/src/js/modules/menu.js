const menu = () => {
  const menu = document.querySelector('.mobil_menu');
  const menuToggle = document.querySelector('.header__menu--mobile');

  if (!menu || !menuToggle) {
    console.warn('Menu or Menu Toggle is not found in the DOM');
    return;
  }

  // Функция для открытия/закрытия меню
  const toggleMenu = () => {
    menu.classList.toggle('active-menu');
  };

  // Закрыть меню
  const closeMenu = () => {
    menu.classList.remove('active-menu');
  };

  // Открытие/закрытие меню при клике на кнопку
  menuToggle.addEventListener('click', (e) => {
    e.stopPropagation(); // Останавливаем всплытие события
    toggleMenu();
  });

  // Закрытие меню при клике вне его области
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.mobil_menu')) {
      closeMenu();
    }
  });

  // Закрытие меню при клике на элементы внутри меню
  menu.addEventListener('click', (e) => {
    if (e.target.matches('ul>li>a') || e.target.classList.contains('close-button')) {
      closeMenu();
    }
  });
};

export default menu;
