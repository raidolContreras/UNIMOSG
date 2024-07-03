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
                        ${data.name}
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
                    
                    var message = (daysDiff > 0) ? `${daysDiff} días desde el levantamiento del reporte` : '0 días';
                    if ( data.status == 2) {
                        message = `Pospuesto hasta el: ${data.fechaAsignada}`;
                        $('.posponer').css('display', 'none');
                    } else if ( data.status == 1) {
                        message = `Solucionado el: ${data.solutionDate}`;
                    } else {
                        $('.posponer').css('display','block');
                    }
                    // Retornar la diferencia en días
                    return message;
                }
            },            
            {
                data: null,
                render: function (data, type, row, meta) {
                    // Utilizando el contador proporcionado por DataTables
                    return `
                    <center class="table-columns">
                        <button class="btn btn-success" onclick="lookOrder(${data.idIncidente}, '${importancia}')">
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

function lookOrder(idIncidente, importancia){
    $.ajax({
        url: 'controller/ajax/getOrder.php',
        dataSrc: '',
        type: 'POST',
        dataType: 'json',
        data: {searchIncidente: idIncidente},
        success: function(data) {

            $('#idIncidente').val(data.idIncidente);
            $('#nPedido').val(data.nPedido);
            $('#Cantidad').val(data.cantidad);
            $('#Observaciones').val(data.description);
            $('#dateRevition').val(data.dateCreated);
            $('#Estado').val(data.estado);

            $('#details').html(data.detallesCorregidos);
            $('#shopping').html((data.compra == 1) ? 'Si' : 'No');
            $('#specific').html(data.detalleCompra);
            
            $('#posponerRazonContainer').css('display', 'none');
            $('#fechaAsignadaContainer').css('display', 'none');

            $('.specific').css('display', 'none');

            if (importancia != 'Completado') {
                $('.posponer').attr('onclick', 'posponerCorreccion()');
                $('.corregido').attr('onclick', `corregido(${data.idIncidente})`);
                $('.corregido').css('display', 'block');
                $('.details').css('display', 'none');
                $('.shopping').css('display', 'none');
                $('.specific').css('display', 'none');
            } else {
                $('.corregido').attr('onclick', ``);
                $('.corregido').css('display', 'none');
                $('.posponer').css('display', 'none');
                $('.posponer').attr('onclick', '');
                $('.details').css('display', 'block');
                $('.shopping').css('display', 'block');
                $('.specific').css('display', 'block');
            }

            if (!data.files || data.files.length === 0) {
                $('.evidence').css('display', 'none');
            } else {
                var files = JSON.parse(data.files);
                var evidenceContainer = $('#evidence');
                evidenceContainer.html('');
                for (var i = 0; i < files.length; i++) {
                    evidenceContainer.append(`
                        <div class="col-md-3">
                            <a href="view/evidences/${files[i].name}" target="_blank">
                                <img src="view/evidences/${files[i].name}" class="img-fluid" alt="evidence">
                            </a>
                        </div>
                    `);
                }
                $('.evidence').css('display', 'block');
            }
        
            $('#lookOrder').modal('show');
        }
    });
}

function corregido(idIncidente){
    $('#corregidoModal').modal('show');
    $('#lookOrder').modal('hide');
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

function posponerCorreccion() {
    $('#posponerRazonContainer').css('display', 'block');
    $('#fechaAsignadaContainer').css('display', 'block');
    
    $('.posponer').attr('onclick', 'posponer()');
    
}

function posponer() {
    idIncidente = $('#idIncidente').val();
    razon = $('#posponerRazon').val();
    fechaAsignada = $('#fechaAsignada').val();
    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            idIncidente: idIncidente,
            razon: razon,
            fechaAsignada: fechaAsignada
            },
        success: function(data) {
            $('#lookOrder').modal('hide');
            $('#results').DataTable().ajax.reload();
        }
    });
}