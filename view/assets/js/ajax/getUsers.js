$(document).ready(function() {
    var level = '';
    var status = '';
	$('#tableUsers').DataTable({
		ajax: {
            url: 'controller/ajax/getUsers.php',
			dataSrc: ''
		},
		columns:[
			{
				data: null,
				render: function(data) {
					return `
					<center class="table-columns">
						`+data.name+`
					</center>
					`;
				}
			},
			{
				data: null,
				render: function(data) {
                    switch (data.level) {
                        case 0:
                            level = 'Administrador';
                            break;
                    
                        case 2:
                            level = 'Supervisor';
                            break;
                
                        case 1:
                            level = 'Director';
                            break;
                        
                        default:
                            break;
                    }
					return `
					<center class="table-columns">
                        ${level}
                    </center>`;
				}
			},
            { // telefono y si es 0 saldra un '-'
                data: null,
                render: function(data) {
                    return `
                    <center class="table-columns">
                        ${data.phone === 0? '-' : data.phone}
                    </center>`;
                }
            },
			{
				data: null,
				render: function(data) {
                    if (data.status == 1) {
                        status = 'Activo';
                        classStatus = 'text-success';
                    } else {
                        status = 'Desactivado';
                        classStatus = 'text-danger';
                    }
					return `
					<center class="table-columns">
                        <div class="flex items-center justify-center ${classStatus}">
                        ${status}
                        </div>
                    </center>
					`;
				}
			},
			{   // añadir tooltips
				data: null,
				render: function(data) {
                    html = `
                    <center class="table-columns">
                        <div class="flex justify-center items-center btn-group">
                            <button class="btn btn-info flex items-center editUser" onclick="openMenuEdit('modalNavUpdate', 'editUsers', ${data.idUsers})" data-tippy-content="Editar usuario">
                                <i class="fa-duotone fa-pen-to-square"></i>
                            </button>
                    `;
                    if (data.status == 1) {
                        html += `
                            <button class="btn btn-danger flex items-center" onclick="SuspendUsers(${data.idUsers})" data-tippy-content="Suspender usuario">
                                <i class="fa-duotone fa-user-slash"></i> 
                            </button>
                        </div>
                    </center>`;
                    } else {
                        html += `
                            <button class="btn btn-success flex items-center" onclick="ActivateUsers(${data.idUsers})" data-tippy-content="Activar usuario">
                                <i class="fa-duotone fa-user-plus"></i> 
                            </button>
                            <button class="btn btn-danger flex items-center" onclick="DeleteUsers(${data.idUsers})" data-tippy-content="Eliminar usuario">
                                <i class="fa-duotone fa-trash"></i>
                            </button>
                        </div>
                    </center>`;
                    }
                    return html;
                }                              
			}
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
});

function SuspendUsers(idUsers) {
	$.ajax({
        url: "controller/forms.ajax.php",
        type: 'POST',
        data: {
            action: 'suspendUser',
            suspendUsers: idUsers
        },
        success: function(response) {
            if (response == 'ok') {
                $('#tableUsers').DataTable().ajax.reload();
            } else {
                console.log(response);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}
function ActivateUsers(idUsers) {
	$.ajax({
        url: "controller/forms.ajax.php",
        type: 'POST',
        data: {
            action: 'activateUser',
            activateUsers: idUsers
        },
        success: function(response) {
            if (response == 'ok') {
                $('#tableUsers').DataTable().ajax.reload();
            } else {
                console.log(response);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function DeleteUsers(idUsers) {
    // Confirmación antes de eliminar
    if (confirm('¿Estás seguro de eliminar este usuario permanentemente?')) {
        $.ajax({
            url: "controller/forms.ajax.php",
            type: 'POST',
            data: {
                action: 'deleteUser',
                deleteUsers: idUsers
            },
            success: function(response) {
                if (response == 'ok') {
                    // Recargar la tabla solo si la respuesta es exitosa
                    $('#tableUsers').DataTable().ajax.reload();
                    alert('Usuario eliminado exitosamente.');
                } else {
                    // Mostrar el error en consola
                    console.log('Error:', response);
                    alert('Hubo un problema al eliminar el usuario.');
                }
            },
            error: function(error) {
                console.log('Error:', error);
                alert('Hubo un error en la comunicación con el servidor.');
            }
        });
    }
}
