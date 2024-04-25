<?php 

include "conection.php";

class FormsModel {

    static public function mdlSearchUsers($item, $value) {
        try {
            $pdo = Conexion::conectar();
            if ($item != null) {
                $stmt = $pdo->prepare("SELECT * FROM servicios_users WHERE $item = :$item");
                $stmt->bindParam(":$item", $value);
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

    static public function mdlSearchschools($item,$value){
        try {
            $pdo = Conexion::conectar();
            
            if ($item != null) {
                $stmt = $pdo->prepare("SELECT * FROM servicios_schools WHERE $item = :$item");
                $stmt->bindParam(":$item", $value);
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    return false;
                }
            } else {
                $stmt = $pdo->prepare('SELECT * FROM servicios_schools');
            
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

    static public function mdlRegisterUser($data){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('INSERT INTO servicios_users (name, email, password, level) VALUES (:name, :email, :password, :level)');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
            $stmt->bindParam(':level', $data['level'], PDO::PARAM_INT);
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

    static public function mdlEditUser($data){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('UPDATE servicios_users SET name = :name, email = :email, level = :level WHERE idUsers = :idUsers');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':level', $data['level'], PDO::PARAM_INT);
            $stmt->bindParam(':idUsers', $data['idUsers'], PDO::PARAM_INT);
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

    static public function mdlSuspendUser($idUsers){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('UPDATE servicios_users SET status = 0 WHERE idUsers = :idUsers');
            $stmt->bindParam(':idUsers', $idUsers, PDO::PARAM_INT);
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

    static public function mdlActivateUser($idUsers){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('UPDATE servicios_users SET status = 1 WHERE idUsers = :idUsers');
            $stmt->bindParam(':idUsers', $idUsers, PDO::PARAM_INT);
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

    static public function mdlEditSchool($data){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('UPDATE servicios_schools SET nameSchool = :nameSchool WHERE idSchool = :idSchool');
            $stmt->bindParam(':nameSchool', $data['nameSchool'], PDO::PARAM_STR);
            $stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
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

}