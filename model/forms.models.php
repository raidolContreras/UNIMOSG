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
            error_log("Error al buscar el evento: " . $e->getMessage());
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
                $stmt = $pdo->prepare('SELECT * FROM servicios_schools WHERE status = 1');
            
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
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
                return $pdo->lastInsertId();
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al registrar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlSearchZones($idSchool, $item, $value) {
        try {
            $pdo = Conexion::conectar();
            if ($item == null) {
                if ($idSchool == null || $idSchool == 0) {
                    $stmt = $pdo->prepare('SELECT * FROM servicios_zones z LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool');
                } else {
                    $stmt = $pdo->prepare('SELECT * FROM servicios_zones z LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool WHERE zone_idSchool = :idSchool');
                    $stmt->bindParam(':idSchool', $idSchool, PDO::PARAM_INT);
                }
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                } else {
                    return false;
                }
            } else {
                if  ($idSchool == null) {
                    $stmt = $pdo->prepare("SELECT * FROM servicios_zones WHERE $item = :$item");
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM servicios_zones WHERE zone_idSchool = :idSchool AND $item = :$item");
                    $stmt->bindParam(':idSchool', $idSchool, PDO::PARAM_INT);
                }
                $stmt->bindParam(":$item", $value);
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlSearchObjects($idArea, $item, $value) {
        try {
            $pdo = Conexion::conectar();
            if ($item == null) {
                if ($idArea == null || $idArea == 0) {
                    $stmt = $pdo->prepare('SELECT o.idObject, o.nameObject, o.cantidad, o.statusObject, o.objects_idArea, a.nameArea, z.nameZone, s.nameSchool FROM servicios_objects o
                                            LEFT JOIN servicios_areas a ON a.idArea = o.objects_idArea
                                            LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
                                            LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool;');
                } else {
                    $stmt = $pdo->prepare('SELECT o.idObject, o.nameObject, o.cantidad, o.statusObject, o.objects_idArea, a.nameArea, z.nameZone, s.nameSchool, i.* FROM servicios_objects o
                                                LEFT JOIN servicios_areas a ON a.idArea = o.objects_idArea
                                                LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
                                                LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool
                                                LEFT JOIN servicios_incidentes i ON i.incidente_idObject = o.idObject
                                            WHERE objects_idArea = :idArea');
                    $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
                }
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                } else {
                    return false;
                }
            } else {
                if  ($idArea == null) {
                    $stmt = $pdo->prepare("SELECT * FROM servicios_objects WHERE $item = :$item");
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM servicios_objects WHERE objects_idArea = :idArea AND $item = :$item");
                    $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
                }
                $stmt->bindParam(":$item", $value);
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlSearchArea($idZone, $item, $value) {
        try {
            $pdo = Conexion::conectar();
            if ($item == null) {
                $stmt = $pdo->prepare('SELECT * FROM servicios_areas WHERE area_idZones = :idZone');
                $stmt->bindParam(':idZone', $idZone, PDO::PARAM_INT);
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                } else {
                    return false;
                }
            } else {
                $stmt = $pdo->prepare("SELECT * FROM servicios_areas WHERE area_idZones = :idZone AND $item = :$item");
                $stmt->bindParam(':idZone', $idZone, PDO::PARAM_INT);
                $stmt->bindParam(":$item", $value);
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlSearchObject($idArea, $item, $value) {
        try {
            $pdo = Conexion::conectar();
            if ($item == null) {
                $stmt = $pdo->prepare('SELECT * FROM servicios_objects WHERE objects_idArea = :idArea');
                $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                } else {
                    return false;
                }
            } else {
                $stmt = $pdo->prepare("SELECT * FROM servicios_objects WHERE objects_idArea = :idArea AND $item = :$item");
                $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
                $stmt->bindParam(":$item", $value);
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
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

    static public function mdlRegisterArea($nameArea, $idZone){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('INSERT INTO servicios_areas (nameArea, area_idZones) VALUES (:nameArea, :idZone)');
            $stmt->bindParam(':nameArea', $nameArea, PDO::PARAM_STR);
            $stmt->bindParam(':idZone', $idZone, PDO::PARAM_INT);
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

    static public function mdlRegisterObject($nameObject, $cantidad, $idArea) {
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('INSERT INTO servicios_objects (nameObject, objects_idArea, cantidad) VALUES (:nameObject, :idArea, :cantidad)');
            $stmt->bindParam(':nameObject', $nameObject, PDO::PARAM_STR);
            $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
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

    static public function mdlEditZone($data){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('UPDATE servicios_zones SET nameZone = :nameZone WHERE idZone = :idZone');
            $stmt->bindParam(':nameZone', $data['nameZone'], PDO::PARAM_STR);
            $stmt->bindParam(':idZone', $data['idZone'], PDO::PARAM_INT);
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

    static public function mdlSearchAreas($idZone, $item, $value){
        try {
            $pdo = Conexion::conectar();
            if ($item == null) {
                if  ($idZone == null) {
                    $stmt = $pdo->prepare('SELECT * FROM servicios_areas a 
                                            LEFT JOIN servicios_zones z ON a.area_idZones = z.idZone
                                            LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool');
                } else {
                    $stmt = $pdo->prepare('SELECT * FROM servicios_areas a 
                                            LEFT JOIN servicios_zones z ON a.area_idZones = z.idZone
                                            LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool
                                            WHERE area_idZones = :idZone');
                    $stmt->bindParam(':idZone', $idZone, PDO::PARAM_INT);
                }
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                } else {
                    return false;
                }
            } else {
                if  ($idZone == null) {
                    $stmt = $pdo->prepare("SELECT * FROM servicios_areas WHERE $item = :$item");
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM servicios_areas a 
                                            LEFT JOIN servicios_zones z ON a.area_idZones = z.idZone
                                            LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool
                                            WHERE area_idZones = :idZone AND $item = :$item");
                    $stmt->bindParam(':idZone', $idZone, PDO::PARAM_INT);
                }
                $stmt->bindParam(":$item", $value);
                if ($stmt->execute() && $stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    return false;
                }
            }

        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlSendForm($idObject, $estado, $description, $importancia){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('INSERT INTO servicios_incidentes (estado, description, importancia, incidente_idObject ) VALUES (:estado, :description, :importancia, :idObject)');
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':importancia', $importancia, PDO::PARAM_STR);
            $stmt->bindParam(':idObject', $idObject, PDO::PARAM_INT);
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

    static public function mdlSearchSolicitudes($idSchool,$importancia){
        try {
            $pdo = Conexion::conectar();
            $sql = "SELECT i.*, o.nameObject, o.idObject, a.nameArea, a.idArea, z.idZone, z.nameZone, s.idSchool, s.nameSchool
                    FROM servicios_incidentes i 
                        LEFT JOIN servicios_objects o ON o.idObject = i.incidente_idObject
                        LEFT JOIN servicios_areas a ON a.idArea = o.objects_idArea
                        LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
                        LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool
                    WHERE i.status = 0 AND s.idSchool = :idSchool AND i.importancia = :importancia;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idSchool', $idSchool, PDO::PARAM_INT);
            $stmt->bindParam(':importancia', $importancia, PDO::PARAM_STR);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlDeleteSchool($idSchool){
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('UPDATE servicios_schools SET status = 0 WHERE idSchool = :idSchool');
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

    static public function mdlSearchIncidentes($idIncidente) {
        try {
            $pdo = Conexion::conectar();
            $sql = 'SELECT * FROM servicios_incidentes i
                        LEFT JOIN servicios_objects o ON o.idObject = i.incidente_idObject
                    WHERE idIncidente = :idIncidente';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idIncidente', $idIncidente, PDO::PARAM_INT);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                return $stmt->fetch();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
            throw $e;
        }
    }

    static public function mdlDetailsCorrect($data) {
        try {
            $pdo = Conexion::conectar();
            $sql = "UPDATE servicios_incidentes SET detallesCorregidos = :detallesCorregidos, compra =:compra, detalleCompra = :detalleCompra, status = 1 WHERE idIncidente = :idIncidente";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':detallesCorregidos', $data['detallesCorregidos'], PDO::PARAM_STR);
            $stmt->bindParam(':compra', $data['compra'], PDO::PARAM_INT);
            $stmt->bindParam(':detalleCompra', $data['detalleCompra'], PDO::PARAM_STR);
            $stmt->bindParam(':idIncidente', $data['idIncidente'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al ACTUALIZAR el evento: " . $e->getMessage());
            throw $e;
        }
    }

}