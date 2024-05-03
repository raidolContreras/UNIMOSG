<div class="row">
    <div class="col-md-3 col-xl-2 px-0 sidebar sidebar-collapse">
		<div class="container">
			<a class="navbar-brand" href="./">
				<img src="view/assets/images/logo.png" alt="Logo" class="logo">
			</a>
            <nav class="navbar">
                <div class="row schools px-4" style="padding-right: 0 !important;">
                    <a href="./" class="col-xl-12 col-xs-3 mt-3 btn btn-success">
                    <i class="fa-duotone fa-house"></i> Tablero
                    </a>
                    <?php if ($_SESSION['level']== 0):?>
                        <a href="users" class="col-xl-12 col-xs-3 mt-3 btn btn-success">
                        <i class="fa-duotone fa-users"></i> Usuarios
                        </a>
                        <a href="schools" class="col-xl-12 col-xs-3 mt-3 btn btn-success">
                        <i class="fa-duotone fa-school"></i> Escuelas
                        </a>
                        <a href="zones" class="col-xl-12 col-xs-3 mt-3 btn btn-success">
                            <i class="fa-duotone fa-location-dot"></i> Zonas
                        </a>
                    <?php elseif ($_SESSION['level']== 1):?>
                    
                        <script src="view/assets/js/ajax/General/getSchools.js"></script>
                    <?php else: ?>
                        <a href="lista" class="col-xl-12 col-xs-3 mt-3 btn btn-success">
                            Supervición
                        </a>
                    <?php endif ?>
                </div>
            </nav>
	    </div>
    </div>
    <div class="col-lg-3 col-xl-2 "></div>
    <div class="col-md-12 col-lg-9 col-xl-10 p-4">