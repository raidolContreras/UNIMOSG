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
                            level = 'Director';
                            break;
                
                        case 1:
                            level = 'Supervisor';
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
			{
				data: null,
				render: function(data) {
					html = `
					<center class="table-columns">
                        <div class="flex justify-center items-center">
                            <button class="btn btn-info flex items-center mr-3 editUser" onclick="openMenuEdit('modalNavUpdate', 'editUsers', ${data.idUsers})">
								<i class="fa-duotone fa-pen-to-square"></i> Editar
                            </button>
					`;
					if (data.status == 1) {
						html += `
                            <button class="btn btn-danger flex items-center" onclick="SuspendUsers(${data.idUsers})">
								<i class="fa-duotone fa-user-slash"></i> Suspender 
                            </button>
                        </div>
                    </center>`;
					} else {
						html += `
                            <button class="btn btn-success flex items-center" onclick="ActivateUsers(${data.idUsers})">
								<i class="fa-duotone fa-user-plus"></i> Activar 
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
		}
	});
});

function SuspendUsers(idUsers) {
	$.ajax({
        url: 'controller/ajax/ajax.form.php',
        type: 'POST',
        data: {
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
        url: 'controller/ajax/ajax.form.php',
        type: 'POST',
        data: {
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