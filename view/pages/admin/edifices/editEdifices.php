<div class="intro-y box">
    <form class="p-1" id="editEdifices">
        <div class="mb-3">
            <label for="nameEdificeEdit" class="form-label">Nombre de la zona</label>
            <input id="nameEdificeEdit" type="text" class="form-control" placeholder="Nombre del edificio">
        </div>
        <div class="d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-danger me-2" onclick="closeMenu('modalNavUpdate')">Cancelar</button>
            <button type="button" class="btn btn-success update">Aceptar</button>
        </div>
        <input type="hidden" id="edit">
    </form>
</div>
<script src="view/assets/js/ajax/Edifices/editEdifices.js"></script>
