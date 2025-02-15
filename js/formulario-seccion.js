function mostrarSeccion(seccion) {
    const secciones = document.querySelectorAll('.seccion');
    secciones.forEach(sec => sec.style.display = 'none');

    const seccionActiva = document.getElementById('seccion' + seccion);
    if (seccionActiva) {
        seccionActiva.style.display = 'block';
    }
}

// Manejo del formulario
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('formulario');

    formulario.addEventListener('submit', function(event) {
        event.preventDefault();

        // Validar que los campos requeridos tengan datos
        const nombre = document.getElementById('nombre').value.trim();
        const apellidoP = document.getElementById('apellidoP').value.trim();
        const apellidoM = document.getElementById('apellidoM').value.trim();
        const fechaNacimiento = document.getElementById('fechaNacimiento').value.trim();
        const genero = document.getElementById('genero').value.trim();
        const tipoDonacion = document.getElementById('catalogo-donacion').value.trim();
        const centroDonacion = document.getElementById('puntos-donacion').value.trim();

        if (!nombre || !apellidoP || !apellidoM || !fechaNacimiento || !genero || !tipoDonacion || !centroDonacion) {
            alert("Por favor, completa todos los campos obligatorios.");
            return;
        }

        // Mostrar modal de confirmación
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
        modal.show();

        // Limpiar formulario después de enviar (opcional)
        formulario.reset();
        mostrarSeccion(1);
    });
});
