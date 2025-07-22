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
                <div class="col-md-4">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body">
                            <i class="fas fa-hand-holding-heart fa-3x text-primary mb-3"></i>
                            <h2 class="card-title" id="totalDonaciones">0</h2>
                            <p class="card-text text-muted">Donaciones Completadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h2 class="card-title" id="totalDonantes">0</h2>
                            <p class="card-text text-muted">Donantes Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body">
                            <i class="fas fa-building fa-3x text-primary mb-3"></i>
                            <h2 class="card-title" id="totalEmpresas">0</h2>
                            <p class="card-text text-muted">Organizaciones Aliadas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Donaciones por Tipo</h5>
                            <canvas id="donacionesPorTipoChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Donaciones por Mes (Últimos 6)</h5>
                            <canvas id="donacionesPorMesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
    <?php require_once 'templates/scripts.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // Función para procesar los datos y crear las gráficas
            function procesarEstadisticas(data) {
                // Actualizar tarjetas de estadísticas
                document.getElementById('totalDonaciones').textContent = data.totales.donaciones.toLocaleString('es-MX');
                document.getElementById('totalDonantes').textContent = data.totales.donantes.toLocaleString('es-MX');
                document.getElementById('totalEmpresas').textContent = data.totales.empresas.toLocaleString('es-MX');

                // Crear gráfica de Donaciones por Tipo (Pastel)
                const ctxPie = document.getElementById('donacionesPorTipoChart').getContext('2d');
                new Chart(ctxPie, {
                    type: 'pie',
                    data: {
                        labels: ['Sangre', 'Medicamentos', 'Dispositivos'],
                        datasets: [{
                            label: 'Donaciones por Tipo',
                            data: [
                                data.por_tipo.sangre, 
                                data.por_tipo.medicamentos, 
                                data.por_tipo.dispositivos
                            ],
                            backgroundColor: [
                                'rgba(255, 82, 82, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)'
                            ],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'top' } }
                    }
                });

                // Crear gráfica de Donaciones por Mes (Barras)
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
                        scales: { y: { beginAtZero: true } },
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // --- CÓDIGO PARA CONECTAR AL BACKEND ---
            fetch('auth/obtener_estadisticas.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('La respuesta del servidor no fue exitosa.');
                    }
                    return response.json();
                })
                .then(data => {
                    procesarEstadisticas(data);
                })
                .catch(error => {
                    console.error('Error al cargar las estadísticas:', error);
                    // Opcional: Mostrar un mensaje de error en la página
                    // document.querySelector('.container-fluid.py-5').innerHTML = '<div class="alert alert-danger">No se pudieron cargar los datos de estadísticas.</div>';
                });
        });
    </script>

</body>
</html>