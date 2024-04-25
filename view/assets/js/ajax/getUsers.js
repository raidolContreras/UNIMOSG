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
                    
                        case 1:
                            level = 'Director';
                            break;
                
                        case 2:
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
					return `
					<center class="table-columns">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="">
                                Editar
                            </a>
                            <a class="flex items-center text-danger" href="">
                                Suspender 
                            </a>
                        </div>
                    </center>
					`;
				}
			}
		],
		"language": {
			"url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
		}
	});
});