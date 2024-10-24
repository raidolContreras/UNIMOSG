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
				$sql .+ " WHERE $item = :$item";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(":$item", $value);
				if ($stmt->execute() && $stmt->rowCount() > 0) {
					return $stmt->fetch();
				} else {
					return false;
				}
			} else {
				$stmt = $pdo->prepare($sql);

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
					$stmt = $pdo->prepare('SELECT o.idObject, o.nameObject, o.cantidad, o.statusObject, o.objects_idArea, a.nameArea, z.nameZone, s.nameSchool
												FROM servicios_objects o
												LEFT JOIN servicios_areas a ON a.idArea = o.objects_idArea
												LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
												LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool
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
			if ( $idZone != null) {
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
			} else {
				$stmt = $pdo->prepare("	SELECT a.*, s.nameSchool, z.nameZone FROM servicios_areas a
											LEFT JOIN servicios_zones z ON z.idZone = a.area_idZones
											LEFT JOIN servicios_schools s ON s.idSchool = z.zone_idSchool
										WHERE $item = :$item");
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

	static public function mdlDeleteArea($idArea) {
		try {
            $pdo = Conexion::conectar();
			$stmt = $pdo->prepare("UPDATE servicios_areas SET statusArea = 0 WHERE idArea = :idArea");
			$stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
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
			if	($idArea != null) {
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
			} else {
				$stmt = $pdo->prepare("SELECT * FROM servicios_objects WHERE $item = :$item");
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

	static public function mdlSelectObjectsbyAreas($idArea) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare('SELECT * FROM servicios_objects WHERE objects_idArea = :idArea');
            $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al buscar los objetos: " . $e->getMessage());
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
			$stmt = $pdo->prepare('UPDATE servicios_users SET name = :name, email = :email, phone = :phone, level = :level WHERE idUsers = :idUsers');
			$stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
			$stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $data['phone'], PDO::PARAM_INT);
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

	static public function mdlDeleteUser($idUser) {
		try {
            $pdo = Conexion::conectar();
			$stmt = $pdo->prepare('DELETE FROM servicios_users WHERE idUsers = :idUsers');
            $stmt->bindParam(':idUsers', $idUser, PDO::PARAM_INT);
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

	static public function mdlRegisterObjects($data) {
		try {
			$pdo = Conexion::conectar();
	
			// Iniciamos una transacción para asegurar la atomicidad
			$pdo->beginTransaction();
	
			// Construimos la consulta SQL para insertar múltiples registros de una vez
			$sql = 'INSERT INTO servicios_objects (nameObject, objects_idArea, cantidad) VALUES ';
			$values = [];
			$params = [];
	
			foreach ($data as $index => $object) {
				$nameObject = $object['nameObject'];
				$idArea = $object['objects_idArea'];
				$cantidad = $object['cantidad'];
	
				// Usamos placeholders dinámicos para los valores de cada fila
				$values[] = "(?, ?, ?)";
				$params[] = $nameObject;
				$params[] = $idArea;
				$params[] = $cantidad;
			}
	
			// Unimos todas las consultas en una sola
			$sql .= implode(", ", $values);
	
			// Preparamos la consulta
			$stmt = $pdo->prepare($sql);
	
			// Ejecutamos la consulta con los valores concatenados
			if ($stmt->execute($params)) {
				// Confirmamos la transacción si todo salió bien
				$pdo->commit();
				return 'ok';
			} else {
				// Si algo falla, hacemos rollback
				$pdo->rollBack();
				return 'error';
			}
		} catch (PDOException $e) {
			// En caso de error, hacemos rollback de la transacción
			$pdo->rollBack();
			error_log("Error al registrar los objetos: " . $e->getMessage());
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
											LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool
											WHERE a.statusArea = 1');
				} else {
					$stmt = $pdo->prepare('SELECT * FROM servicios_areas a 
											LEFT JOIN servicios_zones z ON a.area_idZones = z.idZone
											LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool
											WHERE area_idZones = :idZone AND a.statusArea = 1');
					$stmt->bindParam(':idZone', $idZone, PDO::PARAM_INT);
				}
				if ($stmt->execute() && $stmt->rowCount() > 0) {
					return $stmt->fetchAll();
				} else {
					return false;
				}
			} else {
				if ($idZone == null) {
					$stmt = $pdo->prepare("SELECT * FROM servicios_areas WHERE $item = :$item AND statusArea = 1");
				} else {
					$stmt = $pdo->prepare("SELECT * FROM servicios_areas a 
											LEFT JOIN servicios_zones z ON a.area_idZones = z.idZone
											LEFT JOIN servicios_schools s ON z.zone_idSchool = s.idSchool
											WHERE area_idZones = :idZone AND $item = :$item AND a.statusArea = 1");
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
                return 'ok';
            } else {
                return 'error';
            }
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
                return $stmt->fetchAll();
            } else {
                return false;
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
			$sql = 'INSERT INTO servicios_plan (idSchool, idZone, idArea, idSupervisor, datePlan, eventTime) VALUES (:idSchool, :idZone, :idArea, :idSupervisor, :datePlan, :eventTime)';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':idSchool', $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(':idZone', $data['idZone'], PDO::PARAM_INT);
			$stmt->bindParam(':idArea', $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(':idSupervisor', $data['idSupervisor'], PDO::PARAM_INT);
			$stmt->bindParam(':datePlan', $data['datePlan'], PDO::PARAM_STR);
			$stmt->bindParam(':eventTime', $data['eventTime'], PDO::PARAM_STR);
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

	static public function mdlUpdateObject($idObject, $name, $value) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("UPDATE servicios_objects SET $name = :value WHERE idObject = :idObject");
            $stmt->bindParam(":value", $value, PDO::PARAM_STR);
            $stmt->bindParam(":idObject", $idObject, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al actualizar el objeto: ". $e->getMessage());
            throw $e;
        }
	}

	static public function mdlAddDaySupervision($data) {
		try {
            $pdo = Conexion::conectar();
			$sql = "INSERT INTO servicios_supervision_days(idSchool, idZone, idArea, day, supervisionTime, idSupervisor) VALUES (:idSchool, :idZone, :idArea, :day, :supervisionTime, :idSupervisor)";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(":idSchool", $data['idSchool'], PDO::PARAM_INT);
			$stmt->bindParam(":idZone", $data['idZone'], PDO::PARAM_INT);
			$stmt->bindParam(":idArea", $data['idArea'], PDO::PARAM_INT);
			$stmt->bindParam(":day", $data['day'], PDO::PARAM_STR);
			$stmt->bindParam(":supervisionTime", $data['supervisionTime'], PDO::PARAM_STR);
			$stmt->bindParam(":idSupervisor", $data['idSupervisor'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $pdo->lastInsertId();
            } else {
                return 'error';
            }
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
                return 'ok';
            } else {
                return 'error';
            }
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
					SUM(CASE WHEN i.importancia = 'Inmediata' AND i.status = 1 THEN 1 ELSE 0 END) AS InmediatoComplete
				FROM 
					servicios_incidentes i;";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$return = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt = null;
		return $return;
	}

	static public function mdlSendNotify() {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT * FROM servicios_notify WHERE status = 0");
			$stmt->execute();
			$return = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt = null;
			return $return;
        } catch (PDOException $e) {
            error_log("Error al enviar notificaciones: ". $e->getMessage());
            throw $e;
        }
	}

	static public function mdlUpdateNotify($idNotify) {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("UPDATE servicios_notify SET status = 1 WHERE idNotify = :idNotify");
            $stmt->bindParam(":idNotify", $idNotify, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al actualizar la notificación: ". $e->getMessage());
            throw $e;
        }
	}

	static public function mdlNewNotify($data) {
		try {
            $pdo = Conexion::conectar();
			$sql = "INSERT INTO servicios_notify(title, body, url) VALUES (:title, :body, :url)";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(":title", $data['title'], PDO::PARAM_STR);
			$stmt->bindParam(":body", $data['body'], PDO::PARAM_STR);
			$stmt->bindParam(":url", $data['url'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                return 'ok';
            } else {
                return 'error';
            }

        } catch (PDOException $e) {
            error_log("Error al actualizar la notificación: ". $e->getMessage());
            throw $e;
        }
	}

	static public function mdlSearchDirector() {
		try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT * FROM servicios_users WHERE level = 1 AND status = 1");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $e) {
            error_log("Error al actualizar la fecha de asignación: ". $e->getMessage());
            throw $e;
        }
	}
}
