<?php 

class FormsController {
    static public function ctrSearchUsers($email){
        return FormsModel::mdlSearchUsers($email);
    }
    static public function ctrSearchschools(){
        return FormsModel::mdlSearchschools();
    }

    static public function ctrRegisterSchool($nameSchool){
        return FormsModel::mdlRegisterSchool($nameSchool);
    }

    static public function ctrRegisterZone($nameZone, $idSchool){
        return FormsModel::mdlRegisterZone($nameZone, $idSchool);
    }

    static public function ctrSearchZones($idSchool){
        return FormsModel::mdlSearchZones($idSchool);
    }

}