<?php 

include "conection.php";

class FormsModel {
    static public function mdlSearchUsers($email) {
        try {
            $pdo = Conexion::conectar();
            if ($email != null) {
                $stmt = $pdo->prepare('SELECT * FROM servicios_users WHERE email = :email');
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    return false;
                }
            } else {
                $stmt = $pdo->prepare('SELECT * FROM servicios_users');
            
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlSearchschools(){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('SELECT * FROM servicios_schools');
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlRegisterSchool($nameSchool){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('INSERT INTO servicios_schools (nameSchool) VALUES (:nameSchool)');
            $stmt->bindParam(':nameSchool', $nameSchool, PDO::PARAM_STR);
            if ($stmt->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlRegisterZone($nameZone, $idSchool) {
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('INSERT INTO servicios_zones (nameZone, zone_idSchool) VALUES (:nameZone, :idSchool)');
            $stmt->bindParam(':nameZone', $nameZone, PDO::PARAM_STR);
            $stmt->bindParam(':idSchool', $idSchool, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlSearchZones($idSchool) {
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('SELECT * FROM servicios_zones WHERE zone_idSchool = :idSchool');
            $stmt->bindParam(':idSchool', $idSchool, PDO::PARAM_INT);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el evento: " . $e->getMessage());
            throw $e;
        }
    }

}