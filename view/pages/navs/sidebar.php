<div class="row">
    <div class="col-md-3 col-xl-2 px-0 sidebar sidebar-collapse pt-2">
		<div class="container">
			<a class="navbar-brand" href="./">
				<img src="view/assets/images/logo.png" alt="Logo" class="logo">
			</a>
            <nav class="navbar">
                <div class="row schools px-4" style="padding-right: 0 !important;">
                    <a href="./" class="mt-3 menu-top py-2">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa-duotone fa-house"></i> 
                            </div>
                            <div class="col-8">Tablero</div> 
                        </div>
                    </a>
                    <?php if ($_SESSION['level']== 0):?>
                        <a href="users" class="mt-3 menu-top py-2">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-users"></i> 
                                </div>
                                <div class="col-8">Usuarios</div> 
                            </div>
                        </a>
                        <a href="schools" class="mt-3 menu-top py-2">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-school"></i> 
                                </div>
                                <div class="col-8">Escuelas</div> 
                            </div>
                        </a>
                        <a href="zones" class="mt-3 menu-top py-2">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-location-dot"></i> 
                                </div>
                                <div class="col-8">Zonas</div> 
                            </div>
                        </a>
                    <?php elseif ($_SESSION['level']== 1):?>
                    
                        <script src="view/assets/js/ajax/General/getSchools.js"></script>
                    <?php else: ?>
                        <a href="lista" class="mt-3 menu-top py-2">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-users"></i> 
                                </div>
                                <div class="col-8">Supervisión</div> 
                            </div>
                        </a>
                    <?php endif ?>
                </div>
            </nav>
	    </div>
    </div>
    <div class="col-lg-3 col-xl-2 "></div>
    <div class="col-md-12 col-lg-9 col-xl-10 p-4 row">