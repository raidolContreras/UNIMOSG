$(document).ready(function() {
    var zone = $('#zone').val();
    var html = `<i class="fa-duotone fa-arrow-left-to-line" onclick="returnZonas()"></i>`;
    $('#return').html(html);
	$('#areas').DataTable({
		ajax: {
            type: "POST",
            url: 'controller/ajax/getAreas.php',
            data: {
                idZone: zone,
                },
			dataSrc: '',
		},
		columns:[
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
				render: function(data) {
					return `
					<center class="table-columns">
						`+data.nameArea+`
					</center>
					`;
				}
			},
			{
				data: null,
				render: function(data) {
					return `
					<center class="table-columns">
						`+data.nameZone+`
					</center>
					`;
				}
			},
			{
				data: null,
				render: function(data) {
					return `
					<center class="table-columns">
						`+data.nameSchool+`
					</center>
					`;
				}
			},
			{
				data: null,
				render: function(data) {
                    return `
                    <center class="table-columns">
                        <div class="flex justify-center items-center">
                            <button class="btn btn-primary items-center mr-3 button-custom" onclick="verObjetos(${data.idArea}, ${data.idZone})">
								Ver objetos <i class="fa-duotone fa-right-from-bracket"></i>
                            </button>
                        </div>
                    </center>
                    `;
				}
			},
		],
		"language": {
			"url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
		}
	});
});

function openMenuEdit(collapse, idForm, id) {

    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            searchZone: id
            },
        dataType: 'json',
        success: function(data) {
            $('#nameZoneEdit').val(data.nameZone);
            $('#edit').val(data.idZone);

            document.querySelector('#' + collapse).classList.add('show');
            var modalBackdrop = document.createElement('div');
            modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(modalBackdrop);
        }
    });
}

function verObjetos(idArea, idZone) {
    // Crear un formulario oculto
    var form = document.createElement('form');
    form.method = 'post';
    form.action = 'objetos'; // Coloca la URL a la que quieres enviar los datos

    // Crear un input oculto para enviar el valor
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'area';
    input.value = idArea; // El valor que quieres enviar

    var input2 = document.createElement('input');
    input2.type = 'hidden';
    input2.name = 'zone';
    input2.value = idZone; // El valor que quieres enviar

    // Agregar el input al formulario
    form.appendChild(input);
    form.appendChild(input2);

    // Agregar el formulario al cuerpo del documento
    document.body.appendChild(form);

    // Enviar el formulario
    form.submit();
}

function returnZonas() {
    window.location.href = 'zones';
}