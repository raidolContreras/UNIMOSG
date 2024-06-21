<header>
	<nav class="navbar">
		<div class="container">
			<?php if ($_SESSION['level'] == 2):?>
				<div class="navbar-brand">
					<img src="view/assets/images/logo.png" alt="Logo" class="logo">
					<div class="divider"></div>
					<ul class="list-group list-group-horizontal">
						<li class="nav-item">
							<a class="nav-link menu-top" href="./">Inicio</a>
						</li>
						<li class="nav-item">
							<a class="nav-link menu-top" href="planSupervisor">Plan de Supervisión</a>
						</li>
					</ul>
				</div>
				<?php else: ?>
				<a class="navbar-brand" href="./"></a>
					<img src="view/assets/images/logo.png" alt="Logo" class="logo image-nav">
				</a>
				<?php endif ?>
			<button class="navbar-toggler boton-sombra" type="button" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon one"></span>
				<span class="navbar-toggler-icon two"></span>
				<span class="navbar-toggler-icon three"></span>
			</button>
		</div>
		<div class="container navbar-hidden">
			<div id="schools" style="padding-right: 0 !important;">
				<a href="./" class="mt-3 menu-top py-2">
				<i class="fa-duotone fa-house"></i> Tablero
				</a>
				<?php if ($_SESSION['level'] == 0): ?>
					<a href="users" class="mt-3 menu-top py-2">
					<i class="fa-duotone fa-users"></i> Usuarios
					</a>
					<a href="schools" class="mt-3 menu-top py-2">
					<i class="fa-duotone fa-school"></i> Escuelas
					</a>
					<a href="zones" class="mt-3 menu-top py-2">
					<i class="fa-duotone fa-location-dot"></i> Zonas
					</a>
				<?php elseif ($_SESSION['level'] == 1): ?>
					<script src="view/assets/js/ajax/General/getSchools.js"></script>
					<script>
						function sendNotify() {
							$.ajax({
								url: 'controller/ajax/sendNotify.php',
								type: 'POST'
							});
						}

						if (Notification.permission === 'granted'){
							// Ejecutar sendNotify cada 10 segundos
							setInterval(sendNotify, 10000);
						}
						
						$(document).ready(function() {
							// Verificar el permiso de notificaciones
							<?php
								if ($_SESSION['notify'] == 0) {
									echo  "if (Notification.permission === 'default') {
												$('#notificationModal').modal('show');
											}";
								}
							?>

							// Solicitar permiso para notificaciones
							$('#allowNotifications').on('click', function() {
								Notification.requestPermission().then(function(permission) {
									if (permission === 'granted') {
										console.log('Gracias por permitir las notificaciones.');
									} else if (permission === 'denied') {
										console.log('Ha bloqueado las notificaciones para este sitio.');
									}
									$('#notificationModal').modal('hide');
								});
							});

							$('#denegateNotify').on('click', function() {
								<?php
									$_SESSION['notify'] = 1;
								?>
							});

							$('#denyNotifications').on('click', function() {
								<?php
									$_SESSION['notify'] = 1;
								?>
							});
						});
					</script>
				<?php else: ?>
					<a href="lista" class="mt-3 btn btn-success">
						Supervisión
					</a>
				<?php endif ?>
			</div>
		</div>
	</nav>
	
	<div class="navbar-collapse" id="navbarNav">
		<span class="close-btn" onclick="closeMenu('navbarNav')"></span>
			<?php if ($_SESSION['level'] != 2):?>
				<ul class="navbar-nav">
			<?php else: ?>
				<ul class="navbar-nav mt-5">
			<?php endif ?>
			<li class="nav-item mt-5">
				<?php echo $_SESSION['levelName'] ?>
				<h5>
					<?php echo $_SESSION['name'] ?>
				</h5>
			</li>
			<li class="nav-item">
				<a class="nav-link px-3" href="" onclick="logout()">
					<i class="fas fa-sign-out-alt"></i> Cerrar sesión
				</a>
			</li>
		</ul>
	</div>
</header>