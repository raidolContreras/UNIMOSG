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

    // Obtener referencia a los radio buttons de "Sí" y "No" y al textarea de especificar el gasto
    const shoppingYes = document.getElementById('shoppingYes');
    const shoppingNo = document.getElementById('shoppingNo');
    const specificShopping = document.getElementById('specificShopping');

    // Función para habilitar o deshabilitar el textarea de especificar el gasto
    function toggleSpecificShopping() {
        specificShopping.disabled = !shoppingYes.checked;
    }

    // Agregar event listeners para el cambio en los radio buttons
    shoppingYes.addEventListener('change', toggleSpecificShopping);
    shoppingNo.addEventListener('change', toggleSpecificShopping);

    // Llamar a la función inicialmente para asegurarse de que el estado del textarea sea correcto al cargar la página
    toggleSpecificShopping();
    
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
                render: function (data) {
                    return `
                    <center class="table-columns">
                        ${data.dateCreated}
                    </center>
                    `;
                }
            },{
                data: null,
                render: function (data) {
                    // Obtener la fecha actual
                    var currentDate = new Date();
                    
                    // Convertir la fecha creada del reporte a un objeto Date
                    var reportDate = new Date(data.dateCreated);
                    
                    // Calcular la diferencia en milisegundos entre las dos fechas
                    var timeDiff = currentDate.getTime() - reportDate.getTime();
                    
                    // Convertir la diferencia de milisegundos a días
                    var daysDiff = Math.floor(timeDiff / (1000 * 3600 * 24));
                    
                    // Retornar la diferencia en días
                    return `${daysDiff} días desde el levantamiento del reporte`;
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
    $.ajax ({
        url: 'controller/ajax/getOrder.php',
        dataSrc: '',
        type: 'POST',
        dataType: 'json',
        data: {searchIncidente: idIncidente},
        success: function (data) {

            $('#idIncidente').val(data.idIncidente);
            $('#nPedido').val(data.nPedido);
            $('#Cantidad').val(data.cantidad);
            $('#Observaciones').val(data.description);
            $('#dateRevition').val(data.dateCreated);
            $('#Estado').val(data.estado);
            
            $('.corregido').attr('onclick',`corregido(${data.idIncidente})`);
        
        }
    });
}

function corregido(idIncidente){
    $('#lookOrder').modal('hide');
    $('#corregidoModal').modal('show');
}

function cancelCorreccion(){
    $('#corregidoModal').modal('hide');
    $('#lookOrder').modal('show');
}

$('.marcarCorreccion').click(function () {

    idIncidente = $('#idIncidente').val();
    detailsCorrect = $('#detailsCorrect').val();
    specificShopping = $('#specificShopping').val();
    shoppingOption = $('input[name="shoppingOption"]').val();
    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            idIncidente: idIncidente,
            detailsCorrect: detailsCorrect,
            specificShopping: specificShopping,
            shoppingOption: shoppingOption
            },
        success: function(data) {
            $('#corregidoModal').modal('hide');
            $('#results').DataTable().ajax.reload();
        }
    });
});