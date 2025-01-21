<?php
$supervitors = FormsController::getSupervitors();
$planteles = FormsController::ctrSearchSchools(null, null);
?>
<div class="p-3 mb-4 rounded">
	<h5 id="namePage" class="page-title">Calendario de Eventos</h5>
	<!-- Botón para agregar recorrido -->
	<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecorridoModal">Agregar
		Recorrido</button>
</div>

<!-- Contenedor del calendario -->
<div id="calendar-container" class="card p-5">
	<div id="calendar"></div>
</div>

<!-- Modal para agregar/editar eventos -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<form id="eventForm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="eventModalLabel">Agregar/Editar Evento</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">&times;</button>
				</div>
				<div class="modal-body row">
					<div class="mb-3 col-md-6 col-12">
						<label for="eventTitle" class="form-label">Título del Evento</label>
						<input type="text" class="form-control" id="eventTitle" required>
					</div>
					<div class="mb-3 col-md-6 col-12">
						<label for="eventSupervisor" class="form-label">Supervisor</label>
						<select class="form-select" id="eventSupervisor" required>
							<option value="" disabled selected>Seleccione un supervisor</option>
							<?php foreach ($supervitors as $supervitor): ?>
								<option value="<?php echo $supervitor['idUsers'] ?>"><?php echo $supervitor['name'] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="mb-3 col-md-6 col-12">
						<label for="eventPlantel" class="form-label">Planteles</label>
						<select class="form-select" id="eventPlantel" required>
							<option value="" disabled selected>Seleccione un plantel</option>
							<?php foreach ($planteles as $plantel): ?>
								<option value="<?php echo $plantel['idSchool'] ?>"><?php echo $plantel['nameSchool'] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="mb-3 col-md-6 col-12">
						<label for="eventEdificio" class="form-label">Edificio</label>
						<select class="form-select" id="eventEdificio" required disabled>
							<option value="" disabled selected>Seleccione un edificio</option>
						</select>
					</div>
					<div class="mb-3 col-md-6 col-12">
						<label for="eventPiso" class="form-label">Piso</label>
						<select class="form-select" id="eventPiso" required disabled>
							<option value="" disabled selected>Seleccione un piso</option>
						</select>
					</div>
					<div class="mb-3 col-md-6 col-12">
						<label for="eventZona" class="form-label">Zona</label>
						<select class="form-select" id="eventZona" required disabled>
							<option value="" disabled selected>Seleccione una zona</option>
							<option value="Norte">Norte</option>
							<option value="Sur">Sur</option>
							<option value="Este">Este</option>
							<option value="Oeste">Oeste</option>
						</select>
					</div>
					<div class="mb-3 col-md-6 col-12">
						<label for="eventArea" class="form-label">Área</label>
						<select class="form-select" id="eventArea" required disabled>
							<option value="" disabled selected>Seleccione una área</option>
						</select>
					</div>
					<div class="mb-3 col-md-6 col-12" id="timePickerContainer">
						<label for="eventTime" class="form-label">Hora</label>
						<input type="time" class="form-control" id="eventTime" required step="1800" min="08:00" max="17:30">
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="eventId">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Guardar Evento</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Modal para agregar recorridos -->
<div class="modal fade" id="addRecorridoModal" tabindex="-1" aria-labelledby="addRecorridoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRecorridoModalLabel">Agregar Recorrido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">&times;</button>
            </div>
            <form id="recorridoForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Supervisor -->
                        <div class="col-md-6 col-12">
                            <label for="recorridoSupervisor" class="form-label">Supervisor</label>
                            <select class="form-select" id="recorridoSupervisor" required>
                                <option value="" disabled selected>Seleccione un supervisor</option>
                                <?php foreach ($supervitors as $supervitor): ?>
                                    <option value="<?php echo $supervitor['idUsers'] ?>"><?php echo $supervitor['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <!-- Plantel -->
                        <div class="col-md-6 col-12">
                            <label for="recorridoPlantel" class="form-label">Planteles</label>
                            <select class="form-select" id="recorridoPlantel" required>
                                <option value="" disabled selected>Seleccione un plantel</option>
                                <?php foreach ($planteles as $plantel): ?>
                                    <option value="<?php echo $plantel['idSchool'] ?>"><?php echo $plantel['nameSchool'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <!-- Edificio -->
                        <div class="col-md-6 col-12">
                            <label for="recorridoEdificio" class="form-label">Edificio</label>
                            <select class="form-select" id="recorridoEdificio" required disabled>
                                <option value="" disabled selected>Seleccione un edificio</option>
                            </select>
                        </div>

                        <!-- Piso -->
                        <div class="col-md-6 col-12">
                            <label for="recorridoPiso" class="form-label">Piso</label>
                            <select class="form-select" id="recorridoPiso" required disabled>
                                <option value="" disabled selected>Seleccione un piso</option>
                            </select>
                        </div>

                        <!-- Zona -->
                        <div class="col-md-6 col-12">
                            <label for="recorridoZona" class="form-label">Zona</label>
                            <select class="form-select" id="recorridoZona" required disabled>
                                <option value="" disabled selected>Seleccione una zona</option>
                                <option value="Norte">Norte</option>
                                <option value="Sur">Sur</option>
                                <option value="Este">Este</option>
                                <option value="Oeste">Oeste</option>
                            </select>
                        </div>

                        <!-- Área -->
                        <div class="col-md-6 col-12">
                            <label for="recorridoArea" class="form-label">Área</label>
                            <select class="form-select" id="recorridoArea" required disabled>
                                <option value="" disabled selected>Seleccione una área</option>
                            </select>
                        </div>

                        <!-- Día de la Semana -->
                        <div class="col-md-6 col-12">
                            <label for="recorridoDia" class="form-label">Día de la Semana</label>
                            <select class="form-select" id="recorridoDia" required>
                                <option value="" disabled selected>Seleccione un día</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sábado</option>
                            </select>
                        </div>

                        <!-- Hora -->
                        <div class="col-md-6 col-12">
                            <label for="recorridoHora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="recorridoHora" required step="1800" min="08:00" max="17:30">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar Recorrido</button>
                </div>
            </form>

            <!-- Tabla de recorridos -->
            <div class="card-body row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped" id="recorridosTable"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <form id="editarForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editEventModalLabel">Editar Evento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <!-- Titulo del evento -->
            <div class="col-md-6 col-12" id="tituloEvento">
              <label for="editarTitulo" class="form-label">Título del Evento</label>
              <input type="text" class="form-control" id="editarTitulo" required>
            </div>
            <!-- Supervisor -->
            <div class="col-md-6 col-12">
              <label for="editarSupervisor" class="form-label">Supervisor</label>
              <select class="form-select" id="editarSupervisor" required>
                <option value="" disabled selected>Seleccione un supervisor</option>
                <?php foreach ($supervitors as $supervitor): ?>
                  <option value="<?php echo $supervitor['idUsers'] ?>"><?php echo $supervitor['name'] ?></option>
                <?php endforeach ?>
              </select>
            </div>

            <!-- Plantel -->
            <div class="col-md-6 col-12">
              <label for="editarPlantel" class="form-label">Planteles</label>
              <select class="form-select" id="editarPlantel" required>
                <option value="" disabled selected>Seleccione un plantel</option>
                <?php foreach ($planteles as $plantel): ?>
                  <option value="<?php echo $plantel['idSchool'] ?>"><?php echo $plantel['nameSchool'] ?></option>
                <?php endforeach ?>
              </select>
            </div>

            <!-- Edificio -->
            <div class="col-md-6 col-12">
              <label for="editarEdificio" class="form-label">Edificio</label>
              <select class="form-select" id="editarEdificio" required disabled>
                <option value="" disabled selected>Seleccione un edificio</option>
              </select>
            </div>

            <!-- Piso -->
            <div class="col-md-6 col-12">
              <label for="editarPiso" class="form-label">Piso</label>
              <select class="form-select" id="editarPiso" required disabled>
                <option value="" disabled selected>Seleccione un piso</option>
              </select>
            </div>

            <!-- Zona -->
            <div class="col-md-6 col-12">
              <label for="editarZona" class="form-label">Zona</label>
              <select class="form-select" id="editarZona" required disabled>
                <option value="" disabled selected>Seleccione una zona</option>
                <option value="Norte">Norte</option>
                <option value="Sur">Sur</option>
                <option value="Este">Este</option>
                <option value="Oeste">Oeste</option>
              </select>
            </div>

            <!-- Área -->
            <div class="col-md-6 col-12">
              <label for="editarArea" class="form-label">Área</label>
              <select class="form-select" id="editarArea" required disabled>
                <option value="" disabled selected>Seleccione una área</option>
              </select>
            </div>

            <!-- Día de la Semana -->
            <div class="col-md-6 col-12">
              <label for="editarDia" class="form-label">Día de la Semana</label>
              <select class="form-select" id="editarDia" required>
                <option value="" disabled selected>Seleccione un día</option>
                <option value="1">Lunes</option>
                <option value="2">Martes</option>
                <option value="3">Miércoles</option>
                <option value="4">Jueves</option>
                <option value="5">Viernes</option>
                <option value="6">Sábado</option>
              </select>
              <!-- dia con fecha actual -->
              <input type="date" id="editarFecha" class="form-control" required min="<?= date('Y-m-d'); ?>">
            </div>

            <!-- Hora -->
            <div class="col-md-6 col-12">
              <label for="editarHora" class="form-label">Hora</label>
              <input type="time" class="form-control" id="editarHora" required step="1800" min="08:00" max="17:30">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script src="view/assets/js/ajax/supervitors/planSupervitor.js"></script>