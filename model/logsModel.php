<?php

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