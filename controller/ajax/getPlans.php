<?php

require_once "../forms.controller.php";
require_once "../../model/forms.models.php";
$plan = (isset($_POST['plan'])) ? $_POST['plan'] : null;
$user = (isset($_POST['user'])) ? $_POST['user'] : null;
$response = FormsController::ctrGetPlans($plan, $user);
echo json_encode($response);
