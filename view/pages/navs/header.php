<?php
    // Obtener el nombre del archivo actual sin extensión ni parámetros
    $current_page = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'inicio';
?>
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
				<input type="hidden" id="idUser" value="<?php echo $_SESSION['idUser'] ?>">
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
			<div id="schools" style="display: flex; justify-content: space-evenly;">
				<a href="./" class="mt-3 menu-top py-2 <?php echo ($current_page == 'inicio') ? 'active' : ''; ?>">
				<i class="fa-duotone fa-house"></i> Tablero
				</a>
				<?php if ($_SESSION['level'] == 0): ?>
					<a href="users" class="mt-3 menu-top py-2 <?php echo ($current_page == 'users') ? 'active' : ''; ?>">
					<i class="fa-duotone fa-users"></i> Usuarios
					</a>
					<a href="schools" class="mt-3 menu-top py-2 <?php echo ($current_page == 'schools') ? 'active' : ''; ?>">
					<i class="fa-duotone fa-school"></i> Escuelas
					</a>
					<a href="zones" class="mt-3 menu-top py-2 <?php echo ($current_page == 'zones') ? 'active' : ''; ?>">
					<i class="fa-duotone fa-location-dot"></i> Zonas
					</a>
				<?php elseif ($_SESSION['level'] == 1): ?>
					<script src="view/assets/js/ajax/General/getSchools.js"></script>
				<?php else: ?>
					<a href="lista" class="mt-3 menu-top <?php echo ($current_page == 'lista') ? 'active' : ''; ?>">
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