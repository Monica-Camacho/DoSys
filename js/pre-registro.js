// Obtener los elementos modales
let preRegistroModal = new bootstrap.Modal(document.getElementById('preRegistroModal'));
let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

// Lógica para manejar el envío del formulario
document.getElementById("pre-registro-form").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevenir el envío del formulario por defecto

    // Aquí iría la lógica para procesar o enviar los datos del formulario
    // Por ejemplo, con AJAX o algún otro método

    // Cerrar el modal de pre-registro
    preRegistroModal.hide();

    // Mostrar el modal de confirmación
    confirmModal.show();
});
