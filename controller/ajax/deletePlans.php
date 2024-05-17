<?php

require_once "../forms.controller.php";
require_once "../../model/forms.models.php";
$plan = (isset($_POST['idPlan'])) ? $_POST['idPlan'] : null;
$response = FormsController::ctrDeletePlans($plan);
echo json_encode($response);
