$(document).ready(function () {
    var tablaEscuelas = $('#schoolsActive').DataTable({
        rowReorder: {
            dataSrc: 'position',
            selector: 'td:first-child'
        },

        ajax: {
            type: 'POST',
            data: {
                action: 'searchSchool'
            },
            url: 'controller/forms.ajax.php',
            dataSrc: ''
        },
        columns: [
            {
                data: 'position',
                render: function (data, type, row, meta) {
                    return `<center><i class="fas fa-grip-lines" style="cursor: grab;"></i></center>`;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                    <center>
                        <button class="btn btn-link" onclick="openEdifices(${data.idSchool})" data-tippy-content="Zonas de ${data.nameSchool}">
                            <span class="arrow">${data.nameSchool}</span>
                        </button>
                    </center>`;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                    <center>
                        <div class="btn-group">
                            <button class="btn btn-info" onclick="openMenuEdit('modalNavUpdate', ${data.idSchool})" data-tippy-content="Editar">
                                <i class="fa-duotone fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger" onclick="showModal(${data.idSchool})" data-tippy-content="Eliminar">
                                <i class="fa-duotone fa-trash"></i>
                            </button>
                        </div>
                    </center>`;
                }
            }
        ],        
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        drawCallback: function() {
            tippy('[data-tippy-content]', {
                duration: 0,
                arrow: false,
                delay: [1000, 200],
                followCursor: true,
            });            
        }
    });

    // Evento para manejar el reordenamiento
    tablaEscuelas.on('row-reorder', function (e, diff, edit) {
        var orden = [];
        diff.forEach(function (change) {
            orden.push({
                id: tablaEscuelas.row(change.node).data().idSchool,
                position: change.newPosition + 1
            });
        });

        // Enviar el nuevo orden al servidor
        $.ajax({
            type: "POST",
            url: 'controller/forms.ajax.php',
            data: {
                action: 'updateOrderSchool',
                order: orden
            },
            success: function(response) {
                if(response === 'ok') {
                    tablaEscuelas.ajax.reload(); // Recargar la tabla para reflejar los cambios
                } else {
                    console.error('Error al actualizar el orden:', response);
                }
            }
        });
    });
});

function openMenuEdit(collapse, id) {

    $.ajax({
        type: "POST",
        url: 'controller/forms.ajax.php',
        data: {
            action: 'searchSchool',
            searchSchool: id
            },
        dataType: 'json',
        success: function(data) {
            $('#nameSchoolEdit').val(data.nameSchool);

            document.querySelector('#' + collapse).classList.add('show');
            var modalBackdrop = document.createElement('div');
            modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(modalBackdrop);
            

            $('.update').click(function(e){
                e.preventDefault();
                var name = $('#nameSchoolEdit').val();

                $.ajax({
                    type: "POST",
                    url: 'controller/forms.ajax.php',
                    data: {
                        action: 'editSchool',
                        nameSchool: name,
                        idSchool: id
                    },
                    success: function(data) {
                        if (data == 'ok'){
                            $('#edit-1').val('');
                            closeMenu('modalNavUpdate');
                            $('#schoolsActive').DataTable().ajax.reload();
                        }
                    }
                });   
            });
        }
    });

}

function showModal(idSchool) {
    $('#deleteSchool').modal('show');
    $('#idSchool').val(idSchool);
}

function deleteSchool(){
    var idSchool = $('#idSchool').val();
    $.ajax({
        url: 'controller/forms.ajax.php',
        type: 'POST',
        data: {
            action: 'deleteSchool',
            deleteSchool: idSchool
        },
        dataSrc: '',
        async: false, // Asegura que la función espere a que se complete la solicitud AJAX
        success: function (response) {
            if (response == 'ok') { 
                $('#deleteSchool').modal('hide');
                $('#schoolsActive').DataTable().ajax.reload();
            }
        }
    });
};

function openEdifices(idSchool) {
    //abrir edificio y enviar el id por post
    window.location.href = 'edifices&school=' + idSchool;
}

$('.success').click(function(e){
        
    var name = $('#nameSchool').val();
    $.ajax({
        type: 'POST',
        url: 'controller/forms.ajax.php',
        data: {
            action:'registerSchool',
            nameSchool: name
        },
        success: function(response){
            if (response = 'ok') {
                closeMenu('modalCollapse');
                $('#schoolsActive').DataTable().ajax.reload();
            }
        },
        error: function(xhr, status, error){
            // Manejar errores de la solicitud AJAX
            alert('Error al registrar la escuela. Por favor, inténtalo de nuevo.');
        }
    });
});