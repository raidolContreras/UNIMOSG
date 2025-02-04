<?php

$userId = $_SESSION['idUser'];

switch ($_POST['action']) {
    
    case 'registerUser':
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $userChatId = $_POST['userChatId'];
        $level = $_POST['level'];

        $cryptPassword = crypt($password, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        $data = array(
            "name" => $name,
            "email" => $email,
            "password" => $cryptPassword,
            "phone" => $phone,
            "userChatId" => $userChatId,
            "level" => $level
        );

        $response = FormsController::ctrRegisterUser($data);
        
        if ($response == 'ok') {
            Logs::createLogs($userId, 'registerUser', 'Registro de un nuevo usuario: '.json_encode($data));
        }

        echo $response;
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
        $userChatId = $_POST['userChatId'];
        $idUser = $_POST['idUser'];

        $data = array(
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "level" => $level,
            "userChatId" => $userChatId,
            "idUsers" => $idUser
        );

        $response = FormsController::ctrEditUser($data);
        if ($response == 'ok') {
            Logs::createLogs($userId, 'editUser', 'Edición de un usuario: '.json_encode($data));
        }
        echo $response;
        break;
    
    case 'suspendUser':
        $idUser = $_POST['suspendUsers'];
        $response = FormsController::ctrSuspendUser($idUser);
        if ($response == 'ok') {
            Logs::createLogs($userId, 'suspendUser', 'Suspención de un usuario: '.$_POST['suspendUsers']);
        }
        echo $response;
        break;
    
    case 'activateUser':
        $idUser = $_POST['activateUsers'];
        $response = FormsController::ctrActivateUser($idUser);
        if ($response == 'ok') {
            Logs::createLogs($userId, 'activateUser', 'Activación de un usuario: '.$_POST['activateUsers']);
        }
        echo $response;
        break;
    
    case 'deleteUser':
        $idUser = $_POST['deleteUsers'];
        $response = FormsController::ctrDeleteUser($idUser);
        if ($response == 'ok') {
            Logs::createLogs($userId, 'deleteUser', 'Eliminación de un usuario: '.$_POST['deleteUsers']);
        }
        echo $response;
        break;
}