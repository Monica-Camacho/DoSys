document.addEventListener('DOMContentLoaded', function() {
    const avisosContainer = document.getElementById('avisos-container');

    const avisos = [
        {
            folio: 'D001',
            articulo: 'Sangre O+',
            ubicacion: 'Hospital General',
            tipo: 'Sangre',
            fecha_solicitud: '2024-07-01',
            fecha_final: '2024-07-01',
            estado: 'PENDIENTE',
            cantidad: '1 Unidad',
            descripcion: 'Sangre O+ para transfusión',
            prioridad: 'ALTA'
        },
        {
            folio: 'D002',
            articulo: 'Insulina',
            ubicacion: 'Centro de Salud',
            tipo: 'Medicamentos',
            fecha_solicitud: '2024-07-01',
            fecha_final: '2024-07-01',
            estado: 'EN PROCESO',
            cantidad: '5 Frascos',
            descripcion: 'Insulina de acción rápida',
            prioridad: 'ALTA'
        },
        // Agrega más avisos según sea necesario...
    ];

    avisos.forEach(aviso => {
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${aviso.folio}</td>
            <td>${aviso.articulo}</td>
            <td>${aviso.ubicacion}</td>
            <td>${aviso.tipo}</td>
            <td>${aviso.fecha_solicitud}</td>
            <td>${aviso.fecha_final}</td>
            <td>${aviso.estado}</td>
            <td>${aviso.cantidad}</td>
            <td>${aviso.descripcion}</td>
            <td>${aviso.prioridad}</td>
            <td><button class="btn btn-sm btn-outline-primary">➔</button></td>
        `;

        avisosContainer.appendChild(row);
    });
});
