$(document).ready(function () {

    // Masque pour le téléphone
    $('.txtTelephone').mask('000-000-0000', { placeholder: '___-___-____' });

    // Pour le code Postal
    $('.txtCodePostal').mask('ANA NAN', {
        placeholder: '___-___',
        translation: {
            A: { pattern: /[a-zA-Z]/ },
            N: { pattern: /[0-9]/ }
        }

    });

    $('.txtCodePostal').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

    const registrationForm = document.querySelectorAll('.needs-validation-register');

    addValidationToForm(registrationForm);
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