// Validar si los campos están vacíos
function isFieldEmpty(fieldId) {
    const field = document.getElementById(fieldId);
    return field.value.trim() === "";  // Verifica si el campo está vacío
}

// Marcar campos no válidos
function markFieldAsInvalid(fieldId) {
    const field = document.getElementById(fieldId);
    field.classList.add("is-invalid"); // Agrega una clase de Bootstrap para el error
}

// Limpiar errores visuales
function clearInvalidMark(fieldId) {
    const field = document.getElementById(fieldId);
    field.classList.remove("is-invalid"); // Limpia la clase de error
}

// Mostrar y desplegar progresivamente los pasos con validación
document.getElementById("next-step-1").addEventListener("click", function(event) {
    event.preventDefault();
    
    let valid = true;
    
    // Validación de los campos
    if (isFieldEmpty("donatario-nombre")) {
        markFieldAsInvalid("donatario-nombre");
        valid = false;
    } else {
        clearInvalidMark("donatario-nombre");
    }

    if (isFieldEmpty("donatario-email")) {
        markFieldAsInvalid("donatario-email");
        valid = false;
    } else {
        clearInvalidMark("donatario-email");
    }

    if (isFieldEmpty("donatario-telefono")) {
        markFieldAsInvalid("donatario-telefono");
        valid = false;
    } else {
        clearInvalidMark("donatario-telefono");
    }

    // Mostrar el Paso 2 si todos los campos son válidos
    if (valid) {
        let step2 = document.getElementById("step-2");
        if (step2.classList.contains("d-none")) {
            step2.classList.remove("d-none");
            step2.classList.add("fadeInRight");
        }
    }
});

// Función para deslizar la página hacia abajo automáticamente
function scrollToNextStep(stepId) {
    const nextStep = document.getElementById(stepId);
    nextStep.scrollIntoView({ behavior: 'smooth' });
}

document.getElementById("next-step-2").addEventListener("click", function(event) {
    event.preventDefault();
    let isFamiliar = document.getElementById("solicitud-familiar").checked;

    if (isFamiliar) {
        let step3 = document.getElementById("step-3");
        if (step3.classList.contains("d-none")) {
            step3.classList.remove("d-none");
            step3.classList.add("fadeInLeft");
        }
        scrollToNextStep("step-3");
    } else {
        let step4 = document.getElementById("step-4");
        if (step4.classList.contains("d-none")) {
            step4.classList.remove("d-none");
            step4.classList.add("fadeInRight");
        }
        scrollToNextStep("step-4");
    }
});

document.getElementById("next-step-3").addEventListener("click", function(event) {
    event.preventDefault();

    let step4 = document.getElementById("step-4");
    if (step4.classList.contains("d-none")) {
        step4.classList.remove("d-none");
        step4.classList.add("fadeInRight");
    }

    scrollToNextStep("step-4");  // Deslizar hacia el siguiente paso (Paso 4)
});

document.getElementById("next-step-4").addEventListener("click", function(event) {
    event.preventDefault();

    // Validación del número de donadores (máximo 5)
    let numDonadores = document.getElementById("num-donadores").value;
    if (numDonadores > 5) {
        alert("El número máximo de donadores permitidos es 5.");
        return;
    }

    if (isFieldEmpty("tipo-sangre") || isFieldEmpty("num-donadores") || isFieldEmpty("cantidad-sangre") || isFieldEmpty("fecha-limite")) {
        // Aquí podrías agregar una indicación visual si los campos están vacíos
        return; // No continuar si hay campos vacíos
    }

    // Mostrar el Paso 5
    let step5 = document.getElementById("step-5");
    if (step5.classList.contains("d-none")) {
        step5.classList.remove("d-none");
        step5.classList.add("fadeInRight");
    }

    scrollToNextStep("step-5");  // Deslizar hacia el Paso 5
});


// Habilitar campo de "Ubicación Personalizada" solo si no se selecciona un centro de salud
document.getElementById("centro-salud").addEventListener("change", function() {
    const ubicacionInput = document.getElementById("ubicacion-personalizada");
    if (this.value === "") {
        ubicacionInput.disabled = false;
    } else {
        ubicacionInput.disabled = true;
    }
});

// Mostrar mapa según el centro de salud seleccionado
document.getElementById("centro-salud").addEventListener("change", function() {
    const mapContainer = document.getElementById("map-container");
    const issetMap = document.getElementById("isset-manzur-map");
    const grahamMap = document.getElementById("hospital-juan-graham-map");

    // Mostrar el mapa correspondiente y ocultar los otros
    if (this.value === "isset-manzur") {
        mapContainer.classList.remove("d-none");
        issetMap.classList.remove("d-none");
        grahamMap.classList.add("d-none");
    } else if (this.value === "hospital-juan-graham") {
        mapContainer.classList.remove("d-none");
        grahamMap.classList.remove("d-none");
        issetMap.classList.add("d-none");
    } else {
        mapContainer.classList.add("d-none");
    }
});

// Avanzar al Paso 6: Comentarios Extras
document.getElementById("next-step-5").addEventListener("click", function(event) {
    event.preventDefault();

    // Mostrar el Paso 6
    let step6 = document.getElementById("step-6");
    if (step6.classList.contains("d-none")) {
        step6.classList.remove("d-none");
        step6.classList.add("fadeInRight");
    }

    scrollToNextStep("step-6");  // Deslizar hacia el Paso 6
});

// Avanzar al Paso 7: Subir Documento
document.getElementById("next-step-6").addEventListener("click", function(event) {
    event.preventDefault();

    // Mostrar el Paso 7
    let step7 = document.getElementById("step-7");
    if (step7.classList.contains("d-none")) {
        step7.classList.remove("d-none");
        step7.classList.add("fadeInRight");
    }

    scrollToNextStep("step-7");  // Deslizar hacia el Paso 7
});

// Avanzar al Paso 8: Revisión Final
document.getElementById("next-step-7").addEventListener("click", function(event) {
    event.preventDefault();

    // Validar que se haya subido un archivo PDF
    const archivo = document.getElementById("archivo-verificacion").files[0];
    if (!archivo || archivo.type !== "application/pdf") {
        alert("Por favor, sube un archivo PDF válido.");
        return;
    }

    // Mostrar el Paso 8 (Revisión Final)
    let step8 = document.getElementById("step-8");
    if (step8.classList.contains("d-none")) {
        step8.classList.remove("d-none");
        step8.classList.add("fadeInRight");
    }

    scrollToNextStep("step-8");  // Deslizar hacia el Paso 8
});


// Avanzar al Paso 8: Revisión Final
document.getElementById("next-step-7").addEventListener("click", function(event) {
    event.preventDefault();

    // Validar que se haya subido un archivo PDF
    const archivo = document.getElementById("archivo-verificacion").files[0];
    if (!archivo || archivo.type !== "application/pdf") {
        alert("Por favor, sube un archivo PDF válido.");
        return;
    }

    // Capturar los datos ingresados y mostrarlos en el resumen final
    document.getElementById("final-nombre").textContent = document.getElementById("donatario-nombre").value;
    document.getElementById("final-email").textContent = document.getElementById("donatario-email").value;
    document.getElementById("final-telefono").textContent = document.getElementById("donatario-telefono").value;

    if (document.getElementById("solicitud-familiar").checked) {
        document.getElementById("final-nombre-paciente").textContent = document.getElementById("paciente-nombre").value;
        document.getElementById("final-relacion-paciente").textContent = document.getElementById("relacion").value;
        document.getElementById("final-paciente").classList.remove("d-none");
    }

    document.getElementById("final-tipo-sangre").textContent = document.getElementById("tipo-sangre").value;
    document.getElementById("final-num-donadores").textContent = document.getElementById("num-donadores").value;
    document.getElementById("final-cantidad-sangre").textContent = document.getElementById("cantidad-sangre").value;
    document.getElementById("final-fecha-limite").textContent = document.getElementById("fecha-limite").value;

    // Mostrar el centro de salud y mapa en el resumen
    let centroSalud = document.getElementById("centro-salud").value;
    document.getElementById("final-centro-salud").textContent = centroSalud === "hospital-juan-graham" ? "Hospital Juan Graham Casasús" : "Centro de Especialidades Médicas ISSET";

    let mapaFinal = document.getElementById("final-mapa");
    mapaFinal.innerHTML = "";  // Limpiar mapa anterior
    if (centroSalud === "hospital-juan-graham") {
        mapaFinal.innerHTML = document.getElementById("hospital-juan-graham-map").innerHTML;
    } else if (centroSalud === "isset-manzur") {
        mapaFinal.innerHTML = document.getElementById("isset-manzur-map").innerHTML;
    }

    document.getElementById("final-comentarios").textContent = document.getElementById("comentarios-extras").value;
    document.getElementById("final-archivo").textContent = archivo.name;

    // Mostrar el Paso 8 (Revisión Final)
    let step8 = document.getElementById("step-8");
    if (step8.classList.contains("d-none")) {
        step8.classList.remove("d-none");
        step8.classList.add("fadeInRight");
    }

    scrollToNextStep("step-8");  // Deslizar hacia el Paso 8
});

document.getElementById("formulario-solicitar-sangre").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevenir que se envíe el formulario de manera predeterminada

    // Capturar los datos del formulario
    const nombre = document.getElementById("final-nombre").value;
    const tipoSangre = document.getElementById("tipo-sangre").value;
    const numDonadores = document.getElementById("num-donadores").value;
    const fechaLimite = document.getElementById("fecha-limite").value;
    const comentarios = document.getElementById("comentarios-extras").value;
    const estadoSolicitud = "Pendiente";  // Estado por defecto

    // Llamar a la función para añadir la solicitud a la tabla de avisos
    agregarSolicitudATabla({
        nombreCompleto: `${nombre} ${apellidoPaterno} ${apellidoMaterno}`,
        tipoSangre,
        numDonadores,
        fechaLimite,
        estadoSolicitud,
        comentarios
    });

    // Limpiar el formulario después de agregar los datos
    document.getElementById("formulario-solicitar-sangre").reset();
});

// Lógica para confirmar y enviar la solicitud
document.getElementById("confirmar-enviar-final").addEventListener("click", function(event) {
    event.preventDefault();

    // Mostrar el modal de confirmación
    let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
        keyboard: false,
        backdrop: 'static'
    });
    confirmModal.show();

    // Redirigir después de 5 segundos
    setTimeout(function() {
        window.location.href = "D-avisos.html"; // Redirección a la página de avisos
    }, 5000); // 5 segundos
});



