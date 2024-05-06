$(document).ready(function () {
    
    $('#results').DataTable({});
    var school = $('#school').val();
    $.ajax ({
        url: 'controller/ajax/getSchools.php',
        dataSrc: '',
        type: 'POST',
        dataType: 'json',
        data: {idSchool: school},
        success: function (data) {
            $('#nameSchool').append(data.nameSchool);
        }
    });
    
});

function solicitud(school, importancia){
    $('.resultsSchools').show();
    if ($.fn.DataTable.isDataTable('#results')) {
        $('#results').DataTable().destroy();
    }
    $('#results').DataTable({
        ajax: {
            type: "POST",
            url: 'controller/ajax/getSolicitudes.php',
            data: {idSchool: school, importancia: importancia},
            dataSrc: '',
        },
        columns:[
            {
                data: null,
                render: function (data, type, row, meta) {
                    // Utilizando el contador proporcionado por DataTables
                    return `
                    <center class="table-columns">
                        ${data.nameArea} - ${data.nameZone} - ${data.nameObject}
                    </center>
                    `;
                }
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    // Utilizando el contador proporcionado por DataTables
                    return `
                    <center class="table-columns">
                        ${data.description}
                    </center>
                    `;
                }
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    // Utilizando el contador proporcionado por DataTables
                    return `
                    <center class="table-columns">
                        <button class="btn btn-success" onclick="lookOrder(${data.idIncidente})">
                            <div class="row">
                                <div class="col-9">Ver orden</div> 
                                <div class="col-2">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                            </div>
                        </button>
                    </center>
                    `;
                }
            },
        ]
    });
}

function lookOrder(idIncidente){
    $('#lookOrder').modal('show');
}