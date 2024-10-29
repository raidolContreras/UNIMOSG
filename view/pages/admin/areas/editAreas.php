<div class="intro-y box">
    <form class="p-1" id="editAreas">
        <div class="mb-3">
            <label for="nameAreaEdit" class="form-label">Nombre del area</label>
            <input id="nameAreaEdit" type="text" class="form-control" placeholder="Nombre del area">
        </div>
        <div class="d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-danger me-2" onclick="closeMenu('modalNavUpdate')">Cancelar</button>
            <button type="button" class="btn btn-success update">Aceptar</button>
        </div>
        <input type="hidden" id="editArea">
    </form>
</div>
<script src="view/assets/js/ajax/editAreas.js"></script>
