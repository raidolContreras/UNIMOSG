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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
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
                    </div>
                    <div class="col-md-6 text-center">
                        <h6 class="fw-bold">Foto del Incidente:</h6>
                        <img id="modalImagen" src="" alt="Foto del incidente" class="img-fluid rounded shadow-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
});

function showDetails(nombre, nivel, descripcion, imagen) {
    document.getElementById('modalNombre').innerText = nombre;
    document.getElementById('modalNivel').innerText = nivel;
    document.getElementById('modalDescripcion').innerText = descripcion;
    document.getElementById('modalImagen').src = imagen;
}

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
                    <div class="card mb-4 shadow">
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
                                            onclick="showDetails('${incident.nameObject}', '${urgency}', '${incident.description}', 'view/evidences/${incident.evidence}')">
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
</script>
