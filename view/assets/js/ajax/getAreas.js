$(document).ready(function() {
    var zone = $('#zone').val();
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
                        <div class="flex justify-center items-center btn-group">
                            <button class="btn btn-primary items-center button-custom" onclick="agregarObjetos(${data.idArea})" data-tippy-content="Ver objetos">
								<i class="fa-duotone fa-right-from-bracket"></i>
                            </button>
                            <button class="btn btn-info items-center button-custom" onclick="openMenuEdit('modalNavUpdate', 'editAreas ', ${data.idArea})" data-tippy-content="Editar">
                                <i class="fa-duotone fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger items-center button-custom" onclick="deleteArea(${data.idArea})" data-tippy-content="Eliminar">
                                <i class="fa-duotone fa-trash"></i> 
                            </button>
                        </div>
                    </center>
                    `;
				}
			},
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
    
    // Seleccionamos los campos del formulario y el botón
    const $objectName = $('#nameObject');
    const $cantidad = $('#countObjects');
    const $saveButton = $('.saveNewObject');

    // Función para verificar si ambos campos tienen valor
    function checkFormFields() {
        if ($objectName.val().trim() !== "" && $cantidad.val().trim() !== "") {
            $saveButton.prop('disabled', false);  // Habilitar el botón
        } else {
            $saveButton.prop('disabled', true);  // Deshabilitar el botón
        }
    }

    // Escuchar los eventos input en ambos campos
    $objectName.on('input', checkFormFields);
    $cantidad.on('input', checkFormFields);
});

function openMenuEdit(collapse, idForm, id) {

    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            searchArea: id
            },
        dataType: 'json',
        success: function(data) {
            $('#nameAreaEdit').val(data.nameArea);
            $('#editArea').val(data.idArea);

            document.querySelector('#' + collapse).classList.add('show');
            var modalBackdrop = document.createElement('div');
            modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(modalBackdrop);
            
            // Agregar evento para cerrar el modal al hacer clic en el backdrop
            $('.modal-backdrop').on('click', function() {
                closeMenu('modalNavUpdate');
            });

        }
    });
}

function verArea(idArea) {
    // Crear un formulario oculto
    var form = document.createElement('form');
    form.method = 'post';
    form.action = 'areas'; // Coloca la URL a la que quieres enviar los datos

    // Crear un input oculto para enviar el valor
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'area';
    input.value = idZone; // El valor que quieres enviar

    // Agregar el input al formulario
    form.appendChild(input);

    // Agregar el formulario al cuerpo del documento
    document.body.appendChild(form);

    // Enviar el formulario
    form.submit();
}

function agregarObjetos(idArea) {
    openMenu('modalObjects', 'newObjets');

    // Agregar evento para cerrar el modal al hacer clic en el backdrop
    $('.modal-backdrop').on('click', function() {
        closeMenu('modalObjects'); // Cierra el modal cuando se hace clic en el backdrop
        resetObjectForm();
    });

    $('#newObjectForm2').removeClass('d-none');

    // Borrar archivos cargados en el Dropzone
    var myDropzone = Dropzone.forElement("#addObjectsDropzone");
    if (myDropzone) {
        myDropzone.removeAllFiles();
        numCSV = 0;
        var submitButton = document.querySelector('.sendObjects');
        submitButton.disabled = true;
    }

    $('#idArea').val(idArea);

    // Verificar si el DataTable ya está inicializado y destruirlo si es necesario
    if ($.fn.DataTable.isDataTable('#objects')) {
        $('#objects').DataTable().clear().destroy();
    }

    var table = $('#objects').DataTable({
        ajax: {
            type: "POST",
            url: "controller/ajax/getObjetos.php",
            data: {
                idArea: idArea
            },
            dataSrc: '',
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `<center class="table-columns">${meta.row + 1}</center>`;
                }
            },
            {
                data: 'nameObject',
                render: function (data, type, row, meta) {
                    return `<center class="table-columns">
                                <span class="editable row" data-name="nameObject" data-type="text" data-pk="${row.idObject}" data-text="${data}">${data}</span>
                            </center>`;
                }
            },
            {
                data: 'cantidad',
                render: function (data, type, row, meta) {
                    return `<center class="table-columns">
                                <span class="editable row" data-name="cantidad" data-type="text" data-pk="${row.idObject}" data-text="${data}">${data}</span>
                            </center>`;
                }
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `<center class="table-columns">
                                <button class="btn btn-danger btn-sm" onclick="deleteObject(${row.idObject})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </center>`;
                }
            },
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        }
    });

    // Hacer los campos editables una vez que la tabla ha sido dibujada
    $('#objects').on('draw.dt', function () {
        $('.editable').each(function() {
            var $this = $(this);
            $this.on('click', function() {
                var currentValue = $this.data('text');
                var inputType = $this.data('type');
                var inputName = $this.data('name');
                var pk = $this.data('pk');

                var $input = $('<input>', {
                    type: inputType,
                    name: inputName,
                    value: currentValue,
                    class: 'form-control col-8'
                });

                var $acceptButton = $('<button>', {
                    html: '<i class="fa-solid fa-check"></i>',
                    class: 'btn btn-primary btn-sm editable-accept'
                });

                var $cancelButton = $('<button>', {
                    html: '<i class="fa-solid fa-xmark"></i>',
                    class: 'btn btn-danger btn-sm editable-cancel'
                });

                var $buttons = $('<div>', {
                    class: 'editable-buttons col-4'
                }).append($acceptButton, $cancelButton);

                $this.html($input).append($buttons);

                $acceptButton.on('click', function() {
                    var newValue = $input.val();

                    $.ajax({
                        url: 'controller/ajax/updateObject.php',
                        type: 'POST',
                        data: {
                            pk: pk,
                            name: inputName,
                            value: newValue
                        },
                        success: function(response) {
                            if (response === 'success') {
                                $this.data('text', newValue);
                                $this.html(newValue);
                            } else {
                                $this.html(currentValue);
                                alert('Error al actualizar el valor.');
                            }
                        },
                        error: function() {
                            $this.html(currentValue);
                            alert('Error en la solicitud AJAX.');
                        }
                    });
                });

                $cancelButton.on('click', function() {
                    $.ajax({
                        url: 'controller/ajax/updateObject.php',
                        type: 'POST',
                        data: {
                            pk: pk,
                            name: inputName,
                            value: currentValue
                        },
                        success: function(response) {
                            if (response === 'success') {
                                $this.data('text', currentValue);
                                $this.html(currentValue);
                            } else {
                                $this.html(currentValue);
                                alert('Error al actualizar el valor.');
                            }
                        },
                        error: function() {
                            $this.html(currentValue);
                            alert('Error en la solicitud AJAX.');
                        }
                    });
                });

                // Para prevenir que el click se propague al span padre
                $input.add($buttons).on('click', function(e) {
                    e.stopPropagation();
                });

                $input.focus();
            });
        });
    });
}

$('.saveNewObject').on('click', function() {
    //validar el formulario
    var objectName = $('#nameObject').val();
    var cantidad = $('#countObjects').val();
    var idArea = $('#idArea').val();

    $.ajax({
        url: 'controller/ajax/ajax.form.php',
        type: 'POST',
        data: {
            addNewObject: true,
            objectName: objectName,
            cantidad: cantidad,
            idArea: idArea
        },
        success: function(response) {
            if (response == 'ok') {
                showAlertBootstrap('Éxito', 'Objeto creado exitosamente.');
                $('#objects').DataTable().ajax.reload();
                resetObjectForm();
            } else {
                showAlertBootstrap('¡Alerta!', response);
            }
        }
    });
});

function resetObjectForm() {
    //mostrar la clase d-none del formulario newObjectForm y mostrar el boton con la clase addNewObject
    $('.objectsBox').addClass('d-none');
    $('.addObjects').removeClass('d-none');
    //limpiar el formulario
    $('#newObjectForm2')[0].reset();
}

function deleteArea(idArea){
    var html = `
        <p>
            ¿Está seguro de eliminar el area?
        </p>
    `;
    $('.titleEvent').html(html);
    $('.contentDeleteModal').html('Esta acción no se puede revertir');
    $('#deleteModal').modal('show');
    $('#modalDeleteButton').attr('onclick', 'confirmDeleteZone(' + idArea + ')');
}

function confirmDeleteZone(idArea) {
    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            deleteArea: idArea
            },
        success: function(data) {
            $('#deleteModal').modal('hide');
            $('#areas').DataTable().ajax.reload();
        }
    });
}

// funcion para eliminar los objetos
function deleteObject(idObject) {
    var html = `
        <p>
            ¿Está seguro de eliminar el objeto?
        </p>
    `;
    $('.titleEvent').html(html);
    $('.contentDeleteModal').html('Esta acción no se puede revertir');
    $('#deleteModal').modal('show');
    $('#modalDeleteButton').attr('onclick', 'confirmDeleteObject(' + idObject + ')');
}

function confirmDeleteObject(idObject) {
    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            deleteObject: idObject
            },
        success: function(data) {
            $('#deleteModal').modal('hide');
            $('#objects').DataTable().ajax.reload();
        }
    });
}