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

	static public function mdlRegisterSchool($nameSchool, $chatId)
	{
		try {
			$pdo = Conexion::conectar();
			
			// Obtener el valor máximo actual de "position" y sumar 1 para el nuevo registro
			$stmt = $pdo->prepare('SELECT COALESCE(MAX(position), 0) + 1 AS next_position FROM servicios_schools');
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$nextPosition = $result['next_position'];

			// Insertar el nuevo registro con el valor de "position" calculado
			$stmt = $pdo->prepare('INSERT INTO servicios_schools (nameSchool, position, chatId) VALUES (:nameSchool, :position, :chatId)');
			$stmt->bindParam(':nameSchool', $nameSchool, PDO::PARAM_STR);
			$stmt->bindParam(':position', $nextPosition, PDO::PARAM_INT);
			$stmt->bindParam(':chatId', $chatId, PDO::PARAM_STR);

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
			$stmt = $pdo->prepare('UPDATE servicios_schools SET nameSchool = :nameSchool, chatId = :chatId WHERE idSchool = :idSchool');
			$stmt->bindParam(':nameSchool', $data['nameSchool'], PDO::PARAM_STR);
			$stmt->bindParam(':chatId', $data['chatId'], PDO::PARAM_STR);
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
						o.*
					FROM servicios_objects o
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

	static public function mdlGetObjectsBad($idArea) {
		try {
			$pdo = Conexion::conectar();

			$sql = "SELECT o.*, e.*
					FROM servicios_objects o
					LEFT JOIN servicios_evidences e
					ON o.idObject = e.idObjects
					AND e.dateCreated = (
						SELECT MAX(e2.dateCreated)
						FROM servicios_evidences e2
						WHERE e2.idObjects = o.idObject
					)
					WHERE o.object_idArea = :idArea
					AND o.status = 1
					ORDER BY o.idObject ASC;

					";

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
			$stmt = $pdo->prepare("SELECT a.nameArea, f.nameFloor, e.nameEdificer, s.nameSchool, a.idArea, f.idFloor, e.idEdificers, s.idSchool, s.chatId 
											FROM servicios_areas a
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
            $stmt = $pdo->prepare("SELECT sa.*, u.name, s.nameSchool, e.nameEdificer, f.nameFloor, a.nameArea 
					FROM servicios_supervision_areas sa
					LEFT JOIN servicios_users u ON sa.idSupervisor = u.idUsers
					LEFT JOIN servicios_schools s ON s.idSchool = sa.idSchool
					LEFT JOIN servicios_edificers e ON e.idEdificers = sa.idEdificers
					LEFT JOIN servicios_floors f ON f.idFloor = sa.idFloor
					LEFT JOIN servicios_areas a ON a.idArea = sa.idArea;");
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

	public static function mdlGetSupervisionDay($idSupervisionDays) {
		try {
			$pdo = Conexion::conectar();
			$sql = "SELECT sd.*, u.name, s.nameSchool, e.nameEdificer, f.nameFloor, a.nameArea 
					FROM servicios_supervision_days sd
					LEFT JOIN servicios_users u ON sd.idSupervisor = u.idUsers
					LEFT JOIN servicios_schools s ON s.idSchool = sd.idSchool
					LEFT JOIN servicios_edificers e ON e.idEdificers = sd.idEdificers
					LEFT JOIN servicios_floors f ON f.idFloor = sd.idFloor
					LEFT JOIN servicios_areas a ON a.idArea = sd.idArea
					WHERE sd.idSupervisionDays = :idSupervisionDays;";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idSupervisionDays', $idSupervisionDays, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
			$stmt->closeCursor();
			$pdo = null;
	
			return $result;
	
		} catch (PDOException $e) {
			error_log("Error al obtener los días de supervisión: " . $e->getMessage());
			throw $e;
		}
	}

	public static function mdlGetSupervisionAreas($idSupervisionAreas) {
		try {
			$pdo = Conexion::conectar();
			$sql = "SELECT sa.*, u.name, s.nameSchool, e.nameEdificer, f.nameFloor, a.nameArea 
					FROM servicios_supervision_areas sa
					LEFT JOIN servicios_users u ON sa.idSupervisor = u.idUsers
					LEFT JOIN servicios_schools s ON s.idSchool = sa.idSchool
					LEFT JOIN servicios_edificers e ON e.idEdificers = sa.idEdificers
					LEFT JOIN servicios_floors f ON f.idFloor = sa.idFloor
					LEFT JOIN servicios_areas a ON a.idArea = sa.idArea
					WHERE sa.idSupervisionAreas = :idSupervisionAreas;";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idSupervisionAreas', $idSupervisionAreas, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
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
			$sql = "INSERT INTO servicios_evidences(idObjects, idUser, urgency, evidence, description, dateCreated) VALUES (:idObject, :idUser, :urgency, :evidence, :description, NOW() - INTERVAL 6 HOUR);";
			$sql .= "UPDATE servicios_objects SET isOk = 0 WHERE idObject = :idObject;";
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

	static public function mdlConfirmCorrectObject($idObject, $isCorrect) {
		try {
            $pdo = Conexion::conectar();
			$sql = "UPDATE servicios_objects SET isOk = :isCorrect WHERE idObject = :idObject;";
            $stmt = $pdo->prepare($sql);
			$stmt->bindParam(':isCorrect', $isCorrect, PDO::PARAM_INT);
			$stmt->bindParam(':idObject', $idObject, PDO::PARAM_INT);
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

	static public function mdlEndIncident($idEvidence, $endDate, $purchaseMade, $purchaseAmount, $invoiceFileName, $evidenceFileName, $reason) {
		try {
			$pdo = Conexion::conectar();
			$sql = "INSERT INTO servicios_finalizados(idEvidence, endDate, purchaseMade, purchaseAmount, invoiceFileName, evidenceFileName, reason) VALUES (:idEvidence, :endDate, :purchaseMade, :purchaseAmount, :invoiceFileName, :evidenceFileName, :reason);";
			$sql .= "UPDATE servicios_evidences SET statusEvidence = 1 WHERE idEvidence = :idEvidence;";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idEvidence', $idEvidence, PDO::PARAM_INT);
			$stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
			$stmt->bindParam(':purchaseMade', $purchaseMade, PDO::PARAM_STR);
			$stmt->bindParam(':purchaseAmount', $purchaseAmount, PDO::PARAM_STR);
			$stmt->bindParam(':invoiceFileName', $invoiceFileName, PDO::PARAM_STR);
			$stmt->bindParam(':evidenceFileName', $evidenceFileName, PDO::PARAM_STR);
			$stmt->bindParam(':reason', $reason, PDO::PARAM_STR);

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
			error_log("Error al finalizar el incidente: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlEndEvidence($idEvidence) {
		try {
            $pdo = Conexion::conectar();
            $sql = "UPDATE servicios_evidences SET statusEvidence = 1 WHERE idEvidence = :idEvidence;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idEvidence', $idEvidence, PDO::PARAM_INT);
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
			error_log("Error al finalizar la evidencia: ". $e->getMessage());
			throw $e;
		}
	}

	static public function mdlEditSupervisionDay($data) {
		try {
            $pdo = Conexion::conectar();
			$stmt = $pdo->prepare("
				UPDATE servicios_supervision_days
				SET
					idSchool        = :idSchool,
					idEdificers     = :idEdificers,
					idFloor         = :idFloor,
					zone            = :zone,
					idArea          = :idArea,
					day             = :day,
					supervisionTime = :supervisionTime,
					idSupervisor    = :idSupervisor
				WHERE
					idSupervisionDays = :idSupervisionDays
			");
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idEdificers', $data['idEdificers'], PDO::PARAM_INT);
			$stmt->bindParam(':idFloor', $data['idFloor'], PDO::PARAM_INT);
			$stmt->bindParam(':zone', $data['zone'], PDO::PARAM_STR);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':day', $data['day'], PDO::PARAM_INT);
			$stmt->bindParam(':supervisionTime', $data['time'], PDO::PARAM_STR);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->bindParam(':idSupervisionDays', $data['idSupervisionDays'], PDO::PARAM_INT);
			
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
            error_log("Error al editar el día de supervisión: ". $e->getMessage());
            throw $e;
        }
	}

	static public function mdlEditSupervisionAreas($data) {
		try {
            $pdo = Conexion::conectar();
			$stmt = $pdo->prepare("
				UPDATE servicios_supervision_areas
				SET
					title         = :title,
					idSchool      = :idSchool,
					idEdificers   = :idEdificers,
					idFloor       = :idFloor,
					zone          = :zone,
					idArea        = :idArea,
					day           = :day,
					idSupervisor  = :idSupervisor,
					time          = :time
				WHERE
					idSupervisionAreas = :idSupervisionAreas
			");
			$stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idEdificers', $data['idEdificers'], PDO::PARAM_INT);
			$stmt->bindParam(':idFloor', $data['idFloor'], PDO::PARAM_INT);
			$stmt->bindParam(':zone', $data['zone'], PDO::PARAM_STR);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':day', $data['day'], PDO::PARAM_STR);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->bindParam(':time', $data['time'], PDO::PARAM_STR);
			$stmt->bindParam(':idSupervisionAreas', $data['idSupervisionAreas'], PDO::PARAM_INT);

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
            error_log("Error al editar el día de supervisión: ". $e->getMessage());
            throw $e;
        }
	}
}