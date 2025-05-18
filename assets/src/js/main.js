// import slides from './modules/slides';
import menu from './modules/menu';
import forms from './modules/forms';

const App = {
  init() {
    menu();
    // slides();
    forms({ formId: 'sendform' });
  },
};
document.addEventListener('DOMContentLoaded', () => {
  App.init();
});
