<style>
td, th {
  vertical-align: middle; /* Centra verticalmente */
}

</style>
<!-- Modal -->
<div class="modal fade" id="modalList" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalListLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalListLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-striped" id="objects">
            <thead>
                <tr>
                    <th></th>
                    <th>Cantidad</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                    <th>Nivel de importancia</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="tbodyObjects">

            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>