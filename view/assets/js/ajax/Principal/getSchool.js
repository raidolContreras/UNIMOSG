
$(document).ready(function () {
    // Inicializar DataTable
    $('#results').DataTable();

    // Obtener el valor de 'school'
    const school = $('#school').val();

    // Llamar a la función para obtener el nombre de la escuela
    fetchSchoolName(school);

    // Obtener referencias a los radio buttons y campos de texto
    const $shoppingYes = $('#shoppingYes');
    const $shoppingNo = $('#shoppingNo');
    const $specificShopping = $('#specificShopping');
    const $shopping = $('#shopping');
    const $shoppingProveedores = $('#shoppingProveedores');
    const $shoppingEvidence = $('#shoppingEvidence');

    // Función para habilitar o deshabilitar los campos basados en el radio button seleccionado
    function toggleSpecificShopping() {
        const isYesChecked = $shoppingYes.is(':checked');
        $specificShopping.prop('disabled', !isYesChecked);
        $shopping.prop('disabled', !isYesChecked);
        $shoppingProveedores.prop('disabled', !isYesChecked);
        $shoppingEvidence.prop('disabled', !isYesChecked);
    }

    // Asignar los eventos de cambio a los radio buttons
    $shoppingYes.on('change', toggleSpecificShopping);
    $shoppingNo.on('change', toggleSpecificShopping);

    // Llamar a la función inicialmente para aplicar el estado correcto al cargar la página
    toggleSpecificShopping();
});

function fetchSchoolName(school) {
    $.ajax({
        type: 'POST',
        url: 'controller/principal.ajax.php',
        data: {
            action: 'getSchools',
            idSchool: school
        },
        success: function (data) {
            $('#nameSchool').append(data.nameSchool);
        }
    });
}

function solicitud(school, importancia) {
    $('.resultsSchools').show();

    if ($.fn.DataTable.isDataTable('#results')) {
        $('#results').DataTable().destroy();
    }

    $('#results').DataTable({
        ajax: {
            type: "POST",
            url: 'controller/ajax/getSolicitudes.php',
            data: { idSchool: school, importancia: importancia },
            dataSrc: '',
        },
        columns: [
            { data: null, render: data => createTableColumn(data.name) },
            { data: null, render: data => createTableColumn(data.nameObject + ': ' +data.description) },
            { data: null, render: data => createTableColumn(data.dateCreated) },
            { data: null, render: data => calculateDaysDiff(data) },
            { data: null, render: data => createOrderButton(data.idIncidente, importancia) },
        ],
		language: {
			"paginate": {
				"first":      "<<",
				"last":       ">>",
				"next":       ">",
				"previous":   "<"
			},
			"search":         "Buscar:",
			"lengthMenu":     "Ver _MENU_ resultados",
			"loadingRecords": "Cargando...",
			"info":           "Mostrando _START_ de _END_ en _TOTAL_ resultados",
			"infoEmpty":      "Mostrando 0 resultados",
			"emptyTable":	  "Ningún dato disponible en esta tabla"
		}
    });
}

function createTableColumn(content) {
    return `<center class="table-columns">${content}</center>`;
}

function calculateDaysDiff(data) {
    const currentDate = new Date();
    const reportDate = new Date(data.dateCreated);
    const timeDiff = currentDate - reportDate;
    const daysDiff = Math.floor(timeDiff / (1000 * 3600 * 24));
    let message = (daysDiff > 0) ? `${daysDiff} días desde el levantamiento del reporte` : '0 días';

    if (data.status == 2) {
        message = `Pospuesto hasta el: ${data.fechaAsignada}`;
        $('.posponer').css('display', 'none');
    } else if (data.status == 1) {
        message = `Solucionado el: ${data.solutionDate}`;
    } else {
        $('.posponer').css('display', 'block');
    }

    return message;
}

function createOrderButton(idIncidente, importancia) {
    return `
        <center class="table-columns">
            <button class="btn btn-success" onclick="lookOrder(${idIncidente}, '${importancia}')">
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

function lookOrder(idIncidente, importancia) {
    $.ajax({
        url: 'controller/ajax/getOrder.php',
        type: 'POST',
        dataType: 'json',
        data: { searchIncidente: idIncidente },
        success: function (data) {
            fillOrderDetails(data);
            toggleOrderActions(importancia, data);

            if (!data.files || data.files.length === 0) {
                $('.evidence').css('display', 'none');
            } else {
                displayEvidence(data.files);
            }

            $('#lookOrder').modal('show');
        }
    });
}

function fillOrderDetails(data) {
    $('#idIncidente').val(data.idIncidente);
    $('#nPedido').val(data.nPedido);
    $('#Cantidad').val(data.cantidad);
    $('#Observaciones').val(data.description);
    $('#dateRevition').val(data.dateCreated);
    $('#Estado').val(data.estado);
    $('#details').html(data.detallesCorregidos);
    $('#shopping').html(data.compra == 1 ? 'Si' : 'No');
    $('#specific').html(data.detalleCompra);
    $('#posponerRazonContainer').css('display', 'none');
    $('#fechaAsignadaContainer').css('display', 'none');
    $('.specific').css('display', 'none');
}

function toggleOrderActions(importancia, data) {
    if (importancia !== 'Completado') {
        $('.posponer').attr('onclick', 'posponerCorreccion()');
        $('.corregido').attr('onclick', `corregido(${data.idIncidente})`);
        $('.corregido').css('display', 'block');
        $('.details').css('display', 'none');
        $('.shopping').css('display', 'none');
        $('.specific').css('display', 'none');
    } else {
        $('.corregido').attr('onclick', '');
        $('.corregido').css('display', 'none');
        $('.posponer').css('display', 'none');
        $('.posponer').attr('onclick', '');
        $('.details').css('display', 'block');
        $('.shopping').css('display', 'block');
        $('.specific').css('display', 'block');
    }
}

function displayEvidence(files) {
    const evidenceContainer = $('#evidence');
    evidenceContainer.html('');
    const fileList = JSON.parse(files);
    fileList.forEach(file => {
        evidenceContainer.append(`
            <div class="col-md-3">
                <a href="view/evidences/${file.name}" target="_blank">
                    <img src="view/evidences/${file.name}" class="img-fluid" alt="evidence">
                </a>
            </div>
        `);
    });
    $('.evidence').css('display', 'block');
}

function corregido(idIncidente) {
    $('#corregidoModal').modal('show');
    $('#lookOrder').modal('hide');
}

function cancelCorreccion() {
    $('#corregidoModal').modal('hide');
    $('#lookOrder').modal('show');
}

$('.marcarCorreccion').click(function () {
    const idIncidente = $('#idIncidente').val();
    const detailsCorrect = $('#detailsCorrect').val();
    const specificShopping = $('#specificShopping').val();
    const shoppingOption = $('input[name="shoppingOption"]').val();

    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            idIncidente: idIncidente,
            detailsCorrect: detailsCorrect,
            specificShopping: specificShopping,
            shoppingOption: shoppingOption
        },
        success: function () {
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
    const idIncidente = $('#idIncidente').val();
    const razon = $('#posponerRazon').val();
    const fechaAsignada = $('#fechaAsignada').val();

    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            idIncidente: idIncidente,
            razon: razon,
            fechaAsignada: fechaAsignada
        },
        success: function () {
            $('#lookOrder').modal('hide');
            $('#results').DataTable().ajax.reload();
        }
    });
}
