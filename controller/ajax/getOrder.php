<?php 
    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $searchIncidente = (isset($_POST['searchIncidente'])) ? $_POST['searchIncidente'] : null;
    $response = FormsController::ctrSearchIncidentes($searchIncidente);
    echo json_encode($response);
    