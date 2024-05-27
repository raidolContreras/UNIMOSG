<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/vendor/autoload.php';

include "conection.php";

class FormsModel
{

	static public function mdlSearchUsers($item, $value)
	{
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

	static public function mdlSearchschools($item, $value)
	{
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

	static public function mdlRegisterSchool($nameSchool)
	{
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

	static public function mdlRegisterZone($nameZone, $idSchool)
	{
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

	static public function mdlSearchZones($idSchool, $item, $value)
	{
		try {
			$pdo = Conexion::conectar();
			if ($item == null) {
				if ($idSchool == null || $idSchool == 0) {
					$stmt = $pdo->prepare('SELECT * FROM servicios_zones z LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool WHERE z.status = 1');
				} else {
					$stmt = $pdo->prepare('SELECT * FROM servicios_zones z LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool WHERE zone_idSchool = :idSchool AND z.status = 1');
					$stmt->bindParam(':idSchool', $idSchool, PDO::PARAM_INT);
				}
				if ($stmt->execute() && $stmt->rowCount() > 0) {
					return $stmt->fetchAll();
				} else {
					return false;
				}
			} else {
				if ($idSchool == null) {
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

	static public function mdlSearchObjects($idArea, $item, $value)
	{
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
				if ($idArea == null) {
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

	static public function mdlSearchArea($idZone, $item, $value)
	{
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

	static public function mdlGetArea($item, $value) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT * FROM servicios_areas WHERE $item = :$item");
            $stmt->bindParam(":$item", $value);
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

	static public function mdlEditArea($data) {
		try {
            $pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_areas SET nameArea = :nameArea WHERE idArea = :idArea");
			$stmt->bindParam(':nameArea', $data['nameArea'], PDO::PARAM_STR);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			if ($stmt->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
            throw $e;
        }
	}

	static public function mdlSearchObject($idArea, $item, $value)
	{
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

	static public function mdlRegisterUser($data)
	{
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

	static public function mdlEditUser($data)
	{
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

	static public function mdlSuspendUser($idUsers)
	{
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

	static public function mdlActivateUser($idUsers)
	{
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

	static public function mdlEditSchool($data)
	{
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

	static public function mdlRegisterArea($nameArea, $idZone)
	{
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

	static public function mdlRegisterObject($nameObject, $cantidad, $idArea)
	{
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

	static public function mdlEditZone($data)
	{
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

	static public function mdlSearchAreas($idZone, $item, $value)
	{
		try {
			$pdo = Conexion::conectar();
			if ($item == null) {
				if ($idZone == null) {
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
				if ($idZone == null) {
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

	static public function mdlSendForm($idObject, $estado, $description, $importancia)
	{
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

	static public function mdlSearchSolicitudes($idSchool, $importancia)
	{
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

	static public function mdlDeleteSchool($idSchool)
	{
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

	static public function mdlSearchIncidentes($idIncidente)
	{
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

	static public function mdlDetailsCorrect($data)
	{
		try {
			$pdo = Conexion::conectar();
			$sql = "UPDATE servicios_incidentes SET detallesCorregidos = :detallesCorregidos, compra = :compra, detalleCompra = :detalleCompra, status = 1, solutionDate = NOW() WHERE idIncidente = :idIncidente";
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

	static public function mdlGetPlans($idPlan)
	{
		try {
			$pdo = Conexion::conectar();
			if ($idPlan == null) {
				$sql = 'SELECT p.idPlan, s.nameSchool, z.nameZone, a.nameArea, u.name, p.datePlan
						FROM servicios_plan p
							LEFT JOIN servicios_schools s ON s.idSchool = p.idSchool
							LEFT JOIN servicios_zones z ON z.idZone = p.idZone
							LEFT JOIN servicios_areas a ON a.idArea = p.idArea
							LEFT JOIN servicios_users u ON u.idUsers = p.idSupervisor;';
				$stmt = $pdo->prepare($sql);
				if ($stmt->execute() && $stmt->rowCount() > 0) {
					return $stmt->fetchAll();
				} else {
					return false;
				}
			} else {
				$sql = 'SELECT p.*, s.nameSchool, z.nameZone, a.nameArea, u.name
						FROM servicios_plan p
							LEFT JOIN servicios_schools s ON s.idSchool = p.idSchool
							LEFT JOIN servicios_zones z ON z.idZone = p.idZone
							LEFT JOIN servicios_areas a ON a.idArea = p.idArea
							LEFT JOIN servicios_users u ON u.idUsers = p.idSupervisor
						WHERE p.idPlan = :idPlan';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
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

	static public function mdlAddPlans($data)
	{
		try {
			$pdo = Conexion::conectar();
			$sql = 'INSERT INTO servicios_plan (idSchool, idZone, idArea, idSupervisor, datePlan) VALUES (:idSchool, :idZone, :idArea, :idSupervisor, :datePlan)';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idZone', $data['idZone'], PDO::PARAM_INT);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->bindParam(':datePlan', $data['datePlan'], PDO::PARAM_STR);
			if ($stmt->execute()) {
				return $pdo->lastInsertId();
			} else {
				return 'error';
			}
		} catch (PDOException $e) {
			error_log("Error al buscar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlEditPlans($data)
	{
		try {
			$pdo = Conexion::conectar();
			$sql = 'UPDATE servicios_plan SET idSchool = :idSchool, idZone = :idZone, idArea = :idArea, idSupervisor = :idSupervisor, datePlan = :datePlan WHERE idPlan = :idPlan';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idPlan', $data['idPlan'], PDO::PARAM_INT);
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idZone', $data['idZone'], PDO::PARAM_INT);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->bindParam(':datePlan', $data['datePlan'], PDO::PARAM_STR);
			if ($stmt->execute()) {
				return $data['idPlan'];
			} else {
				return 'error';
			}
		} catch (PDOException $e) {
			error_log("Error al buscar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlDeletePlans($idPlan)
	{
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare('DELETE FROM servicios_plan WHERE idPlan = :idPlan');
			$stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
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

	static function mdlSendMail($responses)
	{
		// Configuración del correo HTML
		$email = '
		<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<style>
				body {
					font-family: Arial, sans-serif;
					margin: 0;
					padding: 0;
					background-color: #f4f4f4;
					color: #333;
				}
				.container {
					width: 100%;
					max-width: 600px;
					margin: 20px auto;
					background-color: #ffffff;
					border: 1px solid #ddd;
					border-radius: 8px;
					overflow: hidden;
					box-shadow: 0 4px 8px rgba(0,0,0,0.1);
				}
				.header {
					background-color: #01643d;
					color: #ffffff;
					padding: 20px;
					text-align: center;
				}
				.header img {
					width: 120px;
					max-width: 100%;
					height: auto;
					margin-bottom: 10px;
				}
				.header h1 {
					margin: 0;
					font-size: 24px;
					font-weight: bold;
				}
				.header p {
					margin: 0;
					font-size: 16px;
				}
				.content {
					padding: 20px;
				}
				.section {
					margin-bottom: 20px;
				}
				.section h2 {
					color: #01643d;
					font-size: 20px;
					margin-bottom: 10px;
					border-bottom: 2px solid #01643d;
					padding-bottom: 5px;
				}
				.task-list {
					list-style: none;
					padding: 0;
					margin: 0;
				}
				.task-list li {
					padding: 10px 0;
					border-bottom: 1px solid #ddd;
					display: flex;
					flex-direction: column;
				}
				.task-list li:last-child {
					border-bottom: none;
				}
				.task-details {
					display: flex;
					justify-content: space-between;
					align-items: center;
					font-size: 14px;
					margin-top: 5px;
				}
				.task-details span {
					display: block;
				}
				.importance {
					font-weight: bold;
					color: #01643d;
				}
				.footer {
					background-color: #01643d;
					color: #ffffff;
					text-align: center;
					padding: 15px;
					font-size: 14px;
				}
				@media screen and (max-width: 600px) {
					.header, .content, .footer {
						padding: 15px;
					}
					.section h2 {
						margin: 0 -15px 10px -15px;
						padding: 10px 15px;
						border-bottom-width: 1px;
					}
					.task-list li {
						padding: 10px 0;
					}
					.task-details {
						flex-direction: column;
						align-items: flex-start;
					}
				}
			</style>
		</head>
		<body>
			<div class="container">
				<div class="header">
					<img src="/view/assets/images/logo.png" alt="UNIMO Logo">
					<h1>Informe de Pendientes</h1>
					<p>Universidad Montrer (UNIMO)</p>
				</div>
				<div class="content">
					<div class="section">';
					foreach ($responses as $response) {
						$email .= '
							<h2>' . htmlspecialchars($response['area']) . '</h2>
							<ul class="task-list">';
							$email .= '
								<li>
									' . htmlspecialchars($response['descripcion']) . '
									<div class="task-details">
										<span>Fecha solicitada: ' . htmlspecialchars($response['fecha']) . '</span>  <span class="importance">' . htmlspecialchars($response['importancia']) . '</span>
									</div>
								</li>';
						$email .= '
							</ul>';
					}

				$email .= '
					</div>
				</div>
				<div class="footer">
					<p>&copy; 2024 Universidad Montrer. Todos los derechos reservados.</p>
				</div>
			</div>
		</body>
		</html>';

		// Configuración del correo
		$mail = new PHPMailer(true);

		try {
			// Configuración del servidor SMTP
			$mail->isSMTP();
			$mail->Host = 'smtp.hostinger.com'; // Cambia esto al servidor SMTP que estés usando
			$mail->SMTPAuth = true;
			$mail->Username = 'unimontrer@contreras-flota.click'; // Cambia esto a tu dirección de correo electrónico
			$mail->Password = 'fjz6GG5l7ly{'; // Cambia esto a tu contraseña de correo electrónico
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port = 587;

			// Configuración del remitente y destinatario
			$mail->setFrom('unimontrer@contreras-flota.click', 'UNIMO');
			$mail->addAddress('oscarcontrerasf91@gmail.com');

			// Contenido del correo
			$mail->isHTML(true);
			$mail->Subject = 'Correo de prueba';
			$mail->Body    = $email;
			$mail->AltBody = 'Este es el contenido del correo electrónico en texto plano para los clientes de correo que no soportan HTML.';

			$mail->send();
			return 'El correo ha sido enviado correctamente';
		} catch (Exception $e) {
			return "El correo no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
		}
	}

	static public function mdlSearchIncidentsDaily(){
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("	SELECT 
										i.idIncidente, i.dateCreated AS fecha, i.description, i.importancia, o.nameObject, a.nameArea AS area, z.nameZone,
										GROUP_CONCAT(u.email SEPARATOR ', ') AS emails
									FROM 
										servicios_incidentes i
									LEFT JOIN 
										servicios_objects o ON o.idObject = i.incidente_idObject
									LEFT JOIN 
										servicios_areas a ON a.idArea = o.objects_idArea
									LEFT JOIN 
										servicios_zones z ON z.idZone = a.area_idZones
									LEFT JOIN 
										servicios_users u ON u.level = 1
									WHERE i.status = 0
									GROUP BY 
									i.idIncidente;
								");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar el evento: " . $e->getMessage());
            throw $e;
        }
	}

	static public function mdlDeleteZone($idZone) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("UPDATE servicios_zones SET status = 0 WHERE idZone = :idZone");
            $stmt->bindParam(":idZone", $idZone, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al eliminar la zona: ". $e->getMessage());
            throw $e;
        }
	}
}
