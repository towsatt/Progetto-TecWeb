function generaCodiceCampagna() {
    const caratteri = '0123456789abcdef';
    let codice = '';
    for (let i = 0; i < 12; i++) {
        codice += caratteri.charAt(Math.floor(Math.random() * caratteri.length));
    }
    return codice;
}

// Imposta il codice campagna all'apertura della pagina
document.addEventListener('DOMContentLoaded', function() {
    const inputCodice = document.getElementById('codice_campagna');
    if (inputCodice) {
        inputCodice.value = generaCodiceCampagna();
    }
});