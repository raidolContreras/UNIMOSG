        <?php if ($_SESSION['level']== 0):?>
        <!-- Estructura del dashboard -->
        <div class="container-fluid p-4">
            <!-- Contenido principal -->
            <main>
                <!-- Topbar con perfil y logout -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h4 fw-bold text-muted mb-0">Tablero de Control</h1>
                </div>

                <!-- Sección de tarjetas de KPIs -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card p-4 shadow-sm border-0">
                            <div class="d-flex justify-content-between">
                                <h5 class="fw-semibold text-muted">Total Usuarios</h5>
                                <span class="text-primary numberUsers fs-4 fw-bold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-4 shadow-sm border-0">
                            <div class="d-flex justify-content-between">
                                <h5 class="fw-semibold text-muted">Total Escuelas</h5>
                                <span class="text-success numberSchools fs-4 fw-bold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-4 shadow-sm border-0">
                            <div class="d-flex justify-content-between">
                                <h5 class="fw-semibold text-muted">Total de Areas</h5>
                                <span class="text-warning numberAreas fs-4 fw-bold"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección del gráfico de estadísticas -->
                <div class="row justify-content-center mb-4">
                    <div class="col-md-8">
                        <div class="card p-4 shadow-sm border-0">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Scripts de Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script src="view/assets/js/ajax/principal/getStats.js"></script>

        <?php elseif($_SESSION['level']== 1): ?>
            <!-- <div class="row justify-content-center">
                <div class="col-8 card p-3">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script src="view/assets/js/ajax/principal/getStats.js"></script> -->

        <?php else: 
            include 'view/pages/supervisor/general/lista.php';
        ?>
            
        <?php endif ?>


    </div>
</div>