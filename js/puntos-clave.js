document.getElementById('municipio-select').addEventListener('change', function() {
    var municipio = this.value;
    var puntoClaveSelect = document.getElementById('punto-clave-select');
    puntoClaveSelect.disabled = false;
});

document.getElementById('punto-clave-select').addEventListener('change', function() {
    var selectedPunto = this.value;
    var puntosClave = document.querySelectorAll('.punto-clave-info');
    puntosClave.forEach(function(punto) {
        punto.classList.add('d-none');
    });
    document.getElementById(selectedPunto).classList.remove('d-none');
    new WOW().init();
});
