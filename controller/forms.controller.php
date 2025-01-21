<?php 

class FormsController {
    static public function ctrSearchUsers($item,$value){
        return FormsModel::mdlSearchUsers($item,$value);
    }
    static public function ctrSearchschools($item,$value){
        return FormsModel::mdlSearchschools($item,$value);
    }

    static public function ctrRegisterSchool($nameSchool, $chatId){
        return FormsModel::mdlRegisterSchool($nameSchool, $chatId);
    }

    static public function ctrSearchEdificers($item, $value){
        return FormsModel::mdlSearchEdificers($item, $value);
    }
    
    static public function ctrRegisterEdificer($edificeName, $idSchool){
        return FormsModel::mdlRegisterEdificer($edificeName, $idSchool);
    }
    
    static public function ctrUpdateEdificer($data){
        return FormsModel::mdlUpdateEdificer($data);
    }
    
    static public function ctrDeleteEdificer($idEdificers){
        return FormsModel::mdlDeleteEdificer($idEdificers);
    }
    
    static public function ctrUpdateOrderEdificer($position, $idEdificers){
        return FormsModel::mdlUpdateOrderEdificer($position, $idEdificers);
    }

    static public function ctrSearchFloors($item, $value) {
        return FormsModel::mdlSearchFloors($item, $value);
    }
    
    static public function ctrRegisterFloor($floorName, $idEdificers) {
        return FormsModel::mdlRegisterFloor($floorName, $idEdificers);
    }
    
    static public function ctrUpdateFloor($data) {
        return FormsModel::mdlUpdateFloor($data);
    }
    
    static public function ctrDeleteFloor($idFloor) {
        return FormsModel::mdlDeleteFloor($idFloor);
    }
    
    static public function ctrUpdateOrderFloor($position, $idFloor) {
        return FormsModel::mdlUpdateOrderFloor($position, $idFloor);
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

    static public function ctrGetAreasForZone($zone, $idFloor) {
        return FormsModel::mdlGetAreasForZone($zone, $idFloor);
    }

    static public function ctrSearchAreas($item, $value) {
        return FormsModel::mdlSearchAreas($item, $value);
    }

    static public function ctrRegisterArea($zone, $nareaName, $idFloor) {
        return FormsModel::mdlRegisterArea($zone, $nareaName, $idFloor);
    }

    static public function ctrUpdateArea($data) {
        return FormsModel::mdlUpdateArea($data);
    }

    static public function ctrDeleteArea($idArea) {
        return FormsModel::mdlDeleteArea($idArea);
    }

    static public function ctrUpdateOrderArea($position, $idArea) {
        return FormsModel::mdlUpdateOrderArea($position, $idArea);
    }

    static public function ctrGetObjects($idArea){
        return FormsModel::mdlGetObjects($idArea);
    }

    static public function ctrAddObjects($data) {
        return FormsModel::mdlAddObjects($data);
    }

    static public function ctrAddObject($data) {
        return FormsModel::mdlAddObject($data);
    }

    static public function ctrSearchObject($idObject) {
        return FormsModel::mdlSearchObject($idObject);
    }

    static public function ctrUpdateObject($data) {
        return FormsModel::mdlUpdateObject($data);
    }

    static public function ctrDeleteObject($idObject) {
        return FormsModel::mdlDeleteObject($idObject);
    }

    static public function ctrUpdateOrderObject($position, $idObject) {
        return FormsModel::mdlUpdateOrderObject($position, $idObject);
    }

    static public function ctrGetDataObjects($idArea) {
        return FormsModel::mdlGetDataObjects($idArea);
    }

    static public function ctrGetSupervitionDays($idSupervisionDays ) {
        return FormsModel::mdlGetSupervitionDays($idSupervisionDays);
    }

    static public function ctrAddSupervition($data) {
        return FormsModel::mdlAddSupervition($data);
    }

    static public function ctrAddSupervitionAreas($data) {
        return FormsModel::mdlAddSupervitionAreas($data);
    }

    static public function ctrGetSupervitionAreas() {
        return FormsModel::mdlGetSupervitionAreas();
    }

    static public function getSupervitors() {
        return FormsModel::getSupervitors();
    }

    static public function ctrDeleteSupervitionArea($idSupervisionAreas) {
        return FormsModel::mdlDeleteSupervitionArea($idSupervisionAreas);
    }

    static public function ctrGetSupervitionDaysUser($idUser) {
        $supervitionDays = FormsModel::mdlGetSupervitionDaysUser($idUser);
        $supervitionAreas = FormsModel::mdlGetSupervitionAreaUser($idUser);
        $supervisions = [
            'daily' => $supervitionDays,
            'today' => $supervitionAreas
        ];
        return $supervisions;
        
    }

    static public function ctrUploadEvidence($data) {
        return FormsModel::mdlUploadEvidence($data);
    }

    static public function ctrFinalizarSupervision($idSupervision) {
        return FormsModel::mdlFinalizarSupervision($idSupervision);
    }

    static public function ctrGetIncidents() {
        return FormsModel::mdlGetIncidents();
    }

    static public function ctrConfirmCorrectObject($idObject, $isCorrect) {
        return FormsModel::mdlConfirmCorrectObject($idObject, $isCorrect);
    }

    static public function ctrGetObjectsBad($idArea) {
        return FormsModel::mdlGetObjectsBad($idArea);
    }

    static public function ctrGetObject($idObject) {
        return FormsModel::mdlGetObject($idObject);
    }

    static public function ctrEndEvidence($idEvidence) {
        return FormsModel::mdlEndEvidence($idEvidence);
    }

    static public function ctrEndIncident($idEvidence, $endDate, $purchaseMade, $purchaseAmount, $invoiceFileName, $evidenceFileName, $reason) {
        return FormsModel::mdlEndIncident($idEvidence, $endDate, $purchaseMade, $purchaseAmount, $invoiceFileName, $evidenceFileName, $reason);
    }

    static public function ctrGetSupervisionDay($id) {
        return FormsModel::mdlGetSupervisionDay(idSupervisionDays: $id);
    }

    static public function ctrGetSupervisionAreas($id) {
        return FormsModel::mdlGetSupervisionAreas($id);
    }

    static public function ctrEditSupervisionDay($data) {
        return FormsModel::mdlEditSupervisionDay($data);
    }

    static public function ctrEditSupervisionAreas($data) {
        return FormsModel::mdlEditSupervisionAreas($data);
    }
}