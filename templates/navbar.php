<?php

$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_type = $is_logged_in ? $_SESSION['tipo_usuario_id'] : 0;
$user_name = ($is_logged_in && isset($_SESSION['nombre_usuario'])) ? $_SESSION['nombre_usuario'] : 'Usuario';
?>

<div class="container-fluid nav-bar px-0 px-lg-4 py-lg-0">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light"> 
            <a href="<?php echo BASE_URL; ?>index.php" class="navbar-brand p-0">
                <img src="<?php echo BASE_URL; ?>img/logos/DoSys_largo_fondoTransparente.png" alt="DoSys_Logo" style="height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav justify-content-center w-100">
                    <a href="<?php echo BASE_URL; ?>index.php" class="nav-item nav-link">Inicio</a>
                    <a href="<?php echo BASE_URL; ?>avisos.php" class="nav-item nav-link">Avisos de Donación</a>
                    <a href="<?php echo BASE_URL; ?>mapa.php" class="nav-item nav-link">Mapa</a>
                    <a href="<?php echo BASE_URL; ?>estadisticas.php" class="nav-item nav-link">Estadísticas</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Conócenos</a>
                        <div class="dropdown-menu">
                            <a href="<?php echo BASE_URL; ?>c-sobre_nosotros.php" class="dropdown-item">Sobre Nosotros</a>
                            <a href="<?php echo BASE_URL; ?>c-nuestro_equipo.php" class="dropdown-item">Nuestro Equipo</a>
                            <a href="<?php echo BASE_URL; ?>c-logros.php" class="dropdown-item">Logros</a>
                            <a href="<?php echo BASE_URL; ?>c-empresas_aliadas.php" class="dropdown-item">Empresas Aliadas</a>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center ms-lg-4">
                    <?php if ($is_logged_in): ?>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle text-primary" data-bs-toggle="dropdown">
                                <?php
                                $icon_class = 'fa-user-circle';
                                if ($user_type == 2) $icon_class = 'fa-building';
                                if ($user_type == 3) $icon_class = 'fa-sitemap';
                                ?>
                                <i class="fas <?php echo $icon_class; ?> fa-2x me-2"></i>
                                <?php echo htmlspecialchars($user_name); ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <?php switch ($user_type):
                                    case 1: // Menú Persona ?>
                                        <a href="<?php echo BASE_URL; ?>persona_dashboard.php" class="dropdown-item">Mi Panel</a>
                                        <a href="<?php echo BASE_URL; ?>persona_beneficios.php" class="dropdown-item">Mis Beneficios</a>
                                        <a href="<?php echo BASE_URL; ?>persona_perfil.php" class="dropdown-item">Mi Perfil</a>
                                        <a href="<?php echo BASE_URL; ?>persona_configuracion.php" class="dropdown-item">Configuración</a>
                                        <?php break; ?>
                                    <?php case 2: // Menú Empresa ?>
                                        <a href="<?php echo BASE_URL; ?>empresa_dashboard.php" class="dropdown-item">Panel de Empresa</a>
                                        <a href="<?php echo BASE_URL; ?>empresa_perfil.php" class="dropdown-item">Perfil de la Empresa</a>
                                        <a href="<?php echo BASE_URL; ?>empresa_beneficios.php" class="dropdown-item">Beneficios</a>
                                        <a href="<?php echo BASE_URL; ?>empresa_usuarios.php" class="dropdown-item">Usuarios</a>
                                        <a href="<?php echo BASE_URL; ?>empresa_reportes.php" class="dropdown-item">Reportes</a>
                                        <a href="<?php echo BASE_URL; ?>empresa_configuracion.php" class="dropdown-item">Configuración</a>
                                        <?php break; ?>
                                    <?php case 3: // Menú Organización ?>
                                        <a href="<?php echo BASE_URL; ?>organizacion_dashboard.php" class="dropdown-item">Panel de Organización</a>
                                        <a href="<?php echo BASE_URL; ?>organizacion_solicitudes.php" class="dropdown-item">Solicitudes</a>
                                        <a href="<?php echo BASE_URL; ?>organizacion_donantes.php" class="dropdown-item">Donantes</a>
                                        <a href="<?php echo BASE_URL; ?>organizacion_usuarios.php" class="dropdown-item">Voluntarios</a>
                                        <a href="<?php echo BASE_URL; ?>organizacion_perfil.php" class="dropdown-item">Perfil de la Organización</a>
                                        <a href="<?php echo BASE_URL; ?>organizacion_configuracion.php" class="dropdown-item">Configuración</a>
                                        <?php break; ?>
                                <?php endswitch; ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?php echo BASE_URL; ?>auth/logout.php" class="dropdown-item text-danger">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-primary rounded-pill py-2 px-4 me-2 text-nowrap">Iniciar Sesión</a>
                        <a href="<?php echo BASE_URL; ?>r_seleccionar_tipo.php" class="btn btn-outline-primary rounded-pill py-2 px-4">Regístrate</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>