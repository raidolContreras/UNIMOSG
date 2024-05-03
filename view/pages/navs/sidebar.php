<div class="row">
    <div class="col-md-3 col-xl-2 px-0 sidebar sidebar-collapse">
		<div class="container">
			<a class="navbar-brand" href="./">
				<img src="view/assets/images/logo.png" alt="Logo" class="logo">
			</a>
            <nav class="navbar">
                <div class="row schools" style="padding-right: 0 !important;">
                    <a href="./" class="col-xl-12 col-xs-3 mt-3 btn btn-success">
                        Tablero
                    </a>
                    <?php if ($_SESSION['level']== 0):?>
                        <a href="users" class="mt-3 col-xl-12 col-xs-3 mt-3 btn btn-success">
                            Usuarios
                        </a>
                        <a href="schools" class="mt-3 col-xl-12 col-xs-3 mt-3 btn btn-success">
                            Escuelas
                        </a>
                        <a href="zones" class="mt-3 col-xl-12 col-xs-3 mt-3 btn btn-success">
                            Zonas
                        </a>
                    <?php elseif ($_SESSION['level']== 1):?>
                    
                        <script src="view/assets/js/ajax/General/getSchools.js"></script>
                    <?php else: ?>
                        <a href="lista" class="mt-3 col-xl-12 col-xs-3 mt-3 btn btn-success">
                            Supervición
                        </a>
                    <?php endif ?>
                </div>
            </nav>
	    </div>
    </div>
    <div class="col-lg-3 col-xl-2 "></div>
    <div class="col-md-12 col-lg-9 col-xl-10 p-4">