var level = $('#level').val();

$(document).ready(function () {
    // Asegúrate de que jQuery UI y Touch Punch estén cargados
    if (typeof $.ui === 'undefined' || !$.ui.sortable) {
        $.getScript("https://code.jquery.com/ui/1.12.1/jquery-ui.min.js", function() {
            $.getScript("https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js", function() {
                inicializarReordenamiento();
            });
        });
    } else {
        inicializarReordenamiento();
    }

    cargarEscuelas();
});

function cargarEscuelas() {
    $.ajax({
        type: 'POST',
        url: 'controller/forms.ajax.php',
        data: {
            action: 'searchSchool'
        },
        success: function(response) {
            var escuelas = JSON.parse(response);
            $('#schoolsContainer').empty();
            escuelas.forEach(function (escuela, index) {
                
            $('#namePage').html(`
                <a class="btn btn-link disabled">Planteles</a>`);
                if ( level == 0 ){
                    var schoolCard = `
                    <div class="col-md-4 mb-3"> 
                        <div class="card school-item shadow-sm border-0" data-position="${escuela.position}" style="align-items: center; flex-direction: row;">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                                <div class="handle me-3">
                                    <i class="fas fa-grip-vertical fa-lg text-muted"></i>
                                </div>
                                <button class="btn btn-link p-0 text-dark fw-bold flex-grow-1 text-start" onclick="openEdifices(${escuela.idSchool})" style="text-decoration: none;">
                                    <span class="arrow">${escuela.nameSchool}</span>
                                </button>
                            </div>
                            <div class="dropdown ms-3">
                                <button style="margin-right: 15px;" class="btn btn-link text-dark p-0" type="button" id="dropdownMenuButton${escuela.idSchool}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                                    <i class="fas fa-ellipsis-v fa-lg"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton${escuela.idSchool}">
                                    <li><button class="dropdown-item" onclick="openMenuEdit('modalNavUpdate', ${escuela.idSchool})">Editar</button></li>
                                    <li><button class="dropdown-item text-danger" onclick="showModal(${escuela.idSchool})">Eliminar</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>`;
                } else {
                    var schoolCard = `
                    <div class="col-md-4 mb-3"> 
                        <div class="card school-item shadow-sm border-0" data-position="${escuela.position}" style="align-items: center; flex-direction: row;">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                                <button class="btn btn-link p-0 text-dark fw-bold flex-grow-1 text-start" onclick="openEdifices(${escuela.idSchool})" style="text-decoration: none;">
                                    <span class="arrow">${escuela.nameSchool}</span>
                                </button>
                            </div>
                        </div>
                    </div>`;
                }
                // Añade esto al inicializar el dropdown
                $('#dropdownMenuButton' + escuela.idSchool).dropdown({
                    display: 'static'
                }).appendTo('body');


                $('#schoolsContainer').append(schoolCard);
            });
            inicializarReordenamiento();
        }
    });
}

function inicializarReordenamiento() {
    $('#schoolsContainer').sortable({
        handle: '.handle',
        update: function (event, ui) {
            var orden = [];
            $('.school-item').each(function (index) {
                orden.push({
                    id: $(this).find('button.btn-link').attr('onclick').match(/\d+/)[0],
                    position: index + 1
                });
            });
            $.ajax({
                type: "POST",
                url: 'controller/forms.ajax.php',
                data: {
                    action: 'updateOrderSchool',
                    order: orden
                },
                success: function(response) {
                    if(response === 'ok') {
                        cargarEscuelas(); // Recargar la lista para reflejar los cambios
                    } else {
                        console.error('Error al actualizar el orden:', response);
                    }
                }
            });
        }
    });
}

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
                            cargarEscuelas();
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
                cargarEscuelas();
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
                cargarEscuelas();
            }
        },
        error: function(xhr, status, error){
            // Manejar errores de la solicitud AJAX
            alert('Error al registrar la escuela. Por favor, inténtalo de nuevo.');
        }
    });
});

async function getExtendedSystemInfo() {
    
    const userAgent = navigator.userAgent;
    // Ejemplo simple: extraer el dispositivo del userAgent (no confiable pero a veces útil)
    let deviceModel = "Unknown Device";
    if (/iPhone/.test(userAgent)) {
        deviceModel = "iPhone";
    } else if (/iPad/.test(userAgent)) {
        deviceModel = "iPad";
    } else if (/Android/.test(userAgent) && /Mobile/.test(userAgent)) {
        deviceModel = "Android Phone";
    } else if (/Android/.test(userAgent)) {
        deviceModel = "Android Tablet";
    } else if (/Windows/.test(userAgent)) {
        deviceModel = "Windows PC";
    } else if (/Mac/.test(userAgent)) {
        deviceModel = "Mac Computer";
    }

    const systemInfo = {
        userAgent: deviceModel,
        platform: navigator.platform,
        vendor: navigator.vendor,
        screenResolution: `${screen.width}x${screen.height}`,
        windowSize: `${window.innerWidth}x${window.innerHeight}`,
        language: navigator.language,
        deviceMemory: navigator.deviceMemory || 'N/A',
        hardwareConcurrency: navigator.hardwareConcurrency || 'N/A',
        dateTime: new Date().toLocaleString(),
        pageHistoryLength: history.length,  // Cantidad de páginas en la sesión de navegación actual
        themePreference: window.matchMedia("(prefers-color-scheme: dark)").matches ? 'Dark' : 'Light',
        orientation: screen.orientation ? screen.orientation.type : "N/A",
    };

    // API de Rendimiento para Tiempos de Carga
    if (performance && performance.timing) {
        const perfData = performance.timing;
        systemInfo.pageLoadTime = (perfData.loadEventEnd - perfData.navigationStart) / 1000;  // en segundos
        systemInfo.domContentLoadedTime = (perfData.domContentLoadedEventEnd - perfData.navigationStart) / 1000;
    }

    // Información de permisos, verificando si el usuario ha concedido permisos
    if (navigator.permissions) {
        const permissionsToCheck = ["geolocation", "notifications", "camera", "microphone"];
        systemInfo.permissions = {};

        for (let permission of permissionsToCheck) {
            try {
                const result = await navigator.permissions.query({ name: permission });
                systemInfo.permissions[permission] = result.state; // granted, denied o prompt
            } catch (error) {
                systemInfo.permissions[permission] = "Not available"; // En caso de error o permisos no soportados
            }
        }
    }

    // Información de batería
    if (navigator.getBattery) {
        const battery = await navigator.getBattery();
        systemInfo.batteryLevel = `${Math.round(battery.level * 100)}%`;
        systemInfo.batteryCharging = battery.charging ? "Yes" : "No";
    }

    console.log(systemInfo);
    // // Enviar la información al servidor
    // fetch('log_system_info.php', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify(systemInfo)
    // })
    // .then(response => response.text())
    // .then(data => console.log('Log saved:', data))
    // .catch(error => console.error('Error:', error));
}

// Ejecutar la función para enviar la información extendida
getExtendedSystemInfo();
