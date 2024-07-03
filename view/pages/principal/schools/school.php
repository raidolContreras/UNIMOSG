    <?php
        $idSchool = (isset($_GET['idSchool']))  ? $_GET['idSchool'] : 0;
    ?>
    <div class="card-custom">
        <div class="card-header-custom">
            <?php if ($idSchool != 0):?>
                <strong id='nameSchool'></strong>
            <?php else: ?>
                <strong>Universidad Montrer</strong>
            <?php endif ?>
        </div>
        <?php if ($idSchool == 0):?>
        
            <div class="schoolsData">
            </div>
        
        <?php endif ?>
    </div>

    <div class="card-custom-xl">
        <div class="row justify-content-center p-3">
            <div class="col-12 col-md-6 my-3">
                <button onclick="solicitud(<?php echo $idSchool ?>, 'Pendiente')" class="btn btn-info w-100">Incidencias pendientes<span class="pendiente position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span></button>
            </div>
            <div class="col-12 col-md-6 my-3">
                <button onclick="solicitud(<?php echo $idSchool ?>, 'Urgente')" class="btn btn-warning w-100">Incidencias urgentes<span class="ungente position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span></button>
            </div>
            <div class="col-12 col-md-6 my-3">
                <button onclick="solicitud(<?php echo $idSchool ?>, 'Inmediata')" class="btn btn-danger w-100">Incidencias inmediatas<span class="importante position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span></button>
            </div>
            <div class="col-12 col-md-6 my-3">
                <button onclick="solicitud(<?php echo $idSchool ?>, 'Completado')" class="btn btn-success w-100">Incidencias completadas<span class="importante position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span></button>
            </div>
        </div>
    </div>

    <div class="card resultsSchools" style="display: none;">
        <div class="p-5 table-responsive" >
            <table class="table" id="results">
                <thead>
                    <tr>
                        <th class="localitation">Localización</th>
                        <th class="observations">Observaciónes</th>
                        <th class="reportDate">Fecha del reporte</th>
                        <th class="days"></th>
                        <th whith="10%"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="lookOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Orden de reparación: </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="nPedido">N° de pedido</label>
                            <input type="text" id="nPedido" disabled class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="Cantidad">Cantidad</label>
                            <input type="text" id="Cantidad" disabled class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="Observaciones">Observaciones</label>
                            <input type="text" id="Observaciones" disabled class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="dateRevition">Fecha de revisión</label>
                            <input type="text" id="dateRevition" disabled class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="Estado">Estado</label>
                            <input type="text" id="Estado" disabled class="form-control">
                        </div>
                        <div class="col-12" id="posponerRazonContainer" style="display: none;">
                            <label class="form-label" for="posponerRazon">Razón de posposición</label>
                            <input type="text" id="posponerRazon" class="form-control">
                        </div>
                        <div class="col-12" id="fechaAsignadaContainer" style="display: none;">
                            <label class="form-label" for="fechaAsignada">Fecha asignada para reparación</label>
                            <input type="date" id="fechaAsignada" class="form-control">
                        </div>
                        <div class="col-12 details">
                            <strong>Detalles corregidos</strong>
                            <label class="form-check-label" id="details"></label>
                        </div>
                        <div class="col-12 shopping">
                            <strong>¿Se realizó alguna compra o gasto?</strong>
                            <label class="form-check-label" id="shopping"></label>
                        </div>
                        <div class="col-12 specific">
                            <strong>Especifique el gasto</strong>
                            <label class="form-check-label" id="specific"></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success corregido" style="display: none;">Corregido</button>
                    <button type="button" class="btn btn-warning posponer" onclick="posponerCorreccion()">Posponer</button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal: Marcar como corregido -->
<div class="modal fade" id="corregidoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Marcar como corregido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <!-- Campo para ingresar los detalles corregidos -->
                        <label class="form-label" for="detailsCorrect">Detalles corregidos</label>
                        <textarea name="detailsCorrect" id="detailsCorrect" class="form-control"></textarea>
                    </div>
                    <div class="col-12">
                        <!-- Opciones para indicar si se realizó alguna compra o gasto -->
                        <label class="form-label">¿Se realizó alguna compra o gasto?</label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="shoppingOption" id="shoppingYes" value="Si">
                            <label class="form-check-label" for="shoppingYes">Sí</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="shoppingOption" id="shoppingNo" value="No">
                            <label class="form-check-label" for="shoppingNo">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <input type="hidden" id="idIncidente">
                    <!-- Campo para ingresar los detalles corregidos -->
                    <label class="form-label" for="specificShopping">Especifique el gasto</label>
                    <textarea name="specificShopping" id="specificShopping" disabled class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Botón para cancelar la acción de corrección -->
                <button type="button" class="btn btn-danger" onclick="cancelCorreccion()">Cancelar</button>
                <!-- Botón para confirmar que se ha corregido -->
                <button type="button" class="btn btn-success marcarCorreccion">Aceptar</button>
            </div>
        </div>
    </div>
</div>

    <input type="hidden" id="school" value="<?php echo $idSchool ?>">

    <script src="view/assets/js/ajax/General/getSchool.js"></script>