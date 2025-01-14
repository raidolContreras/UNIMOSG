<?php

session_start();


if (!isset($_SESSION['logged'])) {
    $pagina = filter_input(INPUT_GET, 'pagina') ?: 'login';
    if ($pagina == 'login') {
        include_once 'view/pages/auth/login.php';
    } else {
        header("Location: login");
        exit();
    }
} else {
    if ($_SESSION['level'] == 0) {
        $pagina = filter_input(INPUT_GET, 'pagina') ?: 'schools';
        $adminPages = ['inicio', 'newUser', 'users', 'schools', 'newSchools', 'edifices', 'newEdifices', 'floors', 'zones', 'objects', 'supervicion'];
        if (in_array($pagina, $adminPages)) {
            includeAdminPages($pagina);
        } elseif ($pagina == 'login') {
            header("Location: schools");
        } else {
            includeError404();
        }
    } else if ($_SESSION['level'] == 1) {
        $pagina = filter_input(INPUT_GET, 'pagina') ?: 'schools';
        $userPages = [
            'inicio' => 'inicio',
            'schools' => 'principal/schools/school',
            'school' => 'principal/schools/school',
            'edifices' => 'principal/edifices/edifices',
            'floors' => 'principal/floors/floors',
            'zones' => 'principal/zones/zones',
            'objects' => 'principal/objects/objects',
            'planes' => 'principal/planes/planes',
            'supervicion' => 'principal/supervicion/supervicion',
            'reportes' => 'principal/reportes/reportes',
            'mySupervition' => 'principal/supervicion/mySupervition',
        ];

        if (isset($userPages[$pagina])) {
            includeUserPages($userPages[$pagina]);
        } elseif ($pagina == 'login') {
            header("Location: schools");
        } else {
            includeError404();
        }
    } else {
        $pagina = filter_input(INPUT_GET, 'pagina') ?: 'inicio';
        $userPages = [
            'inicio' => 'inicio',
        ];

        if (isset($userPages[$pagina])) {
            includeUserPages($userPages[$pagina]);
        } elseif ($pagina == 'login') {
            header("Location: schools");
        } else {
            includeError404();
        }
    }
}

function includeUserPages($pagina) {
    includeCommonComponents();
    include 'view/pages/' . $pagina . '.php';
}

function includeAdminPages($pagina) {
    includeCommonComponents();
    if ($pagina == 'inicio') {
        include 'view/pages/' . $pagina . '.php';
    } else {
        include 'view/pages/admin/' . $pagina .'/' . $pagina . '.php';
    }
}

function includeCommonComponents() {
    include 'view/pages/navs/header.php';
    include 'view/pages/modals.php';
    include 'view/js.php';
    if ($_SESSION['level'] != 2) {
        include 'view/pages/navs/sidebar.php';
    }
}

function includeError404() {
    include 'error404.php';
}
