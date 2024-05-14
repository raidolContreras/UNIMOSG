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
            if ($_SESSION['level'] == 0) {
                includeAdminPages('users', $pagina);
            } else {
                includeError404();
            }
            break;
        case 'users':
            if ($_SESSION['level'] == 0) {
                includeAdminPages('users', $pagina);
            } else {
                includeError404();
            }
            break;
        case 'schools':
            if ($_SESSION['level'] == 0) {
                includeAdminPages('schools', $pagina);
            } else {
                includeUserPages('principal/schools/'.$pagina);
            }
            break;
        case 'school':
            includeUserPages('principal/schools/'.$pagina);
            break;
        case 'newSchools':
            if ($_SESSION['level'] == 0) {
                includeAdminPages('schools', $pagina);
            } else {
                includeError404();
            }
            break;
        case 'zones':
            if ($_SESSION['level'] == 0) {
                includeAdminPages('zones', $pagina);
            } else {
                includeUserPages('principal/zones/'.$pagina);
            }
            break;
        case 'newZones':
            if ($_SESSION['level'] == 0) {
                includeAdminPages('zones', $pagina);
            } else {
                includeError404();
            }
            break;
        case 'areas':
            if ($_SESSION['level'] == 0) {
                includeAdminPages('areas', $pagina);
            } else {
                includeUserPages('principal/areas/'.$pagina);
            }
            break;
        case 'objetos':
            if ($_SESSION['level'] == 0) {
                includeAdminPages('objetos', $pagina);
            } else {
                includeUserPages('principal/objetos/'.$pagina);
            }
            break;
        case 'login':
            header("Location: inicio");
            break;
        case 'lista':
            if ($_SESSION['level'] == 0) {
                includeError404();
            } else {
                includeUserPages('principal/general/'.$pagina);
            }
            break;
        case 'plan':
            if ($_SESSION['level'] == 0) {
                includeError404();
            } else {
                includeUserPages('principal/plan/'.$pagina);
            }
            break;
        default:
            // Si la página solicitada no se encuentra en el menú, mostrar error 404
            includeError404();
    }
}

// Función para incluir páginas de usuarios
function includeUserPages($pagina) {
    include 'view/pages/navs/header.php';
    include 'view/pages/modals.php';
    include 'view/js.php';
    if ($_SESSION['level'] != 2){
        includeCommonComponents();
    }
    include 'view/pages/' . $pagina . '.php';
}

// Función para incluir páginas de administrador
function includeAdminPages($category, $pagina) {
    include 'view/pages/navs/header.php';
    include 'view/pages/modals.php';
    include 'view/js.php';
    if ($_SESSION['level'] != 2){
        includeCommonComponents();
    }
    include 'view/pages/admin/' . $category . '/' . $pagina . '.php';
}

// Función para incluir componentes comunes
function includeCommonComponents() {
    include 'view/pages/navs/sidebar.php';
}

// Función para incluir página de error 404
function includeError404() {
    include 'error404.php';
}