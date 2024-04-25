<?php 
    
session_start();

	$pagina = $_GET['pagina'] ?? 'inicio';

    if (!isset($_SESSION['logged'])) {
        if ($pagina == 'login') {
            include_once 'view/pages/auth/login.php';
        } else {
            header("Location: login");
            exit();
        }
    } else {

        $navs = [
            'inicio',
            'users',
            'schools',
            'zones',
        ];
    
        if (in_array($pagina, $navs)) {
            include "view/pages/navs/header.php";
            include "view/pages/modals.php";
            include "view/js.php";
            include "view/pages/navs/sidebar.php";
        }
        
        if($pagina == 'inicio') {
            include 'view/pages/'.$pagina.'.php';
        } elseif($pagina == 'newUser' || $pagina == 'users') {
            include 'view/pages/admin/users/'.$pagina.'.php';
        } elseif($pagina == 'schools' || $pagina == 'newSchools') {
            include 'view/pages/admin/schools/'.$pagina.'.php';
        } elseif($pagina == 'zones' || $pagina == 'newZones') {
            include 'view/pages/admin/zones/'.$pagina.'.php';
        } elseif ($pagina == 'login'){
            header("Location: inicio");
            exit();
        } else {
            include "error404.php";
        }
        
    }