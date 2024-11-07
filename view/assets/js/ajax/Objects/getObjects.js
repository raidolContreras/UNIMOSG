// Inicializar documento con jQuery
let tablaObjects;

$(document).ready(function() {

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
            dataSrc: ""
        },
        ordering: false,
        columns: [
            {
                data: 'position',
                render: () => `<center style="cursor: grab;"><i class="fas fa-sort"></i></center>`
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
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
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
            if (response === 'ok') {
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
            var rowData = tablaObjects.row(function(idx, data) {
                return data.idObject === id;
            }).data();

            rowData.nameObject = nameObject;
            rowData.quantity = quantity;
            tablaObjects.row(function(idx, data) {
                return data.idObject === id;
            }).data(rowData).draw(false);

            $('#updateObjectForm').trigger('reset');
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
                tablaObjects.row(function(idx, data) {
                    return data.idObject === id;
                }).remove().draw(false);
            } else {
                alert('Error al eliminar el objeto.');
            }
        }).fail(function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
        });
    }
}
