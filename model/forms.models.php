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
			$sql = "SELECT * FROM servicios_users";
			$pdo = Conexion::conectar();
			if ($item != null) {
				$sql .= " WHERE $item = :$item";  // Corregido el operador de concatenación
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(":$item", $value);
				if ($stmt->execute() && $stmt->rowCount() > 0) {
					$result = $stmt->fetch();
				} else {
					$result = false;
				}
			} else {
				$stmt = $pdo->prepare($sql);

				if ($stmt->execute() && $stmt->rowCount() > 0) {
					$result = $stmt->fetchAll();
				} else {
					$result = false;
				}
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
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
					$result = $stmt->fetch();
				} else {
					$result = false;
				}
			} else {
				$stmt = $pdo->prepare('SELECT * FROM servicios_schools WHERE status = 1 ORDER BY position ASC');

				if ($stmt->execute() && $stmt->rowCount() > 0) {
					$result = $stmt->fetchAll();
				} else {
					$result = false;
				}
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al buscar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlRegisterSchool($nameSchool)
	{
		try {
			$pdo = Conexion::conectar();
			
			// Obtener el valor máximo actual de "position" y sumar 1 para el nuevo registro
			$stmt = $pdo->prepare('SELECT COALESCE(MAX(position), 0) + 1 AS next_position FROM servicios_schools');
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$nextPosition = $result['next_position'];

			// Insertar el nuevo registro con el valor de "position" calculado
			$stmt = $pdo->prepare('INSERT INTO servicios_schools (nameSchool, position) VALUES (:nameSchool, :position)');
			$stmt->bindParam(':nameSchool', $nameSchool, PDO::PARAM_STR);
			$stmt->bindParam(':position', $nextPosition, PDO::PARAM_INT);

			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al registrar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlUpdateOrderSchool($position, $idSchool) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare('UPDATE servicios_schools SET position = :position WHERE idSchool = :idSchools');
			$stmt->bindParam(':position', $position, PDO::PARAM_INT);
			$stmt->bindParam(':idSchools', $idSchool, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar el orden de la escuela: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlRegisterUser($data)
	{
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare('INSERT INTO servicios_users (name, email, password, phone, level) VALUES (:name, :email, :password, :phone, :level)');
			$stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
			$stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
			$stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $data['phone'], PDO::PARAM_INT);
			$stmt->bindParam(':level', $data['level'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al registrar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlEditUser($data)
	{
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare('UPDATE servicios_users SET name = :name, email = :email, phone = :phone, level = :level WHERE idUsers = :idUsers');
			$stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
			$stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $data['phone'], PDO::PARAM_INT);
			$stmt->bindParam(':level', $data['level'], PDO::PARAM_INT);
			$stmt->bindParam(':idUsers', $data['idUsers'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
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
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
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
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al registrar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlDeleteUser($idUser) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare('DELETE FROM servicios_users WHERE idUsers = :idUsers');
			$stmt->bindParam(':idUsers', $idUser, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
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
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al registrar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlSendForm($idObject, $estado, $description, $importancia, $filesJson) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare('INSERT INTO servicios_incidentes (estado, description, importancia, incidente_idObject, files) VALUES (:estado, :description, :importancia, :idObject, :files)');
			$stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
			$stmt->bindParam(':description', $description, PDO::PARAM_STR);
			$stmt->bindParam(':idObject', $idObject, PDO::PARAM_INT);
			$stmt->bindParam(':importancia', $importancia, PDO::PARAM_STR);
			$stmt->bindParam(':files', $filesJson, PDO::PARAM_STR);

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

	static public function mdlPedido($pedido, $informe) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare('UPDATE servicios_incidentes SET nPedido = :nPedido WHERE idIncidente = :idIncidente');
			$stmt->bindParam(':nPedido', $pedido, PDO::PARAM_STR);
			$stmt->bindParam(':idIncidente', $informe, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al registrar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlSearchSolicitudes($idSchool, $importancia) {
		try {
			$pdo = Conexion::conectar();

			// Verificar si la importancia es "Completado"
			$completado = ($importancia == 'Completado') ? true : false;

			if ($idSchool != 0) {
				if ($completado) {
					// Importancia es "Completado", buscar status igual a 1
					$sql = "SELECT i.*, o.nameObject, o.idObject, CONCAT(s.nameSchool, ' - ', z.nameZone, ' - ', a.nameArea) AS name, a.idArea, z.idZone, s.idSchool
							FROM servicios_incidentes i 
								LEFT JOIN servicios_objects o ON o.idObject = i.incidente_idObject
								LEFT JOIN servicios_areas a ON a.idArea = o.objects_idArea
								LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
								LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool
							WHERE i.status = 1 AND s.idSchool = :idSchool;";
				} else {
					// Importancia no es "Completado", usar importancia proporcionada
					$sql = "SELECT i.*, o.nameObject, o.idObject, CONCAT(s.nameSchool, ' - ', z.nameZone, ' - ', a.nameArea) AS name, a.idArea, z.idZone, s.idSchool
							FROM servicios_incidentes i 
								LEFT JOIN servicios_objects o ON o.idObject = i.incidente_idObject
								LEFT JOIN servicios_areas a ON a.idArea = o.objects_idArea
								LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
								LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool
							WHERE i.status <> 1 AND s.idSchool = :idSchool AND i.importancia = :importancia;";
				}
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':idSchool', $idSchool, PDO::PARAM_INT);
			} else {
				if ($completado) {
					// Importancia es "Completado", buscar status igual a 1
					$sql = "SELECT i.*, o.nameObject, o.idObject, CONCAT(s.nameSchool, ' - ', z.nameZone, ' - ', a.nameArea) AS name, a.idArea, z.idZone, s.idSchool
							FROM servicios_incidentes i 
								LEFT JOIN servicios_objects o ON o.idObject = i.incidente_idObject
								LEFT JOIN servicios_areas a ON a.idArea = o.objects_idArea
								LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
								LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool
							WHERE i.status = 1;";
				} else {
					// Importancia no es "Completado", usar importancia proporcionada
					$sql = "SELECT i.*, o.nameObject, o.idObject, CONCAT(s.nameSchool, ' - ', z.nameZone, ' - ', a.nameArea) AS name, a.idArea, z.idZone, s.idSchool
							FROM servicios_incidentes i 
								LEFT JOIN servicios_objects o ON o.idObject = i.incidente_idObject
								LEFT JOIN servicios_areas a ON a.idArea = o.objects_idArea
								LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
								LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool
							WHERE i.status <> 1 AND i.importancia = :importancia;";
				}
				$stmt = $pdo->prepare($sql);
			}

			if (!$completado) {
				$stmt->bindParam(':importancia', $importancia, PDO::PARAM_STR);
			}

			if ($stmt->execute() && $stmt->rowCount() > 0) {
				$result = $stmt->fetchAll();
			} else {
				$result = false;
			}
			
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
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
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
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
				$result = $stmt->fetch();
			} else {
				$result = false;
			}
			
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
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
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al ACTUALIZAR el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlGetPlans($idPlan, $user)
	{
		try {
			$pdo = Conexion::conectar();
			if ($idPlan == null) {
				$sql = 'SELECT p.idPlan, s.nameSchool, z.nameZone, a.nameArea, u.name, p.datePlan
						FROM servicios_plan p
						LEFT JOIN servicios_schools s ON s.idSchool = p.idSchool
						LEFT JOIN servicios_zones z ON z.idZone = p.idZone
						LEFT JOIN servicios_areas a ON a.idArea = p.idArea
						LEFT JOIN servicios_users u ON u.idUsers = p.idSupervisor';
				if ($user != null) {
					$sql .= ' WHERE u.idUsers = :idUsers';
				}
				$stmt = $pdo->prepare($sql);
				if ($user != null) {
					$stmt->bindParam(':idUsers', $user, PDO::PARAM_INT);
				}
				if ($stmt->execute() && $stmt->rowCount() > 0) {
					$result = $stmt->fetchAll();
				} else {
					$result = false;
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
					$result = $stmt->fetch();
				} else {
					$result = false;
				}
			}
			
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al buscar el evento: " . $e->getMessage());
			throw $e;
		}
	}
	
	static public function mdlGetDaySupervision($idSupervisionDays, $user)
{
	try {
		$pdo = Conexion::conectar();
		if ($idSupervisionDays == null) {
			$sql = 'SELECT s.nameSchool, z.nameZone, a.nameArea, u.name, sd.day, sd.idSupervisionDays
					FROM servicios_supervision_days sd
					LEFT JOIN servicios_schools s ON s.idSchool = sd.idSchool
					LEFT JOIN servicios_zones z ON z.idZone = sd.idZone
					LEFT JOIN servicios_areas a ON a.idArea = sd.idArea
					LEFT JOIN servicios_users u ON u.idUsers = sd.idSupervisor';
			if ($user != null) {
				$sql .= ' WHERE u.idUsers = :idUsers';
			}
			$stmt = $pdo->prepare($sql);
			if ($user != null) {
				$stmt->bindParam(':idUsers', $user, PDO::PARAM_INT);
			}
			if ($stmt->execute() && $stmt->rowCount() > 0) {
				$result = $stmt->fetchAll();
			} else {
				$result = false;
			}
		} else {
			$sql = 'SELECT s.nameSchool, z.nameZone, a.nameArea, u.name, sd.*
					FROM servicios_supervision_days sd
					LEFT JOIN servicios_schools s ON s.idSchool = sd.idSchool
					LEFT JOIN servicios_zones z ON z.idZone = sd.idZone
					LEFT JOIN servicios_areas a ON a.idArea = sd.idArea
					LEFT JOIN servicios_users u ON u.idUsers = sd.idSupervisor
					WHERE sd.idSupervisionDays = :idSupervisionDays';
			if ($user != null) {
				$sql .= ' AND u.idUsers = :idUsers';
			}
			$stmt = $pdo->prepare($sql);
			if ($user != null) {
				$stmt->bindParam(':idUsers', $user, PDO::PARAM_INT);
			}
			$stmt->bindParam(':idSupervisionDays', $idSupervisionDays, PDO::PARAM_INT);
			if ($stmt->execute() && $stmt->rowCount() > 0) {
				$result = $stmt->fetch();
			} else {
				$result = false;
			}
		}
		// cerrar conexión
		$stmt->closeCursor();
		$pdo = null;
		return $result;
	} catch (PDOException $e) {
		error_log("Error al buscar el evento: " . $e->getMessage());
		throw $e;
	}
}

	static public function mdlAddPlans($data)
	{
		try {
			$pdo = Conexion::conectar();
			$sql = 'INSERT INTO servicios_plan (idSchool, idZone, idArea, idSupervisor, datePlan, eventTime) VALUES (:idSchool, :idZone, :idArea, :idSupervisor, :datePlan, :eventTime)';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idZone', $data['idZone'], PDO::PARAM_INT);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->bindParam(':datePlan', $data['datePlan'], PDO::PARAM_STR);
			$stmt->bindParam(':eventTime', $data['eventTime'], PDO::PARAM_STR);
			if ($stmt->execute()) {
				$result = $pdo->lastInsertId();
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al buscar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlEditPlans($data)
	{
		try {
			$pdo = Conexion::conectar();
			$sql = 'UPDATE servicios_plan SET idSchool = :idSchool, idZone = :idZone, idArea = :idArea, idSupervisor = :idSupervisor, datePlan = :datePlan, eventTime = :eventTime WHERE idPlan = :idPlan';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idPlan', $data['idPlan'], PDO::PARAM_INT);
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idZone', $data['idZone'], PDO::PARAM_INT);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->bindParam(':datePlan', $data['datePlan'], PDO::PARAM_STR);
			$stmt->bindParam(':eventTime', $data['eventTime'], PDO::PARAM_STR);
			if ($stmt->execute()) {
				$result = $data['idPlan'];
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
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
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
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
					<img src="https://servicios.unimontrer.edu.mx/view/assets/images/logo.png" alt="UNIMO Logo">
					<h1>Informe en espera</h1>
					<p>Universidad Montrer (UNIMO)</p>
				</div>
				<div class="content">
					<div class="section">';
					$i = 0;
					foreach ($responses as $response) {
						$email .= '
							<h2>' . htmlspecialchars($response['nameSchool']) . ' - ' . htmlspecialchars($response['nameZone']) . ' - ' . htmlspecialchars($response['area']) . '</h2>
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
						$i++;
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
		
		if ($i > 0) {
			// Configuración del correo
			$mail = new PHPMailer(true);

			try {
				// Configuración del servidor SMTP
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com'; // Cambia esto al servidor SMTP que estés usando
				$mail->SMTPAuth = true;
				$mail->Username = 'no-reply@unimontrer.edu.mx'; // Cambia esto a tu dirección de correo electrónico real
				$mail->Password = 'Unimo2024$'; // Cambia esto a tu contraseña de correo electrónico real
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Port = 587;	

				// Configuración del remitente y destinatario
				$mail->setFrom('no-reply@unimontrer.edu.mx', 'UNIMO');

				$users = FormsModel::mdlSearchDirector();

				foreach ($users as $user) {
					$mail->addAddress($user['email']);
				}

				// Contenido del correo
				$mail->isHTML(true);
				$mail->Subject = 'Reporte diario UNIMO';
				$mail->Body    = $email;
				$mail->AltBody = '';

				$mail->send();
				return 'El correo ha sido enviado correctamente';
			} catch (Exception $e) {
				return "El correo no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}

	static public function mdlSendImportantMail($area, $objeto, $estado, $description, $importancia) {
		{
			// Crear un objeto DateTime con la fecha y hora actual
			$currentDateTime = new DateTime();

			// Añadir 6 horas al objeto DateTime
			$currentDateTime->modify('+6 hours');

			// Formatear la fecha y hora para que se muestre en el formato deseado
			$futureDateTime = $currentDateTime->format('Y-m-d H:i:s');
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
						<img src="https://servicios.unimontrer.edu.mx/view/assets/images/logo.png" alt="UNIMO Logo">
						<h1>Reporte ' . htmlspecialchars($importancia) . '</h1>
						<p>Universidad Montrer (UNIMO)</p>
					</div>
					<div class="content">
						<div class="section">';
							$email .= '
								<h2>' . htmlspecialchars($area['nameSchool']) . ' - ' . htmlspecialchars($area['nameZone']) . ' - ' . htmlspecialchars($area['nameArea']) . '</h2>
								<ul class="task-list">';
								$email .= '
									<li>
									' . htmlspecialchars($objeto['nameObject']) . ' - ' . htmlspecialchars($description) . '
										<div class="task-details">
											<span>Fecha solicitada: ' . htmlspecialchars($futureDateTime) . '</span> - <span class="importance">' . htmlspecialchars($importancia) . '</span>
										</div>
									</li>';
							$email .= '
								</ul>';
	
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
				$mail->Host = 'smtp.gmail.com'; // Cambia esto al servidor SMTP que estés usando
				$mail->SMTPAuth = true;
				$mail->Username = 'no-reply@unimontrer.edu.mx'; // Cambia esto a tu dirección de correo electrónico real
				$mail->Password = 'Unimo2024$'; // Cambia esto a tu contraseña de correo electrónico real
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Port = 587;	
	
				// Configuración del remitente y destinatario
				$mail->setFrom('no-reply@unimontrer.edu.mx', 'UNIMO');
				
				$users = FormsModel::mdlSearchDirector();

				foreach ($users as $user) {
					$mail->addAddress($user['email']);
				}
	
				// Contenido del correo
				$mail->isHTML(true);
				$mail->Subject = 'Reporte con importancia - ' . htmlspecialchars($importancia);
				$mail->Body    = $email;
				$mail->AltBody = '';
	
				$mail->send();
				return 'El correo ha sido enviado correctamente';
			} catch (Exception $e) {
				return "El correo no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}

	static public function mdlSearchIncidentsDaily(){
		try {
			$sql = "SELECT i.idIncidente, i.incidente_idObject, i.dateCreated AS fecha, i.description, i.importancia, o.nameObject, a.nameArea AS area, z.nameZone,
						GROUP_CONCAT(u.email SEPARATOR ', ') AS emails, s.nameSchool
					FROM 
						servicios_incidentes i
						LEFT JOIN 
							servicios_objects o ON o.idObject = i.incidente_idObject
						LEFT JOIN 
							servicios_areas a ON a.idArea = o.objects_idArea
						LEFT JOIN 
							servicios_zones z ON z.idZone = a.area_idZones
						LEFT JOIN 
							servicios_schools s ON s.idSchool = z.zone_idSchool
						LEFT JOIN 
							servicios_users u ON u.level = 1
					WHERE i.status <> 1
					GROUP BY i.idIncidente;";
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al buscar el evento: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlAddDaySupervision($data) {
		try {
			$pdo = Conexion::conectar();
			$sql = "INSERT INTO servicios_supervision_days(idArea, day, supervisionTime, idSupervisor) VALUES (:idArea, :day, :supervisionTime, :idSupervisor)";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(":idArea", $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(":day", $data['day'], PDO::PARAM_STR);
			$stmt->bindParam(":supervisionTime", $data['supervisionTime'], PDO::PARAM_STR);
			$stmt->bindParam(":idSupervisor", $data['idSupervisor'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = $pdo->lastInsertId();
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al agregar el día de supervisión: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlDeleteSupervisionDays($idSupervisionDays) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("DELETE FROM servicios_supervision_days WHERE idSupervisionDays = :idSupervisionDays");
			$stmt->bindParam(":idSupervisionDays", $idSupervisionDays, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al eliminar el día de supervisión: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlEstadisticas() {
		$pdo = Conexion::conectar();
		$sql = "SELECT 
					SUM(CASE WHEN i.importancia = 'Urgente' AND i.status = 0 THEN 1 ELSE 0 END) AS Urgente,
					SUM(CASE WHEN i.importancia = 'En espera' AND i.status = 0 THEN 1 ELSE 0 END) AS Espera,
					SUM(CASE WHEN i.importancia = 'Inmediata' AND i.status = 0 THEN 1 ELSE 0 END) AS Inmediato,
					SUM(CASE WHEN i.importancia = 'Urgente' AND i.status = 1 THEN 1 ELSE 0 END) AS UrgenteComplete,
					SUM(CASE WHEN i.importancia = 'En espera' AND i.status = 1 THEN 1 ELSE 0 END) AS EsperaComplete,
					SUM(CASE WHEN i.importancia = 'Inmediata' AND i.status = 1 THEN 1 ELSE 0 END) AS InmediatoComplete,
					(SELECT COUNT(1) FROM servicios_users WHERE status = 1) AS numberUsers,
					(SELECT COUNT(1) FROM servicios_schools WHERE status = 1) AS numberSchools,
					(SELECT COUNT(1) FROM servicios_areas WHERE statusArea = 1) AS numberAreas,
					(SELECT COUNT(1) FROM servicios_incidentes WHERE status = 0) AS numberPendients
				FROM 
					servicios_incidentes i;";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$return = $stmt->fetch(PDO::FETCH_ASSOC);
		// cerrar conexión
		$stmt->closeCursor();
		$stmt = null;
		$pdo = null;
		return $return;
	}

	static public function mdlSearchDirector() {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("SELECT * FROM servicios_users WHERE level = 1 AND status = 1");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al buscar el director: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlAsignarFecha($data) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_incidentes SET fechaAsignada = :fechaAsignada, posponerRazon = :posponerRazon, status = 2 WHERE idIncidente = :idIncidente");
			$stmt->bindParam(":fechaAsignada", $data['fechaAsignada'], PDO::PARAM_STR);
			$stmt->bindParam(":posponerRazon", $data['razon'], PDO::PARAM_STR);
			$stmt->bindParam(":idIncidente", $data['idIncidente'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar la fecha de asignación: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlRegisterEdificer($edificeName, $idSchool)
	{
		try {
			$pdo = Conexion::conectar();

			// Obtener el valor máximo actual de "position" para el "idSchool" dado y sumar 1
			$stmt = $pdo->prepare("SELECT COALESCE(MAX(position), 0) + 1 AS next_position FROM servicios_edificers WHERE edificer_idSchool = :edificer_idSchool");
			$stmt->bindParam(':edificer_idSchool', $idSchool, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$nextPosition = $result['next_position'];

			// Insertar el nuevo edificio con el valor de "position" calculado
			$stmt = $pdo->prepare("INSERT INTO servicios_edificers (nameEdificer, edificer_idSchool, position) VALUES (:nameEdificer, :edificer_idSchool, :position)");
			$stmt->bindParam(":nameEdificer", $edificeName, PDO::PARAM_STR);
			$stmt->bindParam(":edificer_idSchool", $idSchool, PDO::PARAM_INT);
			$stmt->bindParam(":position", $nextPosition, PDO::PARAM_INT);

			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al registrar el edificio: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlSearchEdificers($item, $value) {
		{
			try {
				$pdo = Conexion::conectar();
	
				if ($item != null) {
					if ($item == 'edificer_idSchool') {
						$data = array();
						$stmt = $pdo->prepare("SELECT * FROM servicios_edificers WHERE $item = :$item AND status = 1 ORDER BY position ASC");
						$stmt->bindParam(":$item", $value);

						if ($stmt->execute()) {
							$data['edificers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
						} else {
							$result = false;
						}
						// Buscar el nombre de la escuela solo si se encontraron edificios
						$stmt2 = $pdo->prepare("SELECT nameSchool FROM servicios_schools WHERE idSchool = :idSchool");
						$stmt2->bindParam(":idSchool", $value);
						$stmt2->execute();
						$schoolName = $stmt2->fetch(PDO::FETCH_ASSOC);

						$data['schoolName'] = $schoolName ? $schoolName['nameSchool'] : null;

						$result = $data;
					} else {
						$stmt = $pdo->prepare("SELECT * FROM servicios_edificers WHERE $item = :$item");
						$stmt->bindParam(":$item", $value);
						if ($stmt->execute() && $stmt->rowCount() > 0) {
							$result = $stmt->fetch();
						} else {
							$result = false;
						}
					}
				} else {
					$stmt = $pdo->prepare("SELECT * FROM servicios_edificers WHERE status = 1 ORDER BY position ASC");
					$stmt->execute();
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
				// cerrar conexión
				$stmt->closeCursor();
				$pdo = null;
				return $result;
			} catch (PDOException $e) {
				error_log("Error al buscar el edificio: " . $e->getMessage());
				throw $e;
			}
		}
	}

	static public function mdlUpdateEdificer($data) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_edificers SET nameEdificer = :nameEdificer WHERE idEdificers = :idEdificers");
			$stmt->bindParam(":nameEdificer", $data['nameEdificer'], PDO::PARAM_STR);
			$stmt->bindParam(":idEdificers", $data['idEdificers'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar el edificio: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlDeleteEdificer($idEdificers) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_edificers SET status = 0 WHERE idEdificers = :idEdificers");
			$stmt->bindParam(":idEdificers", $idEdificers, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al eliminar el edificio: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlUpdateOrderEdificer($position, $idEdificers) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_edificers SET position = :position WHERE idEdificers = :idEdificers");
			$stmt->bindParam(":position", $position, PDO::PARAM_INT);
			$stmt->bindParam(":idEdificers", $idEdificers, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar la posición del edificio: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlRegisterFloor($floorName, $idEdificers) {
		try {
			$pdo = Conexion::conectar();
	
			// Obtener el valor máximo actual de "position" para el "idEdificers" dado y sumar 1
			$stmt = $pdo->prepare("SELECT COALESCE(MAX(position), 0) + 1 AS next_position FROM servicios_floors WHERE floor_idEdificer = :floor_idEdificer");
			$stmt->bindParam(':floor_idEdificer', $idEdificers, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$nextPosition = $result['next_position'];
	
			// Insertar el nuevo piso con el valor de "position" calculado
			$stmt = $pdo->prepare("INSERT INTO servicios_floors (nameFloor, floor_idEdificer, position) VALUES (:nameFloor, :floor_idEdificer, :position)");
			$stmt->bindParam(":nameFloor", $floorName, PDO::PARAM_STR);
			$stmt->bindParam(":floor_idEdificer", $idEdificers, PDO::PARAM_INT);
			$stmt->bindParam(":position", $nextPosition, PDO::PARAM_INT);
	
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al registrar el piso: " . $e->getMessage());
			throw $e;
		}
	}
	
	static public function mdlSearchFloors($item, $value) {
		try {
			$pdo = Conexion::conectar();
	
			if ($item != null) {
				if ($item == 'floor_idEdificer') {
					$data = array();
					$stmt = $pdo->prepare("SELECT * FROM servicios_floors WHERE $item = :$item AND status = 1 ORDER BY position ASC");
					$stmt->bindParam(":$item", $value);
	
					if ($stmt->execute()) {
						$data['floors'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
					} else {
						$result = false;
					}
					// Buscar el nombre del edificio solo si se encontraron pisos
					$stmt2 = $pdo->prepare("SELECT nameEdificer FROM servicios_edificers WHERE idEdificers = :idEdificers");
					$stmt2->bindParam(":idEdificers", $value);
					$stmt2->execute();
					$EdificersName = $stmt2->fetch(PDO::FETCH_ASSOC);
	
					$data['EdificersName'] = $EdificersName ? $EdificersName['nameEdificer'] : null;

					// Buscar el nombre de la escuela a la que pertenece el edificio
					$stmt3 = $pdo->prepare("SELECT nameSchool, idSchool FROM servicios_schools WHERE idSchool = (SELECT edificer_idSchool FROM servicios_edificers WHERE idEdificers = :idEdificers)");
					$stmt3->bindParam(":idEdificers", $value);
					$stmt3->execute();
					$escuelaName = $stmt3->fetch(PDO::FETCH_ASSOC);
					
					$data['nameSchool'] = $escuelaName? $escuelaName['nameSchool'] : null;
					$data['idSchool'] = $escuelaName? $escuelaName['idSchool'] : null;
	
					$result = $data;
				} else {
					$stmt = $pdo->prepare("SELECT * FROM servicios_floors WHERE $item = :$item");
					$stmt->bindParam(":$item", $value);
					if ($stmt->execute() && $stmt->rowCount() > 0) {
						$result = $stmt->fetch();
					} else {
						$result = false;
					}
				}
			} else {
				$stmt = $pdo->prepare("SELECT * FROM servicios_floors WHERE status = 1 ORDER BY position ASC");
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al buscar el piso: " . $e->getMessage());
			throw $e;
		}
	}
	
	static public function mdlUpdateFloor($data) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_floors SET nameFloor = :nameFloor WHERE idFloor = :idFloor");
			$stmt->bindParam(":nameFloor", $data['nameFloor'], PDO::PARAM_STR);
			$stmt->bindParam(":idFloor", $data['idFloor'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
			
		} catch (PDOException $e) {
			error_log("Error al actualizar el piso: ". $e->getMessage());
			throw $e;
		}
	}
	
	static public function mdlDeleteFloor($idFloor) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_floors SET status = 0 WHERE idFloor = :idFloor");
			$stmt->bindParam(":idFloor", $idFloor, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al eliminar el piso: ". $e->getMessage());
			throw $e;
		}
	}
	
	static public function mdlUpdateOrderFloor($position, $idFloor) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_floors SET position = :position WHERE idFloor = :idFloor");
			$stmt->bindParam(":position", $position, PDO::PARAM_INT);
			$stmt->bindParam(":idFloor", $idFloor, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closecursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar la posición del piso: ". $e->getMessage());
			throw $e;
		}
	}
	
	static public function mdlGetAreasForZone($zone, $idFloor) {
		try {
			$pdo = Conexion::conectar();
			
			// Consulta para obtener las áreas
			$stmt = $pdo->prepare("SELECT * FROM servicios_areas WHERE zone = :zone AND area_idFloors = :area_idFloors AND status = 1 ORDER BY position ASC");
			$stmt->bindParam(":zone", $zone, PDO::PARAM_STR);
			$stmt->bindParam(":area_idFloors", $idFloor, PDO::PARAM_INT);
			
			if ($stmt->execute()) {
				$data['areas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				// cerrar conexión
				$stmt->closeCursor();
				$pdo = null;
				return false;
			}
	
			// Consulta para obtener el nombre del piso y el ID del edificio
			$stmt1 = $pdo->prepare("SELECT nameFloor, floor_idEdificer, idFloor FROM servicios_floors WHERE idFloor = :idFloor");
			$stmt1->bindParam(":idFloor", $idFloor);
			$stmt1->execute();
			$FloorName = $stmt1->fetch(PDO::FETCH_ASSOC);
			$data['nameFloor'] = $FloorName ? $FloorName['nameFloor'] : null;
			$data['idFloor'] = $FloorName ? $FloorName['idFloor'] : null;
	
			// Consulta para obtener el nombre del edificio
			$stmt2 = $pdo->prepare("SELECT nameEdificer, idEdificers FROM servicios_edificers WHERE idEdificers = :idEdificers");
			$stmt2->bindParam(":idEdificers", $FloorName['floor_idEdificer']);
			$stmt2->execute();
			$EdificersName = $stmt2->fetch(PDO::FETCH_ASSOC);
			$data['EdificersName'] = $EdificersName ? $EdificersName['nameEdificer'] : null;
			$data['idEdificers'] = $EdificersName ? $EdificersName['idEdificers'] : null;
	
			// Consulta para obtener el nombre de la escuela
			$stmt3 = $pdo->prepare("SELECT nameSchool, idSchool FROM servicios_schools WHERE idSchool = (SELECT edificer_idSchool FROM servicios_edificers WHERE idEdificers = :idEdificers)");
			$stmt3->bindParam(":idEdificers", $FloorName['floor_idEdificer']);
			$stmt3->execute();
			$escuelaName = $stmt3->fetch(PDO::FETCH_ASSOC);
			$data['nameSchool'] = $escuelaName ? $escuelaName['nameSchool'] : null;
			$data['idSchool'] = $escuelaName ? $escuelaName['idSchool'] : null;
	
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $data;
		} catch (PDOException $e) {
			error_log("Error al obtener las áreas para la zona y el piso: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlRegisterArea($zone, $nareaName, $idFloor) {
		try {
			$pdo = Conexion::conectar();
			// Obtener el valor máximo actual de "position" para el "idEdificers" dado y sumar 1
			$stmt = $pdo->prepare("SELECT COALESCE(MAX(position), 0) + 1 AS next_position FROM servicios_areas WHERE area_idFloors = :area_idFloors AND zone = :zone");
			$stmt->bindParam(':area_idFloors', $idFloor, PDO::PARAM_INT);
			$stmt->bindParam(":zone", $zone, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$nextPosition = $result['next_position'];
			
			$stmt = $pdo->prepare("INSERT INTO servicios_areas (zone, nameArea, area_idFloors, position) VALUES (:zone, :nameArea, :area_idFloors, :position)");
			$stmt->bindParam(":zone", $zone, PDO::PARAM_STR);
			$stmt->bindParam(":nameArea", $nareaName, PDO::PARAM_STR);
			$stmt->bindParam(":area_idFloors", $idFloor, PDO::PARAM_INT);
			$stmt->bindParam(":position", $nextPosition, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al registrar la área: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlSearchAreas($item, $value) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("SELECT * FROM servicios_areas WHERE $item = :$item AND status = 1");
			$stmt->bindParam(":$item", $value);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al buscar el área: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlUpdateArea($data) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_areas SET nameArea = :nameArea WHERE idArea = :idArea");
			$stmt->bindParam(":nameArea", $data['nameArea'], PDO::PARAM_STR);
			$stmt->bindParam(":idArea", $data['idArea'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar el área: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlDeleteArea($idArea) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_areas SET status = 0 WHERE idArea = :idArea");
			$stmt->bindParam(":idArea", $idArea, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al eliminar el área: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlUpdateOrderArea($position, $idArea) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_areas SET position = :position WHERE idArea = :idArea");
			$stmt->bindParam(":position", $position, PDO::PARAM_INT);
			$stmt->bindParam(":idArea", $idArea, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar la posición del área: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlGetObjects($idArea) {
		try {
			$pdo = Conexion::conectar();

			$sql = "SELECT 
						o.*, 
						e.*
					FROM servicios_objects o
					LEFT JOIN (
						SELECT 
							e.* 
						FROM servicios_evidences e
						INNER JOIN (
							SELECT 
								idObjects, 
								MAX(dateCreated) AS maxDate
							FROM servicios_evidences
							GROUP BY idObjects
						) latest ON e.idObjects = latest.idObjects AND e.dateCreated = latest.maxDate
					) e ON o.idObject = e.idObjects
					WHERE o.object_idArea = :idArea AND o.status = 1
					ORDER BY o.position ASC;";

			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(":idArea", $idArea, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al obtener los objetos para el área: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlGetObject($idObject) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT 
										o.*,
										UPPER(CONCAT(s.nameSchool, ' - ', e.nameEdificer, ' - ', f.nameFloor, ' - ', a.nameArea, ' - ', a.zone)) AS ubicacion_completa
									FROM 
										servicios_objects o
									LEFT JOIN 
										servicios_areas a ON a.idArea = o.object_idArea
									LEFT JOIN 
										servicios_floors f ON f.idFloor = a.area_idFloors
									LEFT JOIN 
										servicios_edificers e ON e.idEdificers = f.floor_idEdificer
									LEFT JOIN 
										servicios_schools s ON s.idSchool = e.edificer_idSchool
									WHERE 
										o.idObject = :idObject AND o.status = 1;");
            $stmt->bindParam(":idObject", $idObject, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // cerrar conexión
            $stmt->closeCursor();
            $pdo = null;
            return $result;
        } catch (PDOException $e) {
            error_log("Error al obtener el objeto: ". $e->getMessage());
            throw $e;
        }
	}

	static public function mdlAddObjects($data) {
		try {
			$pdo = Conexion::conectar();
	
			// Construir una consulta de inserción múltiple
			$placeholders = [];
			$params = [];
			foreach ($data as $index => $row) {
				$placeholders[] = "(:nameObject{$index}, :idArea{$index}, :position{$index}, :quantity{$index})";
	
				// Obtener la posición siguiente
				$stmt = $pdo->prepare("SELECT COALESCE(MAX(position), 0) + 1 AS next_position FROM servicios_objects WHERE object_idArea = :object_idArea");
				$stmt->bindParam(':object_idArea', $row['idArea'], PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$nextPosition = $result['next_position'];
	
				// Mapear valores
				$params[":nameObject{$index}"] = $row['nameObject'];
				$params[":idArea{$index}"] = $row['idArea'];
				$params[":position{$index}"] = $nextPosition;
				$params[":quantity{$index}"] = $row['quantity'];
			}
	
			$sql = "INSERT INTO servicios_objects (nameObject, object_idArea, position, quantity) VALUES " . implode(", ", $placeholders);
			$stmt = $pdo->prepare($sql);
	
			// Ejecutar la consulta con los parámetros mapeados
			if ($stmt->execute($params)) {
				
				$pdo = null;
				$result = true;
			} else {
				$result = false;
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al agregar los objetos: " . $e->getMessage());
			throw $e;
		}

	}

	static public function mdlAddObject($data) {
		try {
			$pdo = Conexion::conectar();
			// Obtener el valor máximo actual de "position" para el "idEdificers" dado y sumar 1
			$stmt = $pdo->prepare("SELECT COALESCE(MAX(position), 0) + 1 AS next_position FROM servicios_objects WHERE object_idArea = :object_idArea");
			$stmt->bindParam(':object_idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$nextPosition = $result['next_position'];

			$stmt = $pdo->prepare("INSERT INTO servicios_objects (nameObject, object_idArea, position, quantity) VALUES (:nameObject, :idArea, :position, :quantity)");
			$stmt->bindParam(":nameObject", $data['nameObject'], PDO::PARAM_STR);
			$stmt->bindParam(":idArea", $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(":position", $nextPosition, PDO::PARAM_INT);
			$stmt->bindParam(":quantity", $data['quantity'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				//retornar success con true y el idObject en un array
				$result = array('success' => true, 'idObject' => $pdo->lastInsertId());
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al agregar el objeto: ". $e->getMessage());
			throw $e;
		}

	}

	static public function mdlSearchObject($idObject) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("SELECT * FROM servicios_objects WHERE idObject = :idObject");
			$stmt->bindParam(":idObject", $idObject, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al buscar el objeto: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlUpdateObject($data) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_objects SET nameObject = :nameObject, quantity = :quantity WHERE idObject = :idObject");
			$stmt->bindParam(":nameObject", $data['nameObject'], PDO::PARAM_STR);
			$stmt->bindParam(":quantity", $data['quantity'], PDO::PARAM_INT);
			$stmt->bindParam(":idObject", $data['idObject'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar el objeto: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlDeleteObject($idObject) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_objects SET status = 0 WHERE idObject = :idObject");
			$stmt->bindParam(":idObject", $idObject, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al eliminar el objeto: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlUpdateOrderObject($position, $idObject) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_objects SET position = :position WHERE idObject = :idObject");
			$stmt->bindParam(":position", $position, PDO::PARAM_INT);
			$stmt->bindParam(":idObject", $idObject, PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al actualizar la posición del objeto: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlGetDataObjects($idArea) {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("SELECT a.nameArea, f.nameFloor, e.nameEdificer, s.nameSchool, a.idArea, f.idFloor, e.idEdificers, s.idSchool FROM servicios_areas a
											LEFT JOIN servicios_floors f ON f.idFloor = a.area_idFloors
											LEFT JOIN servicios_edificers e ON e.idEdificers = f.floor_idEdificer
											LEFT JOIN servicios_schools s ON s.idSchool = e.edificer_idSchool
											WHERE a.idArea = :idArea");
			$stmt->bindParam(":idArea", $idArea, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al obtener los objetos para el área: ". $e->getMessage());
			throw $e;
		}
	}

	public static function mdlGetSupervitionDays($idSupervisionDays = null) {
		try {
			$pdo = Conexion::conectar();
	
			if ($idSupervisionDays !== null) {
				$sql = "SELECT * FROM servicios_supervision_days 
						WHERE idSupervisionDays = :idSupervisionDays";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(":idSupervisionDays", $idSupervisionDays, PDO::PARAM_INT);
			} else {
				$sql = "SELECT (1) AS repeating, sd.*, u.name, s.nameSchool, e.nameEdificer, f.nameFloor, a.nameArea 
						FROM servicios_supervision_days sd
						LEFT JOIN servicios_users u ON sd.idSupervisor = u.idUsers
						LEFT JOIN servicios_schools s ON s.idSchool = sd.idSchool
						LEFT JOIN servicios_edificers e ON e.idEdificers = sd.idEdificers
						LEFT JOIN servicios_floors f ON f.idFloor = sd.idFloor
						LEFT JOIN servicios_areas a ON a.idArea = sd.idArea;";
				$stmt = $pdo->prepare($sql);
			}
	
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
			$stmt->closeCursor();
			$pdo = null;
	
			return $result;
	
		} catch (PDOException $e) {
			error_log("Error al obtener los días de supervisión: " . $e->getMessage());
			throw $e;
		}
	}

	static public function mdlAddSupervition($data) {
		try {
			$pdo = Conexion::conectar();
            $stmt = $pdo->prepare("INSERT INTO servicios_supervision_days(idSchool, idEdificers, idFloor, zone, idArea, day, supervisionTime, idSupervisor) VALUES (:idSchool, :idEdificers, :idFloor, :zone, :idArea, :day, :supervisionTime, :idSupervisor)");
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idEdificers', $data['idEdificers'], PDO::PARAM_INT);
			$stmt->bindParam(':idFloor', $data['idFloor'], PDO::PARAM_INT);
			$stmt->bindParam(':zone', $data['zone'], PDO::PARAM_STR);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':day', $data['day'], PDO::PARAM_STR);
			$stmt->bindParam(':supervisionTime', $data['supervisionTime'], PDO::PARAM_STR);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->execute();
			// cerrar conexión
			$stmt->closeCursor();
            $pdo = null;
            return "ok";

		} catch (\Throwable $e) {
			error_log("Error al agregar la supervisión: ". $e->getMessage());
            throw $e;
		}
	}

	static public function getSupervitors() {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("SELECT * FROM servicios_users where level = 2");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al obtener los supervisores: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlAddSupervitionAreas($data) {
		try {
			$pdo = Conexion::conectar();
            $stmt = $pdo->prepare("INSERT INTO servicios_supervision_areas(title, idSchool, idEdificers, idFloor, zone, idArea, day, idSupervisor, time) VALUES (:title, :idSchool, :idEdificers, :idFloor, :zone, :idArea, :day, :idSupervisor, :time)");
			$stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idEdificers', $data['idEdificers'], PDO::PARAM_INT);
			$stmt->bindParam(':idFloor', $data['idFloor'], PDO::PARAM_INT);
			$stmt->bindParam(':zone', $data['zone'], PDO::PARAM_STR);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':day', $data['day'], PDO::PARAM_STR);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->bindParam(':time', $data['time'], PDO::PARAM_STR);
            if ($stmt->execute()) {
                $result = 'ok';
            } else {
                $result = 'error';
            }
            // cerrar conexión
            $stmt->closeCursor();
            $pdo = null;
            return $result;
		} catch (PDOException $e) {
			error_log("Error al insertar la supervisión de áreas: ". $e->getMessage());
            throw $e;
		}
	}

	static public function mdlGetSupervitionAreas() {
		try {
			$pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT * FROM servicios_supervision_areas");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // cerrar conexión
            $stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al obtener las supervisiones de áreas: ". $e->getMessage());
            throw $e;
		}
	}

	static public function mdlDeleteSupervitionArea($idSupervisionAreas) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("DELETE FROM servicios_supervision_areas WHERE idSupervisionAreas = :idSupervisionAreas");
            $stmt->bindParam(':idSupervisionAreas', $idSupervisionAreas, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $result = 'ok';
            } else {
                $result = 'error';
            }
            // cerrar conexión
            $stmt->closeCursor();
            $pdo = null;
            return $result;
        } catch (PDOException $e) {
            error_log("Error al eliminar la supervisión de áreas: ". $e->getMessage());
            throw $e;
        }
	}

	public static function mdlGetSupervitionDaysUser($idUser) {
		try {
			$pdo = Conexion::conectar();
			$sql = "SELECT sd.*, u.name, s.nameSchool, e.nameEdificer, f.nameFloor, a.nameArea 
					FROM servicios_supervision_days sd
					LEFT JOIN servicios_users u ON sd.idSupervisor = u.idUsers
					LEFT JOIN servicios_schools s ON s.idSchool = sd.idSchool
					LEFT JOIN servicios_edificers e ON e.idEdificers = sd.idEdificers
					LEFT JOIN servicios_floors f ON f.idFloor = sd.idFloor
					LEFT JOIN servicios_areas a ON a.idArea = sd.idArea
					WHERE u.idUsers = :idUser;";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
			$stmt->closeCursor();
			$pdo = null;
	
			return $result;
	
		} catch (PDOException $e) {
			error_log("Error al obtener los días de supervisión: " . $e->getMessage());
			throw $e;
		}
	}

	public static function mdlGetSupervitionAreaUser($idUser) {
		try {
			$pdo = Conexion::conectar();
			$sql = "SELECT sa.*, u.name, s.nameSchool, e.nameEdificer, f.nameFloor, a.nameArea 
					FROM servicios_supervision_areas sa
					LEFT JOIN servicios_users u ON sa.idSupervisor = u.idUsers
					LEFT JOIN servicios_schools s ON s.idSchool = sa.idSchool
					LEFT JOIN servicios_edificers e ON e.idEdificers = sa.idEdificers
					LEFT JOIN servicios_floors f ON f.idFloor = sa.idFloor
					LEFT JOIN servicios_areas a ON a.idArea = sa.idArea
					WHERE u.idUsers = :idUser;";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
			$stmt->closeCursor();
			$pdo = null;
	
			return $result;
	
		} catch (PDOException $e) {
			error_log("Error al obtener los días de supervisión: " . $e->getMessage());
			throw $e;
		}
	}
	public static function mdlUploadEvidence($data) {
		try {
			$pdo = Conexion::conectar();
			$sql = "INSERT INTO servicios_evidences(idObjects, idUser, urgency, evidence, description) VALUES (:idObject, :idUser, :urgency, :evidence, :description);";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idObject', $data['idObject'], PDO::PARAM_INT);
			$stmt->bindParam(':idUser', $data['idUser'], PDO::PARAM_INT);
			$stmt->bindParam(':urgency', $data['urgency'], PDO::PARAM_STR);
			$stmt->bindParam(':evidence', $data['evidence'], PDO::PARAM_STR);
			$stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al subir la evidencia: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlFinalizarSupervision($idSupervision) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("UPDATE servicios_supervision_areas SET status = 1 WHERE idSupervisionAreas = :idSupervision");
            $stmt->bindParam(':idSupervision', $idSupervision, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $result = 'ok';
            } else {
                $result = 'error';
            }
            // cerrar conexión
            $stmt->closeCursor();
            $pdo = null;
            return $result;
        } catch (PDOException $e) {
            error_log("Error al finalizar la supervisión: ". $e->getMessage());
            throw $e;
        }
	}

	static public function mdlGetIncidents() {
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare("SELECT e.*, o.nameObject, a.nameArea, f.nameFloor, ed.nameEdificer, s.nameSchool FROM servicios_evidences e
											LEFT JOIN servicios_objects o ON o.idObject = e.idObjects
											LEFT JOIN servicios_areas a ON a.idArea = o.object_idArea
											LEFT JOIN servicios_floors f ON f.idFloor = a.area_idFloors
											LEFT JOIN servicios_edificers ed ON ed.idEdificers = f.floor_idEdificer
											LEFT JOIN servicios_schools s ON s.idSchool = ed.edificer_idSchool
										WHERE e.statusEvidence = 0;");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al obtener los incidentes: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlConfirmCorrectObject($idObject, $isCorrect, $idUser) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("INSERT INTO servicios_evidences(idObjects, idUser, urgency, evidence, description, statusEvidence, isOk) VALUES (:idObject, :idUser, null, null, null, null, :isOk);");
			$stmt->bindParam(':idObject', $idObject, PDO::PARAM_INT);
			$stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindParam(':isOk', $isCorrect, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $result = 'ok';
            } else {
                $result = 'error';
            }
            // cerrar conexión
            $stmt->closeCursor();
            $pdo = null;
            return $result;
        } catch (PDOException $e) {
            error_log("Error al confirmar el objeto: ". $e->getMessage());
            throw $e;
        }
	}
}

class Logs {
	static public function createLogs($userId,$action,$description) {

		// Obtener la IP del usuario
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		// Obtener el agente de usuario (dispositivo, navegador, etc.)
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		
		// Preparar e insertar el log
		$sql = "INSERT INTO servicios_logs (idUser, action, description, ip_address, user_agent) VALUES (:user_id, :action, :description, :ip_address, :user_agent)";
		try {
			$pdo = Conexion::conectar();
			$stmt = $pdo->prepare($sql);
			
			$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':action', $action, PDO::PARAM_STR);
			$stmt->bindParam(':description', $description, PDO::PARAM_STR);
			$stmt->bindParam(':ip_address', $ipAddress, PDO::PARAM_STR);
			$stmt->bindParam(':user_agent', $userAgent, PDO::PARAM_STR);

			if ($stmt->execute()) {
				$result = 'ok';
			} else {
				$result = 'error';
			}
			// cerrar conexión
			$stmt->closeCursor();
			$pdo = null;
			return $result;
		} catch (PDOException $e) {
			error_log("Error al crear los logs: ". $e->getMessage());
			throw $e;
		}
	}
}