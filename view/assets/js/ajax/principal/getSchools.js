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
                    <center class="table-columns">
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
                            <a class="btn btn-primary items-center mr-3 button-custom" href="?pagina=zones&school=${data.idSchool}">
                                Ver escuela <i class="fa-duotone fa-right-from-bracket"></i>
                            </a>
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
    
});