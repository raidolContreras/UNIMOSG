<?php 

session_start();

$pagina = $_GET['pagina'] ?? 'inicio';

// Verificar si el usuario está logueado
if (!isset($_SESSION['logged'])) {
    // Si no está logueado, mostrar página de login
    if ($pagina == 'login') {
        include_once 'view/pages/auth/login.php';
    } else {
        // Si intenta acceder a otra página sin loguearse, redirigir al login
        header("Location: login");
        exit();
    }
} else {
    // Definir las páginas disponibles en el menú de navegación
    $navs = [
        'inicio',
        'users',
        'schools',
        'zones',
    ];

    // Verificar la página solicitada
    switch ($pagina) {
        case 'inicio':
            // Incluir componentes comunes y la página de inicio
            includeCommonComponents();
            include 'view/pages/'.$pagina.'.php';
            break;
        case 'newUser':
        case 'users':
            // Verificar permisos de usuario y cargar páginas relacionadas a usuarios
            if($_SESSION['level'] == 0){
                includeCommonComponents();
                include 'view/pages/admin/users/'.$pagina.'.php';
            } else {
                includeError404();
            }
            break;
        case 'schools':
        case 'newSchools':
            // Incluir componentes comunes y páginas relacionadas a escuelas
            includeCommonComponents();
            include 'view/pages/admin/schools/'.$pagina.'.php';
            break;
        case 'zones':
        case 'newZones':
            // Incluir componentes comunes y páginas relacionadas a zonas
            includeCommonComponents();
            include 'view/pages/admin/zones/'.$pagina.'.php';
            break;
        case 'login':
            // Si intenta acceder al login estando logueado, redirigir a la página de inicio
            header("Location: inicio");
            exit();
        default:
            // Si la página solicitada no se encuentra en el menú, mostrar error 404
            includeError404();
    }
}

// Función para incluir componentes comunes
function includeCommonComponents() {
    include "view/pages/navs/header.php";
    include "view/pages/modals.php";
    include "view/js.php";
    include "view/pages/navs/sidebar.php";
}

// Función para incluir página de error 404
function includeError404() {
    include "error404.php";
}
