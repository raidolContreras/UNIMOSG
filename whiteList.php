<?php

session_start();

// Validar y obtener la página solicitada
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_STRING);
$pagina = $pagina ? $pagina : 'inicio';

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
    // Verificar la página solicitada
    switch ($pagina) {
        case 'inicio':
            includeUserPages($pagina);
            break;
        case 'newUser':
        case 'users':
            includeAdminPages('users', $pagina);
            break;
        case 'schools':
        case 'newSchools':
            includeAdminPages('schools', $pagina);
            break;
        case 'zones':
        case 'newZones':
            includeAdminPages('zones', $pagina);
            break;
        case 'areas':
            includeAdminPages('areas', $pagina);
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

// Función para incluir páginas de usuarios
function includeUserPages($pagina) {
    includeCommonComponents();
    include 'view/pages/' . $pagina . '.php';
}

// Función para incluir páginas de administrador
function includeAdminPages($category, $pagina) {
    includeCommonComponents();
    include 'view/pages/admin/' . $category . '/' . $pagina . '.php';
}

// Función para incluir componentes comunes
function includeCommonComponents() {
    include 'view/pages/navs/header.php';
    include 'view/pages/modals.php';
    include 'view/js.php';
    include 'view/pages/navs/sidebar.php';
}

// Función para incluir página de error 404
function includeError404() {
    include 'error404.php';
}
