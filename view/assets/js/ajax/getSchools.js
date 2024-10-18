$(document).ready(function () {
    var tablaEscuelas = $('#schoolsActive').DataTable({
        ajax: {
            url: 'controller/ajax/getSchools.php',
            dataSrc: ''
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    // Utilizando el contador proporcionado por DataTables
                    return `
                    <center class="table-columns">
                        ${meta.row + 1}
                    </center>
                    `;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                    <center class="table-columns school">
                        <span class="arrow">`+ data.nameSchool + `</span>
                    </center>
                    `;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                    <center class="table-columns">
                        <div class="flex justify-center items-center btn-group">
                            <button class="btn btn-info items-center button-custom" onclick="openMenuEdit('modalNavUpdate', 'editUsers', ${data.idSchool})" data-tippy-content="Editar">
                                <i class="fa-duotone fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger items-center button-custom" onclick="showModal(${data.idSchool})" data-tippy-content="Eliminar">
                                <i class="fa-duotone fa-trash"></i> 
                            </button>
                        </div>
                    </center>
                    `;
                }
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        // Aquí se inicializan los tooltips después de renderizar la tabla
        drawCallback: function() {
            tippy('[data-tippy-content]', {
                duration: 0,
                arrow: false,
                delay: [1000, 200],
                followCursor: true,
            });            
        }
    });

    $('#schools tbody').on('click', '.school', function () {
        var fila = tablaEscuelas.row($(this).closest('tr'));
        var datosFila = fila.data();
    
        // Verificar si ya se ha creado un detalle para esta fila
        if (fila.child.isShown()) {
            // Si se muestra, ocultarlo
            fila.child.hide();
            $(this).removeClass('details');
            $(this).find('.arrow').removeClass('active');
        } else {
            // Si no se muestra, crear el detalle
            fila.child(formatoDetalle(datosFila)).show();
            $(this).addClass('details');
            $(this).find('.arrow').addClass('active');
        }
    });
    
});

function formatoDetalle(data) {
    var html = ''; // Inicializamos la variable html
    $.ajax({
        url: 'controller/ajax/getZones.php',
        type: 'POST',
        data: {
            idSchool: data.idSchool
        },
        dataType: 'json',
        async: false, // Asegura que la función espere a que se complete la solicitud AJAX
        success: function (response) {
            html = `
                <div class="table-responsive" style="padding-left: 300px; padding-right: 300px;">
                    <div class="row pr-4">
                        <h5 class="col">${data.nameSchool}</h5>
                        <a class="btn btn-success items-center col-2 mt-2 button-custom" onclick="openMenuZoneSchool('modalZones', 'newZones', ${data.idSchool}, '${data.nameSchool}')">
                            <i class="fa-solid fa-plus"></i> Añadir zonas
                        </a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" width="80%">
                                    Zonas:
                                </th>
                            </tr>
                        </thead>
                        <tbody>`;

            if (response.length > 0) { // Verificamos si hay resultados en la respuesta
                response.forEach(function (zone) {
                    html += `
                    <tr class="">
                        <td>${zone.nameZone}</td>
                        <td>
                            <a class="btn btn-link" href="zones&school=${data.idSchool}">
                                <i class="fa-solid fa-angle-right"></i>
                            </a>
                        </td>
                    </tr>`;
                });
            } else {
                html += `
                <tr class="">
                    <td>Sin resultados</td>
                </tr
                >`; // Mensaje para cuando no hay resultados
            }
            html += `
                    </tbody>
                </table>
            </div>`;
        }
    });
    return html; // Devolvemos el valor html
}

function showModal(idSchool) {
    $('#deleteSchool').modal('show');
    $('#idSchool').val(idSchool);
}

function deleteSchool(){
    var idSchool = $('#idSchool').val();
    $.ajax({
        url: 'controller/ajax/ajax.form.php',
        type: 'POST',
        data: {
            deleteSchool: idSchool
        },
        dataSrc: '',
        async: false, // Asegura que la función espere a que se complete la solicitud AJAX
        success: function (response) {
            if (response == 'ok') { 
                $('#deleteSchool').modal('hide');
                $('#schoolsActive').DataTable().ajax.reload();
            }
        }
    });
};