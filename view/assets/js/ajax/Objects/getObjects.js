// Inicializar documento con jQuery
let tablaObjects;

$(document).ready(function(){
    var myDropzone = new Dropzone("#addObjectsDropzone", {
        maxFiles: 1,
        url: "controller/forms.ajax.php",
        maxFilesize: 2,
        acceptedFiles: ".csv,.xls,.xlsx",
        dictDefaultMessage: 'Arrastra y suelta el archivo aquí o haz clic para seleccionar uno <p class="subtitulo-sup">Tipos de archivo permitidos: .csv, .xls, .xlsx (Tamaño máximo 2 MB)</p>',
        autoProcessQueue: false,
        dictInvalidFileType: "Archivo no permitido. Por favor, sube un archivo en formato CSV o Excel.",
        dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo permitido: {{maxFilesize}}MB.",
        init: function() {
            this.on("addedfile", function(file) {
                if (file.type === "text/csv" || file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || file.type === "application/vnd.ms-excel") {
                    numCSV++;
                }
                toggleSubmitButton();
                var removeButton = Dropzone.createElement('<button class="rounded-button">&times;</button>');
                var _this = this;
                removeButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (file.type === "text/csv") {
                        numCSV--;
                    } 
                    _this.removeFile(file);
                    toggleSubmitButton();
                });
                file.previewElement.appendChild(removeButton);
            });
        }
    });

    $('.sendObjects').click(function(e){
        idArea = $('#area').val();
        myDropzone.processQueue();
    });

    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append("action", "uploadObjects");
        formData.append("idArea", idArea);
    });

    myDropzone.on("success", function(file, response) {
        if (response == 'ok') {
            showAlertBootstrap('Éxito', 'Archivo cargado exitosamente.');
            $('#objects').DataTable().ajax.reload();
        } else {
            showAlertBootstrap('¡Alerta!', response || 'El archivo no se pudo cargar. Intenta más tarde.');
        }
        myDropzone.removeAllFiles();
    });

    $('.cancelMassiveObjects').on('click', function(e) {
        myDropzone.removeAllFiles();
        $('.moduleAddObjects').addClass('d-none');
        $('#newObjectForm').removeClass('d-none');
        $('.addMassiveObject').removeClass('d-none');
    });

    $('.addMassiveObject').on('click', function() {
        $('.moduleAddObjects').removeClass('d-none');
        $('#newObjectForm').addClass('d-none');
        $('.addMassiveObject').addClass('d-none');
        numCSV = 0;
        toggleSubmitButton();
        myDropzone.removeAllFiles();
    });

    // Inicializar DataTable con la tabla de objects usando AJAX
    tablaObjects = $('#objects').DataTable({
        rowReorder: {
            dataSrc: 'position',
            selector: 'td:first-child'
        },
        ajax: {
            url: "controller/forms.ajax.php",
            type: "POST",
            data: function(d) {
                // Agregar parámetros adicionales al AJAX
                d.action = 'getObjets';
                d.idArea = $('#area').val();
            },
            dataSrc: function (data) {

                dataState = data.data; 
                // Actualizar el nombre de la página

                $('#namePage').html(`
                        <a href="schools" class="btn btn-link" >Inicio</a>
                            <i class="fal fa-angle-right"></i> 
                        <a href="edifices&school=${dataState.idSchool}" class="btn btn-link" >${dataState.nameSchool}</a>
                            <i class="fal fa-angle-right"></i>
                        <a href="floors&edificer=${dataState.idEdificers}" class="btn btn-link" >${dataState.nameEdificer}</a>
                            <i class="fal fa-angle-right"></i>
                        <a href="zones&floor=${dataState.idArea}" class="btn btn-link" >${dataState.nameFloor}</a>
                            <i class="fal fa-angle-right"></i>
                        <a class="btn btn-link disabled">${dataState.nameArea}</a>`);
                
                // Retornar los datos de áreas
                return data.objets || [];
            }
        },
        ordering: false,
        columns: [
            {
                data: 'position',
                render: () => `<center class="handle"><i class="fas fa-sort"></i></center>`
            },
            {
                data: 'nameObject',
                render: (data) => `<center><span class="arrow">${data}</span></center>`
            },
            {
                data: 'quantity',
                render: (data) => `<center><span class="arrow">${data}</span></center>`
            },
            {
                data: null,
                render: (data) => `
                    <center>
                        <div class="btn-group">
                            <button class="btn btn-info" onclick="openMenuEdit('modalObjectsUpdate', ${data.idObject})" data-tippy-content="Editar">
                                <i class="fa-duotone fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger" onclick="showModal(${data.idObject})" data-tippy-content="Eliminar">
                                <i class="fa-duotone fa-trash"></i>
                            </button>
                        </div>
                    </center>`
            }
        ],
        language: {
            "paginate": {
                "first": '<i class="fal fa-angle-double-left"></i>',
                "last": '<i class="fal fa-angle-double-right"></i>',
                "next": '<i class="fal fa-angle-right"></i>',
                "previous": '<i class="fal fa-angle-left"></i>'
            },
            "search": "Buscar:",
            "lengthMenu": "Ver _MENU_ resultados",
            "loadingRecords": "Cargando...",
            "info": "Mostrando _START_ de _END_ en _TOTAL_ resultados",
            "infoEmpty": "Mostrando 0 resultados",
			"emptyTable":	  "Ningún dato disponible en esta tabla"
        }
    });

    // Evento para manejar el reordenamiento
    tablaObjects.on('row-reorder', function (e, diff, edit) {
        var orden = [];
        diff.forEach(function (change) {
            orden.push({
                id: tablaObjects.row(change.node).data().idObject,
                position: change.newPosition + 1
            });
        });

        // Enviar el nuevo orden al servidor
        $.ajax({
            type: "POST",
            url: 'controller/forms.ajax.php',
            data: {
                action: 'updateOrderObject',
                order: orden
            },
            success: function(response) {
                if(response === 'ok') {
                    // Actualizar DataTable sin recargar la tabla completa
                    tablaObjects.ajax.reload();
                } else {
                    console.error('Error al actualizar el orden:', response);
                }
            }
        });
    });

    // Agregar objeto
    $('#addObject').on('click', function(event) {
        event.preventDefault();

        const nameObject = $('#nameObject').val().trim();
        const quantity = $('#quantity').val().trim();

        if (!nameObject || !quantity) {
            alert('Por favor, completa todos los campos.');
            return;
        }

        $.post("controller/forms.ajax.php", {
            action: 'addObject',
            idArea: $('#area').val(),
            nameObject: nameObject,
            quantity: quantity
        }).done(function(response) {
            //rescatar response como json
            response = JSON.parse(response);
            if (response.success) {
                // Borrar datos en los inputs y agregar la fila a DataTable sin recargar
                $('#newObjectForm').trigger('reset');
                tablaObjects.row.add({
                    position: tablaObjects.rows().count() + 1, // Asignar la nueva posición al final
                    nameObject: nameObject,
                    quantity: quantity,
                    idObject: response.idObject // Suponiendo que response contiene el idObject agregado
                }).draw(false);
            } else {
                alert('Error al agregar el objeto.');
            }
        }).fail(function() {
            alert('Error en la solicitud AJAX.');
        });
    });    
});

// Abrir modal y cargar datos para editar
function openMenuEdit(collapse, id) {
    $.post('controller/forms.ajax.php', {
        action: 'searchObject',
        idObject: id
    }, function(data) {
        if (data) {
            $('#nameObjectUpdate').val(data.nameObject);
            $('#quantityUpdate').val(data.quantity);
            showModal2(collapse);
            $('#update').data({ id, collapse });
        }
        
        $('.modal-backdrop').on('click', function() {
            closeMenu(collapse);
        });
        
        $('#cancel').on('click', function() {
            closeMenu(collapse);
        });

    }, 'json').fail(function(xhr, status, error) {
        console.error('Error en la solicitud AJAX:', error);
    });
}

// Mostrar el modal
function showModal2(collapse) {
    var modalElement = document.querySelector('#' + collapse);
    if (modalElement) {
        modalElement.classList.add('show');
        if (!document.querySelector('.modal-backdrop')) {
            var modalBackdrop = document.createElement('div');
            modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(modalBackdrop);
        }
    } else {
        console.error('Elemento con id ' + collapse + ' no encontrado.');
    }
}

// Manejar la actualización del objeto
function handleUpdate(id, collapse) {
    const nameObject = $('#nameObjectUpdate').val().trim();
    const quantity = $('#quantityUpdate').val().trim();

    if (!nameObject || !quantity || quantity <= 0) {
        alert('Por favor, completa todos los campos.');
        return;
    }

    $.post("controller/forms.ajax.php", {
        action: 'updateObject',
        idObject: id,
        nameObject: nameObject,
        quantity: quantity
    }).done(function(response) {
        if (response === 'ok') {
            // Actualizar la fila correspondiente en DataTable sin recargar
            $('#objects').DataTable().ajax.reload();
            closeMenu(collapse);
        }
    }).fail(function(xhr, status, error) {
        console.error('Error en la solicitud AJAX:', error);
    });
}

// Evento para el botón de actualización
$(document).on('click', '#update', function(e) {
    e.preventDefault();
    const { id, collapse } = $(this).data();
    handleUpdate(id, collapse);
});

// Función showModal para eliminar un objeto
function showModal(id) {
    if (confirm("¿Está seguro de eliminar este objeto?")) {
        $.post('controller/forms.ajax.php', {
            action: 'deleteObject',
            idObject: id
        }, function(response) {
            if (response === 'ok') {
                // Eliminar la fila correspondiente en DataTable sin recargar
                $('#objects').DataTable().ajax.reload();
            } else {
                alert('Error al eliminar el objeto.');
            }
        }).fail(function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
        });
    }
}


function toggleSubmitButton() {
    var submitButton = document.querySelector('.sendObjects');
    submitButton.disabled = numCSV !== 1;
}