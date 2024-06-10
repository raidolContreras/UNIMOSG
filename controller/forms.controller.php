<?php 

class FormsController {
    static public function ctrSearchUsers($item,$value){
        return FormsModel::mdlSearchUsers($item,$value);
    }
    static public function ctrSearchschools($item,$value){
        return FormsModel::mdlSearchschools($item,$value);
    }

    static public function ctrRegisterSchool($nameSchool){
        return FormsModel::mdlRegisterSchool($nameSchool);
    }

    static public function ctrRegisterZone($nameZone, $idSchool){
        return FormsModel::mdlRegisterZone($nameZone, $idSchool);
    }

    static public function ctrRegisterArea($nameArea, $idSchool){
        return FormsModel::mdlRegisterArea($nameArea, $idSchool);
    }

    static public function ctrRegisterObject($nameObject, $cantidad, $idArea){
        return FormsModel::mdlRegisterObject($nameObject, $cantidad, $idArea);
    }

    static public function ctrSearchZones($idSchool, $item, $value){
        return FormsModel::mdlSearchZones($idSchool, $item, $value);
    }

    static public function ctrSearchObjects($idArea, $item, $value){
        return FormsModel::mdlSearchObjects($idArea, $item, $value);
    }

    static public function ctrSearchArea($idZone, $item, $value){
        return FormsModel::mdlSearchArea($idZone, $item, $value);
    }

    static public function ctrGetArea($item, $value){
        return FormsModel::mdlGetArea($item, $value);
    }

    static public function ctrEditArea($data){
        return FormsModel::mdlEditArea($data);
    }

    static public function ctrSearchObject($idArea, $item, $value){
        return FormsModel::mdlSearchObject($idArea, $item, $value);
    }

    static public function ctrRegisterUser($data){
        return FormsModel::mdlRegisterUser($data);
    }

    static public function ctrEditUser($data){
        return FormsModel::mdlEditUser($data);
    }

    static public function ctrSuspendUser($idUsers){
        return FormsModel::mdlSuspendUser($idUsers);
    }
    static public function ctrActivateUser($idUsers){
        return FormsModel::mdlActivateUser($idUsers);
    }

    static public function ctrEditSchool($data){
        return FormsModel::mdlEditSchool($data);
    }

    static public function ctrEditZone($data){
        return FormsModel::mdlEditZone($data);
    }

    static public function ctrSearchAreas($idZone, $item, $value){
        return FormsModel::mdlSearchAreas($idZone, $item, $value);
    }

    static public function ctrSendForm($idObject, $estado, $description, $importancia){
        
        $object = FormsModel::mdlSearchObject(null, 'idObject', $idObject);
        $area = FormsModel::mdlSearchArea(null, 'idArea', $object['objects_idArea']);

        $informe = FormsModel::mdlSendForm($idObject, $estado, $description, $importancia);
        $nameSchool = $area['nameSchool'];

        // Extraer los primeros dos caracteres del nombre de la escuela
        $schoolInitials = strtoupper(substr($nameSchool, 0, 1)) . strtoupper(substr($nameSchool, strpos($nameSchool, ' ') + 1, 1));

        // Formatear el valor del informe a tres dígitos con ceros a la izquierda
        $informeFormatted = str_pad($informe, 3, '0', STR_PAD_LEFT);

        // Concatenar las iniciales de la escuela con el informe formateado
        $pedido = $schoolInitials . $informeFormatted;

        $response = FormsModel::mdlPedido($pedido, $informe);

        if($importancia != 'Pendiente'){
            FormsModel::mdlSendImportantMail($area, $object, $estado, $description, $importancia);
        }
        return $response;
    }

    static public function ctrSearchSolicitudes($idSchool,$importancia){
        return FormsModel::mdlSearchSolicitudes($idSchool,$importancia);
    }

    static public function ctrDeleteSchool($idSchool){
        return FormsModel::mdlDeleteSchool($idSchool);
    }

    static public function ctrSearchIncidentes($idIncidente){
        return FormsModel::mdlSearchIncidentes($idIncidente);
    }

    static public function ctrDetailsCorrect($data){
        return FormsModel::mdlDetailsCorrect($data);
    }

    static public function ctrGetPlans($plan){
        return FormsModel::mdlGetPlans($plan);
    }

    static public function ctrAddPlans($data){
        if ($data['idPlan'] == '') {
            $response = FormsModel::mdlAddPlans($data);
        } else {
            $response = FormsModel::mdlEditPlans($data);
        }
        return FormsController::ctrGetPlans($response);
    }

    static public function ctrAddDaySupervision($data){
        $response = FormsModel::mdlAddDaySupervision($data);
        return FormsController::ctrGetDaySupervision($response);
    }

    static public function ctrGetDaySupervision($idSupervisionDays) {
        return FormsModel::mdlGetDaySupervision($idSupervisionDays);
    }

    static public function ctrDeletePlans($idPlan){
        return FormsModel::mdlDeletePlans($idPlan);
    }

    static public function ctrSearchIncidentsDaily(){
        return FormsModel::mdlSearchIncidentsDaily();
    }

    static public function ctrSendMaildaily(){
        $data = FormsController::ctrSearchIncidentsDaily();
        return FormsModel::mdlSendMail($data);
    }

    static public function ctrDeleteZone($idZone){
        return FormsModel::mdlDeleteZone($idZone);
    }

    static public function ctrDeleteArea($idArea){
        return FormsModel::mdlDeleteArea($idArea);
    }
    static public function ctrUpdateObject($idObject, $name, $value) {
        return FormsModel::mdlUpdateObject($idObject, $name, $value);
    }
    static public function ctrDeleteSupervisionDays($idSupervisionDays) {
        return FormsModel::mdlDeleteSupervisionDays($idSupervisionDays);
    }

    static public function ctrEstadisticas() {
        return FormsModel::mdlEstadisticas();
    }
}