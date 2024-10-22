<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $data = array(
        "idPlan" => $_POST['plan'],
        "idSchool" => $_POST['school'],
        "idZone" => $_POST['zone'],
        "idArea" => $_POST['area'],
        "idSupervisor" => $_POST['supervisor'],
        "datePlan" => $_POST['eventDate'],
        "eventTime" => $_POST['eventTime']
    );
    $response = FormsController::ctrAddPlans($data);
    echo json_encode($response);