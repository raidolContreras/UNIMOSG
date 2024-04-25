<header>
	<nav class="navbar">
		<div class="container">
			<a class="navbar-brand" href="./">
			</a>
			<button class="navbar-toggler boton-sombra" type="button" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon one"></span>
				<span class="navbar-toggler-icon two"></span>
				<span class="navbar-toggler-icon three"></span>
			</button>
			<div class="navbar-collapse" id="navbarNav">
				<span class="close-btn" onclick="closeMenu('navbarNav')">&times;</span>
				<ul class="navbar-nav">
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
		</div>
	</nav>
</header>