let blockCount = 0;

function openMediaUploader(e, index) {
  e.preventDefault();

  const uploader = wp.media({
    title: "Выберите изображение",
    button: {
      text: "Выбрать",
    },
    multiple: false,
  });

  uploader.on("select", () => {
    const attachment = uploader.state().get("selection").first().toJSON();

    const hiddenInput = document.querySelectorAll('input[name="_image[]"]')[
      index
    ];
    if (hiddenInput) {
      hiddenInput.value = attachment.id;
    }

    const imagePreview = document.querySelectorAll(
      "#image-text-container .image-text-block img",
    )[index];
    if (imagePreview) {
      imagePreview.src = attachment.url;
    }
  });

  uploader.open();
}

function addNewBlock() {
  const container = document.getElementById("image-text-container");
  const newBlock = `
		<div class="image-text-block">
			<h4>Блок №${blockCount + 1}</h4>
			<label for="_image_${blockCount}">Изображение:</label><br/>
			<img src="${themeData.placeholder}" style="max-width:100px; background: darkgrey;" alt="Превью изображения" /><br/>
			<input type="hidden" name="_image[]" value="" />
			<button type="button" onclick="openMediaUploader(event, ${blockCount})">Выбрать изображение</button><br/><br/>
			<label for="_text_${blockCount}">Текст:</label><br/>
			<textarea rows="4" cols="50" name="_text[]"></textarea>
			<hr/>
			<button type="button" class="delete-block-button" onclick="deleteBlock(this)">Удалить блок</button>
		</div>
    `;
  container.insertAdjacentHTML("beforeend", newBlock);
  blockCount++;
}

function deleteBlock(button) {
  const block = button.closest(".image-text-block");
  block.remove();
  updateBlockNumbers();
}

function updateBlockNumbers() {
  const blocks = document.querySelectorAll(".image-text-block");
  blocks.forEach((block, index) => {
    const heading = block.querySelector("h4");
    if (heading) {
      heading.textContent = `Блок №${index + 1}`;
    }

    const hiddenInput = block.querySelector('input[name="_image[]"]');
    if (hiddenInput) {
      hiddenInput.id = `_image_${index}`;
    }

    const textArea = block.querySelector('textarea[name="_text[]"]');
    if (textArea) {
      textArea.id = `_text_${index}`;
    }

    const button = block.querySelector('button[onclick^="openMediaUploader"]');
    if (button) {
      button.setAttribute("onclick", `openMediaUploader(event, ${index})`);
    }
  });

  blockCount = blocks.length;
}
