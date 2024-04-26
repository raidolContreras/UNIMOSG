<div class="intro-y box">
    <form class="p-5" id="editZones">
        <div class="mb-3">
            <label for="nameZoneEdit" class="form-label">Nombre de la zona</label>
            <input id="nameZoneEdit" type="text" class="form-control" placeholder="Nombre de la escuela">
        </div>
        <div class="d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-danger me-2" onclick="closeMenu('modalNavUpdate')">Cancelar</button>
            <button type="button" class="btn btn-success update">Aceptar</button>
        </div>
        <input type="hidden" id="edit">
    </form>
</div>
<script src="view/assets/js/ajax/editZones.js"></script>
