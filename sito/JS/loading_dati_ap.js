const profileCircle = document.getElementById('profileCircle');
const profileImage = document.getElementById('profileImage');
const fileInput = document.getElementById('fileInput');
const resetBtn = document.getElementById('resetBtn');

const nicknameInput = document.getElementById('nickname');
const passwordInput = document.getElementById('password');
const emailInput = document.getElementById('email');
const descriptionTextarea = document.getElementById('description');

// Mappa dei campi
const fieldsMap = {
    'nickname': nicknameInput,
    'password': passwordInput,
    'email': emailInput,
    'description': descriptionTextarea
};

let currentUsername = null;

// Funzione per caricare i dati dell'utente loggato
async function loadUserData() {
    try {
        const response = await fetch('get_user_data.php');
        const data = await response.json();

        if (data.success) {
            currentUsername = data.username;

            // Aggiorna i campi con i dati dal DB
            if (nicknameInput) nicknameInput.value = data.username;
            if (emailInput) emailInput.value = data.email;
            if (descriptionTextarea) descriptionTextarea.value = data.description || 'Descriviti! (max 500 caratteri)';

            // La password non la mostriamo mai in chiaro
            if (passwordInput) passwordInput.value = '********';
        } else {
            console.error('Errore caricamento dati:', data.message);
        }
    } catch (error) {
        console.error('Errore:', error);
    }
}

// Funzione per abilitare la modifica di un campo
function enableEditing(field, inputElement) {
    const wasReadonly = inputElement.readOnly || inputElement.disabled;

    if (wasReadonly) {
        // Abilita il campo
        inputElement.readOnly = false;
        inputElement.disabled = false;
        inputElement.classList.add('editing');
        inputElement.focus();

        // Cambia l'icona del bottone
        const editBtn = document.querySelector(`.edit-btn[data-field="${field}"]`);
        if (editBtn) {
            editBtn.innerHTML = '<img src="../assets/spunta.png" width="30" height="30">'; // Icona salva
            editBtn.style.backgroundColor = '#4CAF50';
        }

        // Gestione salvataggio
        const saveHandler = async () => {
            let newValue = inputElement.value;

            // Per la password, se è uguale a '********' non modificare
            if (field === 'password' && newValue === '********') {
                disableEditing(field, inputElement);
                return;
            }

            // Validazione base lato client
            if (field === 'email' && !isValidEmail(newValue)) {
                showTemporaryMessage('Email non valida', 'error');
                return;
            }

            if (field === 'nickname' && (newValue.length < 3 || newValue.length > 255)) {
                showTemporaryMessage('Il nickname deve essere tra 3 e 255 caratteri', 'error');
                return;
            }

            if (field === 'password' && newValue.length > 0 && newValue.length < 4) {
                showTemporaryMessage('La password deve essere di almeno 4 caratteri', 'error');
                return;
            }

            if (field === 'description' && newValue.length > 500) {
                showTemporaryMessage('La descrizione non può superare 500 caratteri', 'error');
                return;
            }

            // Invia al server
            try {
                const response = await fetch('update_profile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        field: field,
                        value: newValue
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showTemporaryMessage('Dati aggiornati con successo!', 'success');

                    // Aggiorna il valore visualizzato
                    if (field === 'password') {
                        inputElement.value = '********';
                    } else if (field === 'nickname') {
                        inputElement.value = result.newValue;
                        currentUsername = result.newValue;
                    } else {
                        inputElement.value = result.newValue;
                    }
                } else {
                    showTemporaryMessage(result.message, 'error');
                    // Ripristina il valore originale
                    await loadUserData();
                }
            } catch (error) {
                showTemporaryMessage('Errore di connessione', 'error');
            }

            disableEditing(field, inputElement);
        };

        // Rimuovi eventuali listener precedenti
        inputElement.removeEventListener('blur', saveHandler);
        inputElement.removeEventListener('keypress', blurHandler);

        // Aggiungi nuovi listener
        function blurHandler(e) {
            saveHandler();
        }

        inputElement.addEventListener('blur', blurHandler);
        inputElement.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveHandler();
            }
        });

        // Salva il riferimento per rimuovere dopo
        inputElement._saveHandler = saveHandler;

    } else {
        // Se è già in modifica, salva
        if (inputElement._saveHandler) {
            inputElement._saveHandler();
        }
    }
}

function disableEditing(field, inputElement) {
    inputElement.readOnly = true;
    inputElement.disabled = true;
    inputElement.classList.remove('editing');

    // Ripristina l'icona del bottone
    const editBtn = document.querySelector(`.edit-btn[data-field="${field}"]`);
    if (editBtn) {
        editBtn.innerHTML = '<img src="../assets/Modifica_penna.png" width="30" height="30">';
        editBtn.style.backgroundColor = '';
    }
}

function isValidEmail(email) {
    const re = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
    return re.test(email);
}

// Mostra messaggio temporaneo (versione migliorata)
function showTemporaryMessage(message, type = 'success') {
    const messageDiv = document.createElement('div');
    messageDiv.textContent = message;
    messageDiv.style.position = 'fixed';
    messageDiv.style.bottom = '20px';
    messageDiv.style.left = '50%';
    messageDiv.style.transform = 'translateX(-50%)';
    messageDiv.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
    messageDiv.style.color = 'white';
    messageDiv.style.padding = '10px 20px';
    messageDiv.style.borderRadius = '5px';
    messageDiv.style.zIndex = '1000';
    messageDiv.style.fontSize = '14px';
    messageDiv.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';

    document.body.appendChild(messageDiv);

    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Event listener per tutti i pulsanti edit
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const field = btn.getAttribute('data-field');
        const inputElement = fieldsMap[field];

        if (inputElement) {
            enableEditing(field, inputElement);
        }
    });
});

const notificaChiusa = document.getElementById('notifica_chiusa');

const STOCK_IMAGE_URL = '../assets/user.png';

// Chiave per il localStorage
const STORAGE_KEY = 'profileImage';


// Funzione per salvare l'immagine nel localStorage
function saveImageToLocalStorage(imageData) {
    localStorage.setItem(STORAGE_KEY, imageData);
}

// Funzione per caricare l'immagine dal localStorage
function loadImageFromLocalStorage() {
    const savedImage = localStorage.getItem(STORAGE_KEY);
    if (savedImage && savedImage !== STOCK_IMAGE_URL) {
        // Se c'è un'immagine salvata diversa dallo stock, la carica
        profileImage.src = savedImage;
    } else {
        // Altrimenti mostra l'immagine stock
        resetToStockImage();
    }
}

// Funzione per visualizzare l'immagine nel cerchio
function displayImage(imageData) {
    profileImage.src = imageData;
}

function resetToStockImage() {
    profileImage.src = STOCK_IMAGE_URL;
    // Rimuove l'immagine personalizzata dal localStorage
    localStorage.removeItem(STORAGE_KEY);
}

// Funzione per gestire il caricamento dell'immagine
function handleImageUpload(file) {
    // Verifica se è stato selezionato un file
    if (!file) return;

    // Verifica che sia un'immagine
    if (!file.type.startsWith('image/')) {
        alert('Per favore, seleziona un file immagine valido (jpg, png, gif, etc.)');
        return;
    }

    // Verifica la dimensione (max 5MB)
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (file.size > maxSize) {
        alert('L\'immagine è troppo grande! Dimensione massima: 5MB');
        return;
    }

    // Leggi il file come Data URL
    const reader = new FileReader();

    reader.onload = function (event) {
        const imageData = event.target.result;

        // Visualizza l'immagine
        displayImage(imageData);

        // Salva nel localStorage
        saveImageToLocalStorage(imageData);

        // Feedback all'utente
        showTemporaryMessage('Foto profilo caricata e salvata!');
    };

    reader.onerror = function () {
        alert('Errore durante la lettura del file. Per favore riprova.');
    };

    reader.readAsDataURL(file);
}

// Funzione per mostrare un messaggio temporaneo
function showTemporaryMessage(message) {
    // Crea un elemento per il messaggio
    const messageDiv = document.createElement('div');
    messageDiv.textContent = message;
    messageDiv.style.position = 'fixed';
    messageDiv.style.bottom = '20px';
    messageDiv.style.left = '50%';
    messageDiv.style.transform = 'translateX(-50%)';
    messageDiv.style.backgroundColor = '#4CAF50';
    messageDiv.style.color = 'white';
    messageDiv.style.padding = '10px 20px';
    messageDiv.style.borderRadius = '5px';
    messageDiv.style.zIndex = '1000';
    messageDiv.style.fontSize = '14px';
    messageDiv.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';

    document.body.appendChild(messageDiv);

    // Rimuovi il messaggio dopo 3 secondi
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Funzione per resettare la foto profilo
function resetProfileImage() {
    // Rimuovi l'immagine dal localStorage
    localStorage.removeItem(STORAGE_KEY);

    //Viene rimessa l'immagine stock
    profileImage.src = STOCK_IMAGE_URL;
    // Mostra un feedback
    showTemporaryMessage('Foto profilo eliminata');
}

// Evento click sul cerchio per aprire l'esplora file
profileCircle.addEventListener('click', () => {
    fileInput.click();
});

// Evento quando viene selezionato un file
fileInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    handleImageUpload(file);

    // Resetta l'input file per permettere di caricare lo stesso file di nuovo
    fileInput.value = '';
});


// Evento per il reset
resetBtn.addEventListener('click', resetProfileImage);

// Carica l'immagine salvata al caricamento della pagina
loadImageFromLocalStorage();

// (Opzionale) Aggiungi supporto per drag & drop
profileCircle.addEventListener('dragover', (e) => {
    e.preventDefault();
    profileCircle.style.opacity = '0.8';
});

profileCircle.addEventListener('dragleave', () => {
    profileCircle.style.opacity = '1';
});

profileCircle.addEventListener('drop', (e) => {
    e.preventDefault();
    profileCircle.style.opacity = '1';

    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        handleImageUpload(file);
    } else {
        alert('Per favore, trascina un file immagine valido');
    }
});

function changeData() {

}