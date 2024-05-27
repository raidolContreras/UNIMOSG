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
        return FormsModel::mdlSendForm($idObject, $estado, $description, $importancia);
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

}