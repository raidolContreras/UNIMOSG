<style>
/* Estilo base del botón */
.row.mb-3 button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 70px;
  height: 70px;
  border: 3px solid transparent;
  border-radius: 50%;
  background: linear-gradient(145deg, #f0f0f0, #e0e0e0); /* Relieve sutil */
  font-size: 22px;
  color: #444;
  transition: all 0.3s ease, transform 0.3s ease;
  cursor: pointer;
  position: relative;
  overflow: visible; /* Permite que los efectos no se corten */
}

/* Sombra interna */
.row.mb-3 button:before {
  content: '';
  position: absolute;
  top: 5px;
  left: 5px;
  width: calc(100% - 10px);
  height: calc(100% - 10px);
  background: rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  transition: all 0.3s ease;
  z-index: 1;
}

/* Efecto de hover */
.row.mb-3 button:hover {
  background: linear-gradient(145deg, #d3d3d3, #c0c0c0); /* Inversión de relieve */
  box-shadow: 0 0 15px rgba(0, 255, 255, 0.7); /* Efecto de borde iluminado */
}

.row.mb-3 button:hover:before {
  background: rgba(0, 0, 0, 0.2); /* Más sombra interna en hover */
}

/* Efecto de clic */
.row.mb-3 button:active {
  transform: scale(0.95); /* Reduce ligeramente al presionar */
  box-shadow: inset 4px 4px 10px rgba(0, 0, 0, 0.3), inset -4px -4px 10px rgba(255, 255, 255, 0.5);
}

/* Personalización por tipo de botón */
.row.mb-3 .attach-evidence {
  background: linear-gradient(145deg, #1d6f6f, #2b8b8b); /* Verde azulado elegante */
  color: white;
}

.row.mb-3 .attach-evidence:hover {
  background: linear-gradient(145deg, #1b5b5b, #237676);
}

.row.mb-3 .take-photo {
  background: linear-gradient(145deg, #3a4f6f, #4f6782); /* Azul elegante */
  color: white;
}

.row.mb-3 .take-photo:hover {
  background: linear-gradient(145deg, #33475e, #3e5772);
}

.row.mb-3 .send-object {
  background: linear-gradient(145deg, #7e5b3c, #9b7549); /* Marrón sofisticado */
  color: white;
  opacity: 0.8;
  cursor: not-allowed;
}

.row.mb-3 .send-object:not([disabled]) {
  cursor: pointer;
  opacity: 1;
}

.row.mb-3 .send-object:not([disabled]):hover {
  background: linear-gradient(145deg, #6a4d39, #7c5f46);
}

.row.mb-3 .cancel-photo {
  background: linear-gradient(145deg, #7e3e3e, #9b4f4f); /* Rojo oscuro */
  color: white;
}

.row.mb-3 .cancel-photo:hover {
  background: linear-gradient(145deg, #6a3535, #7e4545);
}

/* Iconos dentro de los botones */
.row.mb-3 button i {
  font-size: 24px;
  transition: transform 0.3s ease, color 0.3s ease;
  position: relative;
  z-index: 2;
}

/* Deshabilitar efectos para botones deshabilitados */
.row.mb-3 button[disabled],
.row.mb-3 button[disabled]:hover,
.row.mb-3 button[disabled]:active {
	background: linear-gradient(145deg, #e6e6e6, #d6d6d6); /* Fondo deshabilitado */
  cursor: not-allowed; /* Cursor de no permitido */
  transform: none; /* Sin animación de escala */
  box-shadow: none; /* Sin sombra */
}

</style>

<div class="container mt-5">
	<h2 class="text-center">Sistema de Servicios Generales</h2>
	<div class="text-center my-4">
		<!-- Botón para iniciar recorrido -->
		<button id="startRecorrido" class="btn btn-primary btn-lg">Comenzar Recorrido</button>
	</div>
	<!-- Contenedor para opciones -->
	<div id="optionsContainer" class="my-4 d-none text-center">
		<h4>¿Qué deseas hacer?</h4>
		<button id="asignadosButton" class="btn btn-success mt-2">Dirigirme a los lugares asignados</button>
		<button id="libreButton" class="btn btn-warning mt-2">Hacer un recorrido libre</button>
	</div>
	<!-- Contenedor para las listas -->
	<div id="listContainer" class="my-4 d-none">
		<h4 id="listTitle"></h4>
		<ul class="list-group d-none" id="recorridoList"></ul>
		<div class="row">
			<div id="recorridoListSchool" class="d-none col-3"></div>
			<div id="recorridoListEdificer" class="d-none col-3"></div>
			<div id="recorridoListFloor" class="d-none col-3"></div>
			<div id="recorridoListZone" class="d-none col-3"></div>
			<div id="recorridoListArea" class="d-none col-12 mt-5"></div>
		</div>
		<div class="text-center">
			<button id="backButton" class="btn btn-secondary mt-3">Regresar</button>
		</div>
	</div>
</div>

<!-- modal de objetos, nombre, cantidad boton de evidencia -->
<div class="modal fade" id="modalObjects" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xxl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel"></h5>
			</div>
			<div class="modal-body objects">
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>

<script src="view/assets/js/ajax/supervitors/lista.js"></script>