<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<style>
  .card-body {
    margin-top: 0px !important;
  }

  .dropdown-menu {
    position: absolute !important;
    z-index: 9999 !important; /* Prueba con valores más altos si es necesario */
    top: 100%; /* Esto asegurará que aparezca debajo del botón */
    left: 0; /* Alinear a la izquierda */
  }
  .card.school-item {
    position: relative;
  }

</style>

<div class="p-3 mb-4 rounded">
    <h5 id="namePage" class="page-title">Gestión de Pisos</h5>
</div>
<div class="col-md-12 col-lg-9 col-xl-10 p-4 row" style="width: 100vw; height: 58vh; margin: 0; padding: 0; top: 0; left: 0;">

  <div class="col-12">
      <div id="floorsContainer" class="floors-container row"></div>
  </div>
</div>
<input type="hidden" id="edificer" value="<?php echo (isset($_GET['edificer']) ? $_GET['edificer'] : 0); ?>">

<script src="view/assets/js/ajax/Floors/getFloors.js"></script>
