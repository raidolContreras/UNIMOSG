<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $item = (isset($_POST['idSchool'])) ? 'idSchool' : null;
    $value = (isset($_POST['idSchool'])) ? $_POST['idSchool'] : null;
    $response = FormsController::ctrSearchschools($item,$value);
    echo json_encode($response);