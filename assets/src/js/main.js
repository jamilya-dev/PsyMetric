import menu from './modules/menu';
import forms from './modules/forms';

const App = {
  init() {
    menu();
    forms({ formId: 'form__contact' });
  },
};
document.addEventListener('DOMContentLoaded', () => {
  App.init();
});
