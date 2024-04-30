<div class="card-custom">
    <div class="card-header-custom">
        <span id="return" class="col-1"></span>
        <strong class="col-10" id='namePage'></strong>
        <div class="col-1"></div>
    </div>
</div>
<div class="col-12">
<main class="container">
    <div class="p-3">
	
    <table id="objetos" class="table table-resposive stripe">
        <thead>
            <th>#</th>
            <th class="">Nombre del objeto</th>
            <th class="">Cantidad</th>
            <th class="">Area</th>
            <th class="">Zona</th>
            <th class="">Escuela</th>
            <th width="30%"></th>
        </thead>
    </table>
</main>
</div>
<input type="hidden" id="area" value="<?php echo (isset($_POST['area'])) ? $_POST['area'] : null ?>">
<input type="hidden" id="zone" value="<?php echo (isset($_POST['zone'])) ? $_POST['zone'] : null ?>">

<script src="view/assets/js/ajax/principal/getObjetos.js"></script>