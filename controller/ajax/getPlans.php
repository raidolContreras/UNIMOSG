<?php

require_once "../forms.controller.php";
require_once "../../model/forms.models.php";
$plan = (isset($_POST['plan'])) ? $_POST['plan'] : null;
$response = FormsController::ctrGetPlans($plan);
echo json_encode($response);
