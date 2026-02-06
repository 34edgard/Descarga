<?php
use Liki\Plantillas\Flow;
// This component handles the main navigation when logged in

$menu = 'componentes/menu-desplegable';
?>
<ul class="nav col-8 col-md-auto mb-2 justify-content-center mb-md-0 gap-3">
    <li>
        <a href="/inicio" class="nav-link px-2 text-white">Inicio</a>
    </li>
    <li>
        <?php
        Flow::html($menu, [

        ]);
        ?>
    </li>
    <li>
        <?php
        Flow::html($menu, [
            'title' => 'Docentes',
            'items' => [
                ['label' => 'Registrar', 'hx_get' => '/Docente_Registrar/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Docentes','Registrar"],
                ['label' => 'Consultar', 'hx_get' => '/Docente_Consultar/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Docentes','Consultar"],
            ]
        ]);
        ?>
    </li>
    <li>
        <?php
        Flow::html($menu, [
            'title' => 'Reportes',
            'items' => [
                ['label' => 'Matrícula', 'hx_get' => '/Reportes_Matricula/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Reportes','Matricula"],
                ['label' => 'Planilla', 'hx_get' => '/Reportes_Planilla/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Reportes','Planilla"],
                ['label' => 'Diploma', 'hx_get' => '/Reportes_Diploma/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Reportes','Diploma"],
                ['label' => 'Estadística', 'hx_get' => '/Reportes_Estadisticas/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Reportes','Estadistica"],
                ['label' => 'Lista de Estudiantes', 'hx_get' => '/Reportes_ListaEstudiantes/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Reportes','Lista de Estudiantes"],
            
            ]
        ]);
        ?>
    </li>
    <li>
        <?php
        Flow::html($menu, [
            'title' => 'Plantel',
            'items' => [
                ['label' => 'Niveles', 'hx_get' => '/plantel_Niveles/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Plantel','Niveles"],
                ['label' => 'Secciones', 'hx_get' => '/plantel_Secciones/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Plantel','Secciones"],
                ['label' => 'Aulas', 'hx_get' => '/plantel_Aulas/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Plantel','Aulas"],
                ['label' => 'Periodo Escolar', 'hx_get' => '/plantel_crearPeriodo/src', 'hx_target' => '#main', 'hx_swap' => 'innerHTML', 'hx_trigger' => 'click', 'onclick_title' => "Plantel','Periodo Escolar"],
            ]
        ]);
        ?>
    </li>
    <li>
        <div class="dropdown">
            <a href="#" class="d-block link-body-emphasis link-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="frontend/Img/images.png" alt="mdo" width="32" height="32" class="rounded-circle border border-white">
            </a>
            <ul class="dropdown-menu text-small shadow dropdown-menu-end ">
                <li><a href="/Administrar" class="dropdown-item text-warning">Administrar</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a type="button" class="dropdown-item text-danger" id="cerrarSesion"
                       hx-get="/Cerrar_Sesion"
                       hx-trigger="click"
                       hx-target="#cerrarSecion"
                    >Cerrar Sesión</a>
                    <div id="cerrarSecion"></div>
                </li>
            </ul>
        </div>
    </li>
</ul>