$(document).ready(function () {
    var tablaEscuelas = $('#schools').DataTable({
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
                        `+ data.nameSchool + `
                    </center>
                    `;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                    <center class="table-columns">
                        <div class="flex justify-center items-center">
                            <button class="btn btn-info items-center mr-3 button-custom" onclick="openMenuEdit('modalNavUpdate', 'editUsers', ${data.idSchool})">
                                <i class="fa-duotone fa-pen-to-square"></i> Editar
                            </button>
                            <button class="btn btn-danger items-center button-custom" onclick="">
                                <i class="fa-duotone fa-trash"></i> Eliminar 
                            </button>
                        </div>
                    </center>
                    `;
                }
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
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
        } else {
            // Si no se muestra, crear el detalle
            fila.child(formatoDetalle(datosFila)).show();
            $(this).addClass('details');
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
                            <a class="btn btn-link" href="zones&zone=${zone.idZone}">
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

function deleteSchool(idSchool){
    $.ajax({
        url: 'controller/ajax/ajax.form.php',
        type: 'POST',
        data: {
            deleteSchool: idSchool
        },
        dataType: 'json',
        async: false, // Asegura que la función espere a que se complete la solicitud AJAX
        success: function (response) {
            if (response.length > 0) { // Verificamos si hay resultados en la respuesta
                response.forEach(function (school) {
                    $('#nameSchool').val(school.nameSchool);
                });
            }
        }
    });
};
