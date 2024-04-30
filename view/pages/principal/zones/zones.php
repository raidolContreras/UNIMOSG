<div class="card-custom">
    <div class="card-header-custom">
        <strong id='namePage'></strong>
    </div>
</div>
<div class="col-12">
    <table id="zones" class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre de la zona</th>
                <th>Escuela a la que pertenece</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<input type="hidden" id="school" value="<?php echo (isset($_GET['school']) ? $_GET['school'] :0) ?>">

<script src="view/assets/js/ajax/principal/getZones.js"></script>
