$(document).ready(function () {
    const lugaresAsignados = [];
    const lugaresLibres = [];

    // Utilidad para realizar solicitudes AJAX
    function realizarSolicitud(url, datos, exitoCallback, errorCallback) {
        $.ajax({
            url,
            type: 'POST',
            dataType: 'json',
            data: datos,
            success: exitoCallback,
            error: function (xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                if (errorCallback) errorCallback(xhr, status, error);
            }
        });
    }

    // Cargar lugares asignados
    realizarSolicitud('controller/forms.ajax.php', { action: 'getSupervitionDaysUser' }, (response) => {
        const day = new Date().getDay();
        const { daily, today } = response;
        lugaresAsignados.push(
            ...daily
                .filter((entry) => entry.day === day) // Filtrar solo los elementos cuyo día coincide con el día actual
                .map((entry) => ({
                    id: entry.idArea,
                    lugar: `${entry.nameEdificer} - ${entry.nameFloor} - ${entry.nameArea} (${entry.supervisionTime})`,
                    type: 'daily',
                    idSupervision: entry.idSupervisionDays
                })),
            ...today
                .filter((entry) => entry.status === 0) // Filtrar solo los elementos con status 0
                .map((entry) => ({
                    id: entry.idArea,
                    lugar: `${entry.nameEdificer} - ${entry.nameFloor} - ${entry.nameArea} (${entry.time})`,
                    type: 'today',
                    mensaje: '¡Debe registrarse urgentemente hoy!',
                    idSupervision: entry.idSupervisionAreas
                }))
        );
        
    });

    // Mostrar lista dinámica
    function mostrarLista(titulo, lista, contenedor, claseItem) {
        // Configurar el título y el contenedor
        const $contenedor = $(contenedor);
        $contenedor.empty().removeClass("d-none");

        // Controladores para secciones únicas
        const seccionesMostradas = {
            daily: false,
            today: false
        };

        // Generar contenido dinámico de la lista
        lista.forEach((item) => {
            if (!seccionesMostradas[item.type]) {
                $contenedor.append(`<h5 class="text-center">${titulo}</h5>`);
                seccionesMostradas[item.type] = true;
            }

            $contenedor.append(`
                <li class="custom-list-item ${claseItem} ${item.type}-${item.idSupervision}" data-id="${item.id}" data-type="${item.type}" data-supervision="${item.idSupervision}">
                    ${item.nombre || item.lugar}
                    ${item.type === 'today' ? `<span class="urgent-message">${item.mensaje}</span>` : ''}
                    <i class="fas fa-angle-right"></i>
                </li>
            `);
        });

        // Gestionar visibilidad de otros contenedores
        const contenedoresRecorridos = [
            '#recorridoListSchool',
            '#recorridoListEdificer',
            '#recorridoListFloor',
            '#recorridoListZone',
            '#recorridoListArea'
        ];

        if (contenedor === '#recorridoList') {
            contenedoresRecorridos.forEach(id => $(id).addClass("d-none"));
        } else {
            $('#recorridoList').addClass("d-none");
        }

        // Alternar vistas
        alternarVista("#optionsContainer", "#listContainer");
    }
    
    // Alternar entre vistas
    function alternarVista(ocultar, mostrar) {
        $(ocultar).addClass("d-none");
        $(mostrar).removeClass("d-none");
    }

    // Eventos principales
    $("#startRecorrido").click(() => alternarVista("#startRecorrido", "#optionsContainer"));
    $("#backButton").click(() => alternarVista("#listContainer", "#optionsContainer"));

    // Mostrar lugares asignados
    $("#asignadosButton").click(() => mostrarLista("", lugaresAsignados, "#recorridoList", "asignado"));

    // Mostrar recorrido libre
    $("#libreButton").click(() => {
        realizarSolicitud('controller/forms.ajax.php', { action: 'searchSchool' }, (responses) => {
            const lugaresLibres = responses.map((response) => ({
                id: response.idSchool,
                nombre: response.nameSchool
            }));
            
            $("#listTitle").text('Recorrido libre');
            
            mostrarLista("Planteles", lugaresLibres, "#recorridoListSchool", "school");
            $('#recorridoListEdificer').addClass("d-none");
            $('#recorridoListFloor').addClass("d-none");
            $('#recorridoListZone').addClass("d-none");
            $('#recorridoListArea').addClass("d-none");
        });
    });

    // Delegación de eventos dinámicos
    $(document).on('click', '.school', function () {
        const id = $(this).data('id');
        realizarSolicitud('controller/forms.ajax.php', { action: 'searchEdificer', idSchool: id }, (response) => {
            const edificios = response.edificers.map((edificio) => ({
                id: edificio.idEdificers,
                nombre: edificio.nameEdificer
            }));
            mostrarLista("Edificios", edificios, "#recorridoListEdificer", "edificer");
            $('#recorridoListFloor').addClass("d-none");
            $('#recorridoListZone').addClass("d-none");
            $('#recorridoListArea').addClass("d-none");
        });
    });

    $(document).on('click', '.edificer', function () {
        const id = $(this).data('id');
        realizarSolicitud('controller/forms.ajax.php', { action: 'searchFloor', idEdificers: id }, (response) => {
            const pisos = response.floors.map((piso) => ({
                id: piso.idFloor,
                nombre: piso.nameFloor
            }));
            mostrarLista("Pisos", pisos, "#recorridoListFloor", "floor");
            $('#recorridoListZone').addClass("d-none");
            $('#recorridoListArea').addClass("d-none");
        });
    });

    $(document).on('click', '.floor', function () {
        const idFloor = $(this).data('id');
        const zonas = ["Norte", "Sur", "Este", "Oeste"].map((zona) => ({
            id: zona,
            nombre: zona
        }));
        mostrarLista("Zonas", zonas, "#recorridoListZone", "zone");

        $(document).on('click', '.zone', function () {
            const zone = $(this).data('id');
            realizarSolicitud('controller/forms.ajax.php', { action: 'getAreasForZone', zone, idFloor }, (response) => {
                const areas = response.areas.map((area) => ({
                    id: area.idArea,
                    nombre: area.nameArea
                }));
                mostrarLista("Áreas", areas, "#recorridoListArea", "area");
            });
        });
    });

    $(document).on('click', '.area, .asignado', function () {
        const idArea = $(this).data('id');
        const typeAction = $(this).data('type'); // Captura correcta del data-type
        const idSupervision = $(this).data('supervision');

        const modalFooter = $('#modalObjects .modal-footer');
            
        modalFooter.empty();

        const closeButton = typeAction === 'today'
            ? `<button type="button" class="btn btn-secondary" onclick="finalizar(${idSupervision})">Cerrar</button>`
            : `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>`;

        modalFooter.append(closeButton);

        $('#modalObjects').modal('show');
        realizarSolicitud('controller/forms.ajax.php', { action: 'getObjets', idArea: idArea }, (response) => {
            const { objets: objects, data } = response;
            const modalBody = $('#modalObjects .modal-body');
            const modalTitle = `
                                <span class="school-name">${data.nameSchool} -</span>
                                <span class="edificer-name">${data.nameEdificer} -</span>
                                <span class="floor-name">${data.nameFloor} -</span>
                                <span class="area-name">${data.nameArea}</span>
                                `;

            $('#modalObjects').find('.modal-title').html(modalTitle);

            modalBody.empty();
            if (!objects.length) {
                modalBody.append('<p>No hay objetos registrados para este área.</p>');
                return;
            } else {
                modalBody.append('<div class="row mb-3"><div class="col-2">Objeto</div><div class="col-1">Cantidad</div><div class="col-2">Urgencia</div><div class="col-2">Descripción</div><div class="col-2">Evidencia</div></div>');
            }

            objects.forEach(( object ) => {
                let idObject = object.idObject;
                let nameObject = object.nameObject;
                let quantity = object.quantity;
                let statusEvidence = object.statusEvidence;
                let urgency = (object.urgency === 'urgent') ? 'Urgente' : (object.urgency === 'immediate') ? 'De inmediato' : 'Sin urgencia';
                let description = object.description;
                let evidence = 'view/evidences/' + object.evidence;
                let row = '';
                
                const fileInput = $(`<input type="file" accept="image/*" style="display: none;" id="file-${idObject}">`);
                $('body').append(fileInput);
                //si el objeto tiene statusEvidence = 0 no se muestra
                let objectRow = '';
                if (statusEvidence !== 0) {
                
                    row =`
                        <div class="row mb-3 id-${idObject}" style="align-items: center;">
                            <div class="col-2">${nameObject}</div>
                            <div class="col-1">${quantity}</div>
                            <div class="col-2">
                                <select class="form-select urgency" data-id="${idObject}">
                                    <option value="">Seleccionar</option>
                                    <option value="urgent">Urgente</option>
                                    <option value="immediate">De inmediato</option>
                                    <option value="no-urgency">Sin urgencia</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <textarea class="form-control description" data-id="${idObject}" placeholder="Describir situación"></textarea>
                            </div>
                            <div class="col">
                                <button class="attach-evidence" data-id="${idObject}">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                            </div>
                            <div class="col">
                                <button class="take-photo" data-id="${idObject}">
                                    <i class="fad fa-camera"></i>
                                </button>
                            </div>
                            <div class="col">
                                <button class="send-object" data-id="${idObject}" disabled>
                                    <i class="fad fa-share-square"></i>
                                </button>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="preview-container" id="preview-${idObject}"></div>
                            </div>
                        </div>
                    `;
                } else {
                    // señalar como si ya se hubiera enviado
                    row = `
                        <div class="row mb-3 id-${idObject}" style="align-items: center;">
                            <div class="col-2">${nameObject}</div>
                            <div class="col-1">${quantity}</div>
                            <div class="col-2">${urgency}</div>
                            <div class="col-2">${description}</div>
                            <div class="col-2 mt-2">
                                <div class="preview-container" id="preview-${idObject}">
                                    <img src="${evidence}" style="max-width: 200px; max-height: 200px;" class="mt-2">
                                </div>
                            </div>
                            <div class="col">
                            </div>
                        </div>
                    `;
                }

                objectRow = $(row);

                modalBody.append(objectRow);

                const urgencyField = objectRow.find(`.urgency[data-id="${idObject}"]`);
                const descriptionField = objectRow.find(`.description[data-id="${idObject}"]`);
                const attachButton = objectRow.find(`.attach-evidence[data-id="${idObject}"]`);
                const sendButton = objectRow.find(`.send-object[data-id="${idObject}"]`);
                const previewContainer = $(`#preview-${idObject}`);

                const showImagePreview = (file) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewContainer.html(`
                                                <img src="${e.target.result}" style="max-width: 200px; max-height: 200px;" class="mt-2">
                                                <button class="btn btn-danger btn-sm ml-2 remove-image">&times;</button>
                                            `);
                        attachButton.data('evidence', file);
                        enableSendButton();
                    };
                    reader.readAsDataURL(file);
                };

                const enableSendButton = () => {
                    const hasUrgency = urgencyField.val();
                    const hasDescription = descriptionField.val().trim();
                    const hasEvidence = attachButton.data('evidence');
                    sendButton.prop('disabled', !(hasUrgency && hasDescription && hasEvidence));
                };

                attachButton.on('click', () => {
                    $(`#file-${idObject}`).click();
                });

                $(`#file-${idObject}`).on('change', function (e) {
                    if (this.files && this.files[0]) {
                        showImagePreview(this.files[0]);
                    }
                });

                const getMediaDevice = () => {
                    return new Promise((resolve, reject) => {
                        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                            resolve(navigator.mediaDevices.getUserMedia({ video: true }));
                        } else {
                            reject(new Error('getUserMedia no está soportado en este navegador.'));
                        }
                    });
                };

                objectRow.find(`.take-photo[data-id="${idObject}"]`).on('click', async () => {
                    if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                        alert('El acceso a la cámara requiere HTTPS.');
                        return;
                    }

                    let currentStream = null;
                    let facingMode = 'environment'; // Start with back camera
                    let hasMultipleCameras = false;

                    // Check for available cameras
                    try {
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        const videoDevices = devices.filter(device => device.kind === 'videoinput');
                        hasMultipleCameras = videoDevices.length > 1;
                    } catch (err) {
                        console.error('Error checking cameras:', err);
                    }

                    const videoElement = $('<video autoplay style="width: 75%;"></video>');
                    const cameraModal = $(`
                                        <div class="modal modal-fullscreen fade" id="cameraModal-${idObject}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="camera row">
                                                        <div class="col-10 camera" id="camera-container-${idObject}"></div>
                                                            <div class="col-2 text-center row">
                                                            ${hasMultipleCameras ? `
                                                                <button type="button" class="switch-camera">
                                                                    <i class="fad fa-sync-alt"></i>
                                                                </button>
                                                            ` : ''}
                                                            <button type="button" class="capture-photo">
                                                                <i class="fad fa-camera"></i>
                                                            </button>
                                                            <button type="button" class="cancel-photo" data-bs-dismiss="modal">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        `);

                    $('body').append(cameraModal);
                    $(`#camera-container-${idObject}`).append(videoElement);

                    const startCamera = (facingMode) => {
                        const constraints = {
                            video: {
                                facingMode: facingMode
                            }
                        };

                        // Stop any existing stream
                        if (currentStream) {
                            currentStream.getTracks().forEach(track => track.stop());
                        }

                        return navigator.mediaDevices.getUserMedia(constraints)
                            .then(stream => {
                                currentStream = stream;
                                const video = videoElement[0];
                                video.srcObject = stream;
                            })
                            .catch(err => {
                                console.error('Error accessing camera:', err);
                                if (err.name === 'NotFoundError' || err.name === 'OverconstrainedError') {
                                    alert('No se encontró la cámara solicitada o no está disponible.');
                                } else {
                                    alert('Error al acceder a la cámara: ' + err.message);
                                }
                            });
                    };

                    // Initialize camera
                    startCamera(facingMode);

                    // Handle camera switching (only if multiple cameras available)
                    if (hasMultipleCameras) {
                        cameraModal.find('.switch-camera').on('click', () => {
                            facingMode = facingMode === 'user' ? 'environment' : 'user';
                            startCamera(facingMode);
                        });
                    }

                    const modal = new bootstrap.Modal($(`#cameraModal-${idObject}`)[0]);
                    modal.show();

                    cameraModal.find('.capture-photo').on('click', () => {
                        const video = videoElement[0];
                        const canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        canvas.getContext('2d').drawImage(video, 0, 0);

                        canvas.toBlob(blob => {
                            const file = new File([blob], "photo.jpg", { type: "image/jpeg" });
                            showImagePreview(file);

                            if (currentStream) {
                                currentStream.getTracks().forEach(track => track.stop());
                            }
                            modal.hide();
                            cameraModal.remove();
                        }, 'image/jpeg');
                    });

                    cameraModal.on('hidden.bs.modal', () => {
                        if (currentStream) {
                            currentStream.getTracks().forEach(track => track.stop());
                        }
                        cameraModal.remove();
                    });
                });

                previewContainer.on('click', '.remove-image', function () {
                    previewContainer.empty();
                    attachButton.data('evidence', null);
                    enableSendButton();
                });

                urgencyField.on('change', enableSendButton);
                descriptionField.on('input', enableSendButton);

                sendButton.on('click', () => {
                    const formData = new FormData();
                    formData.append('action', 'uploadEvidence');
                    formData.append('idObject', idObject);
                    formData.append('urgency', urgencyField.val());
                    formData.append('description', descriptionField.val());
                    formData.append('evidence', attachButton.data('evidence'));

                    $.ajax({
                        url: 'controller/forms.ajax.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (response) {
                            if(response.status == 'success') {
                                alert('Objeto enviado correctamente');
                                $(`.id-${idObject}`).remove();
                            } else {
                                alert('Error al enviar los datos');
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('Error al enviar los datos: ' + error);
                        }
                    });
                });
            });
        });
    });

});

function finalizar(idSupervision) {
    //preguntar si esta seguro de finalizar la supervicion
    if (confirm('¿Está seguro de finalizar la supervisión?')) {
        $.ajax({
            url: 'controller/forms.ajax.php',
            type: 'POST',
            data: {
                action: 'finalizarSupervision',
                idSupervision: idSupervision
            },
            success: function (response) {
                $('#modalObjects').modal('hide');
                //eliminar el elemento de la lista con la clase today-${item.idSupervision}
                $(`.today-${idSupervision}`).remove();
            },
            error: function (xhr, status, error) {
                alert('Error al finalizar la supervisión:'+ error);
            }
        });
    }
}