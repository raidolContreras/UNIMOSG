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

/* Checkbox */
.checkbox-wrapper-7 .tgl {
    display: none;
}
.checkbox-wrapper-7 .tgl,
.checkbox-wrapper-7 .tgl:after,
.checkbox-wrapper-7 .tgl:before,
.checkbox-wrapper-7 .tgl *,
.checkbox-wrapper-7 .tgl *:after,
.checkbox-wrapper-7 .tgl *:before,
.checkbox-wrapper-7 .tgl + .tgl-btn {
    box-sizing: border-box;
}
.checkbox-wrapper-7 .tgl::-moz-selection,
.checkbox-wrapper-7 .tgl:after::-moz-selection,
.checkbox-wrapper-7 .tgl:before::-moz-selection,
.checkbox-wrapper-7 .tgl *::-moz-selection,
.checkbox-wrapper-7 .tgl *:after::-moz-selection,
.checkbox-wrapper-7 .tgl *:before::-moz-selection,
.checkbox-wrapper-7 .tgl + .tgl-btn::-moz-selection,
.checkbox-wrapper-7 .tgl::selection,
.checkbox-wrapper-7 .tgl:after::selection,
.checkbox-wrapper-7 .tgl:before::selection,
.checkbox-wrapper-7 .tgl *::selection,
.checkbox-wrapper-7 .tgl *:after::selection,
.checkbox-wrapper-7 .tgl *:before::selection,
.checkbox-wrapper-7 .tgl + .tgl-btn::selection {
    background: none;
}
.checkbox-wrapper-7 .tgl + .tgl-btn {
    outline: 0;
    display: block;
    width: 4em;
    height: 2em;
    position: relative;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
        -ms-user-select: none;
            user-select: none;
}
.checkbox-wrapper-7 .tgl + .tgl-btn:after,
.checkbox-wrapper-7 .tgl + .tgl-btn:before {
    position: relative;
    display: block;
    content: "";
    width: 50%;
    height: 100%;
}
.checkbox-wrapper-7 .tgl + .tgl-btn:after {
    left: 0;
}
.checkbox-wrapper-7 .tgl + .tgl-btn:before {
    display: none;
}
.checkbox-wrapper-7 .tgl:checked + .tgl-btn:after {
    left: 50%;
}

.checkbox-wrapper-7 .tgl-ios + .tgl-btn {
    background: #fbfbfb;
    border-radius: 2em;
    padding: 2px;
    transition: all 0.4s ease;
    border: 1px solid #e8eae9;
}
.checkbox-wrapper-7 .tgl-ios + .tgl-btn:after {
    border-radius: 2em;
    background: #fbfbfb;
    transition: left 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), padding 0.3s ease, margin 0.3s ease;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1), 0 4px 0 rgba(0, 0, 0, 0.08);
}
.checkbox-wrapper-7 .tgl-ios + .tgl-btn:hover:after {
    will-change: padding;
}
.checkbox-wrapper-7 .tgl-ios + .tgl-btn:active {
    box-shadow: inset 0 0 0 2em #e8eae9;
}
.checkbox-wrapper-7 .tgl-ios + .tgl-btn:active:after {
    padding-right: 0.8em;
}
.checkbox-wrapper-7 .tgl-ios:checked + .tgl-btn {
    background: #86d993;
}
.checkbox-wrapper-7 .tgl-ios:checked + .tgl-btn:active {
    box-shadow: none;
}
.checkbox-wrapper-7 .tgl-ios:checked + .tgl-btn:active:after {
    margin-left: -0.8em;
}
</style>

<div class="container mt-5">
	<h2 class="text-center">Sistema de Servicios Generales</h2>
	<!-- Contenedor para las listas -->
	<div id="listContainer" class="my-4">
		<h4 id="listTitle"></h4>
		<ul class="list-group d-none" id="recorridoList"></ul>
		<div class="row">
			<div id="recorridoListSchool" class="d-none col-3"></div>
			<div id="recorridoListEdificer" class="d-none col-3"></div>
			<div id="recorridoListFloor" class="d-none col-3"></div>
			<div id="recorridoListZone" class="d-none col-3"></div>
			<div id="recorridoListArea" class="d-none col-12 mt-5"></div>
		</div>
	</div>
</div>

<!-- modal de objetos, nombre, cantidad boton de evidencia -->
<div class="modal modal-fullscreen fade" id="modalObjects" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
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

<script src="view/assets/js/ajax/supervitors/principalSupervition.js"></script>