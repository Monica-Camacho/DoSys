<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>DoSys - Estadísticas de Impacto</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <?php require_once 'templates/topbar.php'; ?>
    
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container text-center">
            <div>
                <h1 class='display-5 mb-0'>Nuestro Impacto en Números</h1>
                <p class="fs-5 text-muted mb-0">Cada número representa una vida cambiada gracias a la solidaridad.</p>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <i class="fas fa-hand-holding-heart fa-3x text-primary mb-3"></i>
                            <h2 class="card-title display-5" id="totalDonaciones">0</h2>
                            <p class="card-text text-muted">Donaciones Completadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h2 class="card-title display-5" id="totalDonantes">0</h2>
                            <p class="card-text text-muted">Donantes Únicos</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="mapa.php" class="text-decoration-none">
                        <div class="card text-center shadow-sm h-100 card-hover">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <i class="fas fa-hands-helping fa-3x text-primary mb-3"></i>
                                <h2 class="card-title display-5" id="totalOrganizaciones">0</h2>
                                <p class="card-text text-muted">Organizaciones Altruistas</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <a href="c-empresas_aliadas.php" class="text-decoration-none">
                        <div class="card text-center shadow-sm h-100 card-hover">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <i class="fas fa-building fa-3x text-primary mb-3"></i>
                                <h2 class="card-title display-5" id="totalEmpresas">0</h2>
                                <p class="card-text text-muted">Empresas Aliadas</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <div style="position: relative; height:350px; width:100%">
                                <canvas id="donacionesPorTipoChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <canvas id="donacionesPorMesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <canvas id="topOrganizacionesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <canvas id="donacionesPorEstadoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
    <?php require_once 'templates/scripts.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            Chart.defaults.font.family = "'DM Sans', sans-serif";
            Chart.defaults.font.size = 14;
            Chart.defaults.color = '#6c757d';

            function procesarEstadisticas(data) {
                // Actualizar tarjetas de totales
                document.getElementById('totalDonaciones').textContent = data.totales.donaciones.toLocaleString('es-MX');
                document.getElementById('totalDonantes').textContent = data.totales.donantes.toLocaleString('es-MX');
                document.getElementById('totalOrganizaciones').textContent = data.totales.organizaciones.toLocaleString('es-MX');
                document.getElementById('totalEmpresas').textContent = data.totales.empresas.toLocaleString('es-MX');

                // Gráfica 1: Donaciones por Tipo (Dona)
                const ctxPie = document.getElementById('donacionesPorTipoChart').getContext('2d');
                const pieData = [data.por_tipo.sangre, data.por_tipo.medicamentos, data.por_tipo.dispositivos];
                const totalTipos = pieData.reduce((a, b) => a + b, 0);

                new Chart(ctxPie, {
                    type: 'doughnut',
                    data: {
                        labels: ['Sangre', 'Medicamentos', 'Dispositivos'],
                        datasets: [{
                            data: pieData,
                            backgroundColor: ['rgba(220, 53, 69, 0.8)', 'rgba(13, 110, 253, 0.8)', 'rgba(255, 193, 7, 0.8)'],
                            borderColor: '#fff',
                            borderWidth: 3,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: { position: 'bottom' },
                            title: { display: true, text: 'Distribución de Donaciones por Tipo' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw;
                                        const percentage = totalTipos > 0 ? ((value / totalTipos) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });

                // Gráfica 2: Donaciones por Mes (Barras)
                const ctxBar = document.getElementById('donacionesPorMesChart').getContext('2d');
                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: data.por_mes.meses,
                        datasets: [{
                            label: 'Donaciones Realizadas',
                            data: data.por_mes.cantidades,
                            backgroundColor: 'rgba(6, 163, 218, 0.7)',
                            borderColor: 'rgba(6, 163, 218, 1)',
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: { y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } }, x: { grid: { display: false } } },
                        plugins: { legend: { display: false }, title: { display: true, text: 'Volumen de Donaciones Mensuales' } }
                    }
                });

                // Gráfica 3: Top 5 Organizaciones
                if (data.top_organizaciones && data.top_organizaciones.length > 0) {
                    const ctxTopOrgs = document.getElementById('topOrganizacionesChart').getContext('2d');
                    const orgLabels = data.top_organizaciones.map(org => org.nombre_organizacion);
                    const orgData = data.top_organizaciones.map(org => org.total_donaciones);
                    
                    new Chart(ctxTopOrgs, {
                        type: 'bar',
                        data: {
                            labels: orgLabels,
                            datasets: [{
                                label: 'Donaciones Gestionadas',
                                data: orgData,
                                backgroundColor: 'rgba(25, 135, 84, 0.7)',
                                borderColor: 'rgba(25, 135, 84, 1)',
                                borderWidth: 1,
                                borderRadius: 5
                            }]
                        },
                        options: {
                            indexAxis: 'y', // <-- Gráfica de barras horizontal
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: 'Top 5 Organizaciones por Donaciones Gestionadas' }
                            },
                            scales: { x: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } }, y: { grid: { display: false } } }
                        }
                    });
                }

                // Gráfica 4: Donaciones por Estado
                if (data.donaciones_por_estado && data.donaciones_por_estado.length > 0) {
                    const ctxEstado = document.getElementById('donacionesPorEstadoChart').getContext('2d');
                    const estadoLabels = data.donaciones_por_estado.map(e => e.estado);
                    const estadoData = data.donaciones_por_estado.map(e => e.total_donaciones);

                    new Chart(ctxEstado, {
                        type: 'bar',
                        data: {
                            labels: estadoLabels,
                            datasets: [{
                                label: 'Donaciones Completadas',
                                data: estadoData,
                                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                                borderColor: 'rgba(220, 53, 69, 1)',
                                borderWidth: 1,
                                borderRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: 'Donaciones por Estado' }
                            },
                            scales: { y: { beginAtZero: true, grid: { color: 'rgba(0, 0,0, 0.05)' } }, x: { grid: { display: false } } }
                        }
                    });
                }
            }

            // --- CÓDIGO PARA CONECTAR AL BACKEND ---
            fetch('auth/obtener_estadisticas.php')
                .then(response => {
                    if (!response.ok) { throw new Error('La respuesta del servidor no fue exitosa.'); }
                    return response.json();
                })
                .then(data => {
                    procesarEstadisticas(data);
                })
                .catch(error => {
                    console.error('Error al cargar las estadísticas:', error);
                    document.querySelector('.container-fluid.py-5 .container').innerHTML = '<div class="alert alert-danger">No se pudieron cargar los datos de estadísticas. Por favor, inténtelo más tarde.</div>';
                });
        });
    </script>

</body>
</html>