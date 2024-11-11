<?php

switch ($_POST['action']) {
    
    case 'registerUser':
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $level = $_POST['level'];

        $cryptPassword = crypt($password, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        $data = array(
            "name" => $name,
            "email" => $email,
            "password" => $cryptPassword,
            "phone" => $phone,
            "level" => $level
        );

        echo FormsController::ctrRegisterUser($data);
        break;
    
    case 'searchUser':
        $searchUser = $_POST['searchUser'];
        echo json_encode(FormsController::ctrSearchUsers('idUsers', $searchUser));
        break;
    
    case 'editUser':
        $name = $_POST['nameEdit'];
        $email = $_POST['emailEdit'];
        $phone = $_POST['phoneEdit'];
        $level = $_POST['levelEdit'];
        $idUser = $_POST['idUser'];

        $data = array(
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "level" => $level,
            "idUsers" => $idUser
        );

        echo FormsController::ctrEditUser($data);
        break;
    
    case 'suspendUser':
        $idUser = $_POST['suspendUsers'];
        echo FormsController::ctrSuspendUser($idUser);
        break;
    
    case 'activateUser':
        $idUser = $_POST['activateUsers'];
        echo FormsController::ctrActivateUser($idUser);
        break;
    
    case 'deleteUser':
        $idUser = $_POST['deleteUsers'];
        echo FormsController::ctrDeleteUser($idUser);
        break;
}