<div class="intro-y box">
    <form class="p-1" id="editUsers">

        <div class="mb-3">
            <label for="c-1" class="form-label">Nombre completo</label>
            <input id="edit-1" type="text" class="form-control" placeholder="Nombre completo">
        </div>

        <div class="mb-3">
            <label for="edit-3" class="form-label">Correo eléctronico</label>
            <input id="edit-3" type="text" class="form-control" placeholder="Correo eléctronico">
        </div>
        
        <!-- Telefono -->
        <div class="mb-3">
            <label for="edit-4" class="form-label">Número de Whatsapp</label>
            <input id="edit-4" type="text" class="form-control" placeholder="Número de Whatsapp">
        </div>

        <!-- Chat telegram id -->
         <div class="mb-3">
            <label for="edit-6" class="form-label">Chat Telegram ID</label>
            <input id="edit-6" type="text" class="form-control" placeholder="Chat Telegram ID">
        </div>

        <div class="mb-3">
            <label for="edit-5" class="form-label">Nivel de acceso</label>
            <select class="form-select" aria-label="Default select example" id="edit-5">
                <option value="2">Supervisor</option>
                <option value="1">Director de servicios generales</option>
                <option value="0">Administrador</option>
            </select>
        </div>

        <input type="hidden" id="edit">

        <div class="d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-danger me-2" onclick="closeMenu('modalNavUpdate')">Cancelar</button>
            <button type="button" class="btn btn-success update">Aceptar</button>
        </div>
    </form>
</div>
<script src="view/assets/js/ajax/editUsers.js"></script>
