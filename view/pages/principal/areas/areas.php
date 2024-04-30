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
	
    <table id="areas" class="table table-resposive stripe">
        <thead>
            <th>#</th>
            <th class="">Nombre del area</th>
            <th class="">Zona</th>
            <th class="">Escuela</th>
            <th width="30%"></th>
        </thead>
    </table>
</main>
</div>
<input type="hidden" id="zone" value="<?php echo $_POST['zone'] ?>">

<script src="view/assets/js/ajax/principal/getAreas.js"></script>