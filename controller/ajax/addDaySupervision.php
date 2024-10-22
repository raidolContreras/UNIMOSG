<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $data = array(
        "idSchool" => $_POST['school'],
        "idZone" => $_POST['zone'],
        "idArea" => $_POST['area'],
        "day" => $_POST['dia'],
        "supervisionTime" => $_POST['supervisionTime'],
        "idSupervisor" => $_POST['supervisor']
    );
    $response = FormsController::ctrAddDaySupervision($data);
    echo json_encode($response);