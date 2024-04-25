
	<!-- Modal para el registro de eventos -->
	<div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="newUserModalLabel">Nuevo usuario</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times-circle"></i></button>
				</div>
				<div class="modal-body">
					<!-- Formulario para registro de eventos -->
					<form id="events">
						<input type="text" class="form-control" placeholder="Nombre del usuario" name="userName">
						<input type="text" class="form-control" placeholder="Correo electrónico" name="email">
						<input type="text" class="form-control" placeholder="Contraseña" name="password">
						<select class="form-control" name="level" id="level">
							<option>Seleccione el nivel</option>
							<option value="1">Administrador</option>
							<option value="2">Usuario general</option>
						</select>
					</form>
					<div class="message"></div>
					<div class="center-buttons mt-4">
						<button type="button" class="btn btn-primary mx-1" id="newUser">Registrar</button>
						<button type="button" class="btn btn-danger mx-1" data-bs-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>

<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titleEvent" id="eventModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times-circle"></i></button>
            </div>
			<div class="modal-body contentModal">

			</div>
            <div class="modal-footer">
				<div class="center-buttons mt-4">
					<button type="button" class="btn btn-danger mx-1" data-bs-dismiss="modal">Cancelar</button>
					<button class="btn btn-primary mx-1" id="modalAcceptButton" data-bs-dismiss="modal">Aceptar</button>
				</div>
			</div>
        </div>
    </div>
</div>

<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titleEvent" id="eventModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times-circle"></i></button>
            </div>
			<div class="modal-body resultModal">

			</div>
            <div class="modal-footer resultFooter">
				<div class="center-buttons mt-4">
					<button class="btn btn-primary mx-1" data-bs-dismiss="modal">Aceptar</button>
				</div>
			</div>
        </div>
    </div>
</div>