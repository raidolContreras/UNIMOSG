<div class="container my-5">
    <h4 class="mb-4 text-success">Reportes de Supervisores</h4>
    <div class="row" id="areas"></div>
</div>

<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel">Detalles del Objeto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Nombre del Objeto:</h6>
                        <p id="modalNombre"></p>
                        <h6 class="fw-bold">Nivel de Importancia:</h6>
                        <p id="modalNivel" class="badge bg-warning text-dark"></p>
                        <h6 class="fw-bold">Descripción:</h6>
                        <p id="modalDescripcion"></p>
                        <h6 class="fw-bold">Fecha del Incidente:</h6>
                        <p id="modalFecha"></p>
                    </div>
                    <div class="col-md-6 text-center">
                        <h6 class="fw-bold">Foto del Incidente:</h6>
                        <img id="modalImagen" src="" alt="Foto del incidente" class="img-fluid rounded shadow-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- boton para finalizar el incidente -->
                <button type="button" class="btn btn-success" id="endIncident">Finalizar Incidente</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    showIncidents();

    $('#modalDetalle').on('hidden.bs.modal', function () {
        $('body').focus();
    });

    // Manejar el clic en "Finalizar Incidente"
    $(document).on('click', '#endIncident', function () {
        let idEvidence = $(this).attr('data-id-evidence');
        $('#modalDetalle').modal('hide'); // Ocultar el modal de detalles
        createEndIncidentModal(idEvidence); // Crear el modal de finalización
    });

    // Manejar el clic en "Cancelar" en el modal dinámico
    $(document).on('click', '#cancelar', function () {
        $('#endIncidentModal').modal('hide'); // Ocultar modal de finalización
        $('#modalDetalle').modal('show'); // Mostrar modal de detalles
    });
});

// Función para formatear la fecha
function formatDate(dateString) {
    const months = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];
    const date = new Date(dateString);

    const day = date.getDate().toString().padStart(2, '0');
    const month = months[date.getMonth()];
    const year = date.getFullYear();

    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12 || 12;

    return `${day}/${month}/${year} ${hours}:${minutes} ${ampm}`;
}

// Función para mostrar detalles en el modal
function showDetails(nombre, nivel, descripcion, imagen, fecha, idEvidence) {
    $('#modalNombre').text(nombre);
    $('#modalNivel').text(nivel);
    $('#modalDescripcion').text(descripcion);
    $('#modalImagen').attr('src', imagen);
    $('#modalFecha').text(formatDate(fecha));
    $('#endIncident').attr('data-id-evidence', idEvidence);
}

// Función para mostrar los incidentes
function showIncidents() {
    $.ajax({
        url: 'controller/forms.ajax.php',
        type: 'POST',
        data: { action: 'getIncidents' },
        dataType: 'json',
        success: function (response) {
            let incidents = response;

            let groupedSchools = {};
            incidents.forEach(incident => {
                if (!groupedSchools[incident.nameSchool]) groupedSchools[incident.nameSchool] = {};
                if (!groupedSchools[incident.nameSchool][incident.nameEdificer]) groupedSchools[incident.nameSchool][incident.nameEdificer] = {};
                if (!groupedSchools[incident.nameSchool][incident.nameEdificer][incident.nameFloor]) groupedSchools[incident.nameSchool][incident.nameEdificer][incident.nameFloor] = {};
                if (!groupedSchools[incident.nameSchool][incident.nameEdificer][incident.nameFloor][incident.nameArea]) groupedSchools[incident.nameSchool][incident.nameEdificer][incident.nameFloor][incident.nameArea] = [];
                groupedSchools[incident.nameSchool][incident.nameEdificer][incident.nameFloor][incident.nameArea].push(incident);
            });

            let schoolsHTML = '';
            for (let school in groupedSchools) {
                schoolsHTML += `
                    <div class="card mb-4 mx-1 col-md-4">
                        <div class="mt-2 mb-0">
                            <h5>${school}</h5>
                        </div>
                        <div class="card-body">
                `;

                for (let building in groupedSchools[school]) {
                    schoolsHTML += `
                        <div class="mb-2">
                            <h6 class="text-success fw-bold">Edificio: ${building}</h6>
                    `;

                    for (let floor in groupedSchools[school][building]) {
                        schoolsHTML += `
                            <div class="mb-2 ps-3 border-start border-2 border-secondary">
                                <strong>Piso: ${floor}</strong>
                        `;

                        for (let area in groupedSchools[school][building][floor]) {
                            schoolsHTML += `
                                <div class="mt-2 ps-4 border-start border-1 border-secondary">
                                    <h6 class="text-dark">Área: ${area}</h6>
                                    <ul class="list-group list-group-flush">
                            `;

                            groupedSchools[school][building][floor][area].forEach(incident => {
                                let urgency = incident.urgency === 'urgent' ? 'Urgente' : incident.urgency === 'no-urgency' ? 'No urgente' : 'De inmediato';
                                let urgencyColor = incident.urgency === 'urgent' ? 'danger' : incident.urgency === 'no-urgency' ? 'info' : 'warning';

                                schoolsHTML += `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="#" class="stretched-link text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#modalDetalle"
                                            onclick="showDetails('${incident.nameObject}', '${urgency}', '${incident.description}', 'view/evidences/${incident.evidence}', '${incident.dateCreated}', ${incident.idEvidence})">
                                            <i class="bi bi-circle-fill text-${urgencyColor} me-2"></i>${incident.nameObject}
                                        </a>
                                        <span class="badge bg-${urgencyColor}">${urgency}</span>
                                    </li>
                                `;
                            });

                            schoolsHTML += `
                                    </ul>
                                </div>
                            `;
                        }

                        schoolsHTML += `
                            </div>
                        `;
                    }

                    schoolsHTML += `
                        </div>
                    `;
                }

                schoolsHTML += `
                        </div>
                    </div>
                `;
            }

            $('#areas').html(schoolsHTML);
        }
    });
}

// Función para crear el modal de finalizar incidente
function createEndIncidentModal(idEvidence) {
    $('#endIncidentModal').remove();

    let modalHTML = `
        <div class="modal fade" id="endIncidentModal" tabindex="-1" aria-labelledby="endIncidentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="endIncidentModalLabel">Finalizar Incidente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="endIncidentForm" enctype="multipart/form-data">
                            <input type="hidden" name="idEvidence" value="${idEvidence}">
                            <div class="mb-3">
                                <label for="endDate" class="form-label">Fecha de Finalización</label>
                                <input type="datetime-local" class="form-control" id="endDate" name="endDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="purchaseMade" class="form-label">¿Se realizó una compra?</label>
                                <select class="form-select" id="purchaseMade" name="purchaseMade" required>
                                    <option value="" selected>Seleccione...</option>
                                    <option value="yes">Sí</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div id="purchaseDetails" class="d-none">
                                <div class="mb-3">
                                    <label for="purchaseAmount" class="form-label">Monto de la Compra</label>
                                    <input type="number" step="0.01" class="form-control" id="purchaseAmount" name="purchaseAmount" placeholder="Ingrese el monto">
                                </div>
                                <div class="mb-3">
                                    <label for="invoiceFile" class="form-label">Adjuntar Factura</label>
                                    <input type="file" class="form-control" id="invoiceFile" name="invoiceFile" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="evidenceFile" class="form-label">Foto de Evidencia</label>
                                <input type="file" class="form-control" id="evidenceFile" name="evidenceFile" accept=".jpg,.jpeg,.png" required>
                            </div>
                            <div class="mb-3">
                                <label for="reason" class="form-label">Motivo de Finalización</label>
                                <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="Escriba el motivo" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submitEndIncident">Finalizar Incidente</button>
                        <button type="button" class="btn btn-danger" id="cancelar">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('body').append(modalHTML);

    $('#endIncidentModal').modal('show');

    $('#purchaseMade').on('change', function () {
        if ($(this).val() === 'yes') {
            $('#purchaseDetails').removeClass('d-none');
        } else {
            $('#purchaseDetails').addClass('d-none');
            $('#purchaseAmount').val('');
            $('#invoiceFile').val('');
        }
    });

    $(document).on('click', '#submitEndIncident', function () {
        let formData = new FormData($('#endIncidentForm')[0]);

        $.ajax({
            url: 'controller/forms.ajax.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response === 'success') {
                    showIncidents();
                    $('#endIncidentModal').modal('hide');
                } else {
                    alert('Error al finalizar el incidente.');
                }
            }
        });
    });
}

</script>
