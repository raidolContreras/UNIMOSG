<div class="intro-y box">
    <form class="p-1" id="newUsers">
        <div class="mb-3">
            <label for="regular-form-1" class="form-label">Nombre completo</label>
            <input id="regular-form-1" type="text" class="form-control" placeholder="Nombre completo">
        </div>
        <div class="mb-3">
            <label for="regular-form-3" class="form-label">Correo eléctronico</label>
            <input id="regular-form-3" type="text" class="form-control" placeholder="Correo eléctronico">
        </div>
        <div class="mb-3">
            <label for="regular-form-4" class="form-label">Contraseña</label>
            <input id="regular-form-4" type="password" class="form-control" placeholder="Contraseña">
        </div>
        <!-- Telefono -->
        <div class="mb-3">
            <label for="regular-form-6" class="form-label">Número de Whatsapp</label>
            <input id="regular-form-6" type="text" class="form-control" placeholder="Número de Whatsapp">
        </div>
        <div class="mb-3">
            <label for="regular-form-5" class="form-label">Nivel de acceso</label>
            <select class="form-select" aria-label="Default select example" id="regular-form-5">
                <option value="2">Supervisor</option>
                <option value="1">Director de servicios generales</option>
                <option value="0">Administrador</option>
            </select>
        </div>
        <div class="d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-danger me-2" onclick="closeMenu('modalNav')">Cancelar</button>
            <button type="button" class="btn btn-success success">Aceptar</button>
        </div>
    </form>
</div>
<script src="view/assets/js/ajax/newUsers.js"></script>
