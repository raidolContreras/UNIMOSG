<?php

require_once "../forms.controller.php";
require_once "../../model/forms.models.php";

$idObject = $_POST['pk']; // Primary key
$name = $_POST['name']; // Column name
$value = $_POST['value']; // New value

$response = FormsController::ctrUpdateObject($idObject, $name, $value);

echo $response;