//Provient d'ici
//https://symfony.com/doc/current/form/form_collections.html#allowing-new-tags-with-the-prototype
$(document).ready(function () {
  const formCategories = document.querySelectorAll('.needs-validation-categorie');

  addValidationToForm(formCategories);
});

function addValidationToForm(forms) {
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    });
}

const addFormToCollection = (e) => {
  const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

  const item = document.createElement('li');

  item.innerHTML = collectionHolder
    .dataset
    .prototype
    .replace(
      /__name__/g,
      collectionHolder.dataset.index
    );

  collectionHolder.appendChild(item);

  collectionHolder.dataset.index++;
};

document
  .querySelectorAll('.add_item_link')
  .forEach(btn => {
    btn.addEventListener("click", addFormToCollection)
  });