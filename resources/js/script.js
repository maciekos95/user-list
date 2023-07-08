const languageButtons = document.querySelectorAll('.lang-button');
const closeButtons = document.querySelectorAll('.close-button');
const form = document.getElementById('user-form');

languageButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        let language = this.getAttribute('data-lang');
        let request = new XMLHttpRequest();
        request.open('POST', '/set-language', true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.onload = function() {
            if (request.status == 200) {
                location.reload();
            }
        };
        let data = 'language=' + encodeURIComponent(language);
        request.send(data);
    });
});

closeButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        let messageContainer = button.parentNode;
        messageContainer.style.display = 'none';
    });
});

if (form) {
    let formData = JSON.parse(form.dataset.formData);
    let inputs = form.querySelectorAll('input');

    inputs.forEach(function(input) {
        let fieldName = input.name;
        let nestedFields = fieldName.split('[');
        let value = formData;

        nestedFields.forEach(function(field) {
            field = field.replace(']', '');
            if (value.hasOwnProperty(field)) {
                value = value[field];
            } else {
                value = '';
                return;
            }
        });

        input.value = value;
    });
}

function showConfirmationDialog(id) {
    let dialog = document.getElementById('confirmation-dialog');
    let modalOverlay = document.querySelector('.modal-overlay');
    dialog.style.display = 'block';
    dialog.dataset.id = id;
    modalOverlay.classList.add('active');
}

function closeConfirmationDialog() {
    let dialog = document.getElementById('confirmation-dialog');
    let modalOverlay = document.querySelector('.modal-overlay');
    dialog.style.display = 'none';
    modalOverlay.classList.remove('active');
}

function confirmDelete() {
    let dialog = document.getElementById('confirmation-dialog');
    let id = dialog.dataset.id;
    let request = new XMLHttpRequest();
    request.open('DELETE', '/users/delete/' + id, true);
    request.onload = function() {
        if (request.status == 200) {
            location.reload();
        }
    };
    request.send();
}

function cancelForm(event) {
    event.preventDefault();
    window.location.href = '/';
}