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
    static public function ctrDeleteUser($idUser){
        return FormsModel::mdlDeleteUser($idUser);
    }

    static public function ctrEditSchool($data){
        return FormsModel::mdlEditSchool($data);
    }

    static public function ctrSearchSolicitudes($idSchool,$importancia){
        return FormsModel::mdlSearchSolicitudes($idSchool,$importancia);
    }

    static public function ctrUpdateOrderSchool($position, $idSchool){
        return FormsModel::mdlUpdateOrderSchool($position, $idSchool);
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

    static public function ctrGetPlans($plan, $user){
        return FormsModel::mdlGetPlans($plan, $user);
    }

    static public function ctrAddPlans($data){
        if ($data['idPlan'] == '') {
            $response = FormsModel::mdlAddPlans($data);
        } else {
            $response = FormsModel::mdlEditPlans($data);
        }
        return FormsController::ctrGetPlans($response, $data['idSupervisor']);
    }

    static public function ctrAddDaySupervision($data){
        $response = FormsModel::mdlAddDaySupervision($data);
        return FormsController::ctrGetDaySupervision($response, null);
    }

    static public function ctrGetDaySupervision($idSupervisionDays, $user) {
        return FormsModel::mdlGetDaySupervision($idSupervisionDays, $user);
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
    
    static public function ctrDeleteSupervisionDays($idSupervisionDays) {
        return FormsModel::mdlDeleteSupervisionDays($idSupervisionDays);
    }

    static public function ctrEstadisticas() {
        return FormsModel::mdlEstadisticas();
    }

    static public function ctrSearchDirector() {
        return FormsModel::mdlSearchDirector();
    }

    static public function ctrAsignarFecha($data) {
        return FormsModel::mdlAsignarFecha($data);
    }
}