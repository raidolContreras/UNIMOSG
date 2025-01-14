<?php
    // Obtener el nombre del archivo actual sin extensión ni parámetros
    $current_page = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'inicio';
?>

<div class="row">
    <div class="col-md-3 col-xl-2 px-0 sidebar sidebar-collapse pt-2">
		<div class="container">
			<a class="navbar-brand" href="./">
				<img src="view/assets/images/logo.png" alt="Logo" class="logo">
			</a>
            <nav class="navbar">
                <div class="row schools px-4" style="padding-right: 0 !important;">
                    <?php if ($_SESSION['level'] == 0): ?>
                        <a href="schools" class="mt-3 menu-top py-2 <?php echo ($current_page == 'schools') ? 'active' : ''; ?>">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-school"></i> 
                                </div>
                                <div class="col-8">Planteles</div> 
                            </div>
                        </a>
                        <a href="reportes" class="mt-3 menu-top py-2 <?php echo ($current_page == 'reportes') ? 'active' : ''; ?>">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-house"></i> 
                                </div>
                                <div class="col-8">Generación de reportes</div> 
                            </div>
                        </a>
                    <?php elseif ($_SESSION['level'] == 1): ?>
                        <a href="schools" class="mt-3 menu-top py-2 <?php echo ($current_page == 'schools') ? 'active' : ''; ?>">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-school"></i> 
                                </div>
                                <div class="col-8">Inicio</div> 
                            </div>
                        </a>
                        <a href="supervicion" class="mt-3 menu-top py-2 <?php echo ($current_page == 'supervicion') ? 'active' : ''; ?>">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-user-hard-hat"></i> 
                                </div>
                                <div class="col-8">Gestión de supervisores</div> 
                            </div>
                        </a>
                        <a href="reportes" class="mt-3 menu-top py-2 <?php echo ($current_page == 'reportes') ? 'active' : ''; ?>">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-house"></i> 
                                </div>
                                <div class="col-8">Generación de incidencias</div> 
                            </div>
                        </a>
                        <a href="mySupervition" class="mt-3 menu-top py-2 <?php echo ($current_page == 'mySupervition') ? 'active' : ''; ?>">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa-duotone fa-house"></i> 
                                </div>
                                <div class="col-8">Supervición</div> 
                            </div>
                        </a>
                    <?php else: ?>
                        <a href="lista" class="mt-3 menu-top py-2 <?php echo ($current_page == 'lista') ? 'active' : ''; ?>">
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
