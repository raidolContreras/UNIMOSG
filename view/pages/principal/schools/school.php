    <div class="card-custom">
        <div class="card-header-custom">
            <strong id='nameSchool'></strong>
        </div>
    </div>

    <div class="card">
        <div class="row justify-content-center p-3">
            <div class="col-12 col-md-4 my-3">
                <button onclick="solicitud(<?php echo $_GET['idSchool'] ?>, 'Pendiente')" class="btn btn-success w-100">Incidencias pendientes<span class="pendiente position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">1</span></button>
            </div>
            <div class="col-12 col-md-4 my-3">
                <button onclick="solicitud(<?php echo $_GET['idSchool'] ?>, 'Urgente')" class="btn btn-success w-100">Incidencias urgentes<span class="ungente position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">5</span></button>
            </div>
            <div class="col-12 col-md-4 my-3">
                <button onclick="solicitud(<?php echo $_GET['idSchool'] ?>, 'Importante')" class="btn btn-success w-100">Incidencias importantes<span class="importante position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span></button>
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
                <div class="row">
                    <div class="col-6">
                        <label class="form-label" for="Cantidad">Cantidad</label>
                        <input type="text" id="Cantidad" disabled class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="nPedido">N° de pedido</label>
                        <input type="text" id="nPedido" disabled class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="Observaciones">Observaciones</label>
                        <input type="text" id="Observaciones" disabled class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="dateRevition">Fecha de revision</label>
                        <input type="text" id="dateRevition" disabled class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="Estado">Estado</label>
                        <input type="text" id="Estado" disabled class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="corregido()">Corregido</button>
            </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="school" value="<?php echo $_GET['idSchool'] ?>">

    <script src="view/assets/js/ajax/General/getSchool.js"></script>