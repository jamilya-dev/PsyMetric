const forms = ({ formId, someElem = [] }) => {
  if (document.getElementById(formId)) {
    const form = document.getElementById(formId);
    const statusBlock = document.createElement("div");
    statusBlock.classList.add("info");
    const loadText = "Загрузка...";
    const errorText = "Ошибка...";
    const successText = "Спасибо! Наш менеджер с вами свяжется";

    const sendData = (data) => {
      const formBody = new URLSearchParams();
      for (const key in data) {
        if (data.hasOwnProperty(key)) {
          formBody.append(key, data[key]);
        }
      }
      return fetch(`${themePaths.themeUrl}/send.php`, {
        method: "POST",
        body: formBody,
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
      }).then((res) => res.text());
    };
    const submitForm = () => {
      const formData = new FormData(form);
      const formBody = {};

      statusBlock.textContent = loadText;
      form.after(statusBlock);

      formData.forEach((val, key) => {
        formBody[key] = val;
      });
      someElem.forEach((elem) => {
        const element = document.getElementById(elem.id);
        if (elem.type === "block") {
          formBody[elem.id] = element.textContent;
        } else if (elem.type === "input") {
          formBody[elem.id] = element.value;
        }
      });

      sendData(formBody)
        .then((data) => {
          statusBlock.textContent = successText;
          form.reset();
          setTimeout(() => {
            statusBlock.textContent = "";
          }, 2000);
        })
        .catch((error) => {
          statusBlock.textContent = errorText;
        });
    };

    try {
      if (!form) {
        throw new Error("Верните форму на место, пожалуйста!!!");
      }
      form.addEventListener("submit", (e) => {
        e.preventDefault();

        submitForm();
      });
    } catch (error) {
      console.log(error.message);
    }
  }
};
export default forms;
