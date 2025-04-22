$(document).ready(function () {

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
                    <i class="fal fa-angle-right"></i>
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
    }
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
		realizarSolicitud('controller/forms.ajax.php', { action: 'getObjectsBad', idArea: idArea }, (response) => {
			const { objets: objects, data } = response;
			const modalBody = $('#modalObjects .modal-body');
			const modalTitle = `
								<span class="school-name">${data.nameSchool} -</span>
								<span class="edificer-name">${data.nameEdificer} -</span>
								<span class="floor-name">${data.nameFloor} -</span>
								<span class="area-name">${data.nameArea}</span>
								`;

			const chatId = data.chatId;

			$('#modalObjects').find('.modal-title').html(modalTitle);

			modalBody.empty();
			if (!objects.length) {
				modalBody.append('<p>No hay objetos registrados para este área.</p>');
				return;
			} else {
				modalBody.append('<div class="row mb-3"><div class="col-2">Objeto</div><div class="col-1">Cantidad</div><div class="col-1">Marcar como correcto</div><div class="col-2">Urgencia</div><div class="col-2">Descripción</div><div class="col-2">Evidencia</div></div>');
			}
			let idObjectbefore = 0;
			objects.forEach(( object ) => {
				let idObject = object.idObject;
				let nameObject = object.nameObject;
				let quantity = object.quantity;
				let isOk = object.isOk;
				let statusEvidence = object.statusEvidence;
				let urgency = (object.urgency === 'urgent') ? 'Urgente' : (object.urgency === 'immediate') ? 'De inmediato' : 'Sin urgencia';
				let description = object.description;
				let evidence = 'view/evidences/' + object.evidence;
				let idEvidence = object.idEvidence;
				let row = '';
				
				if (idObjectbefore != idObject){
					const fileInput = $(`<input type="file" accept="image/*" style="display: none;" id="file-${idObject}">`);
					$('body').append(fileInput);
					//si el objeto tiene statusEvidence = 0 no se muestra
					let objectRow = '';
					if (statusEvidence !== 0) {
					//agregar checkbox de si esta bien o no

						row = `
						<div class="row mb-3 id-${idObject}" style="align-items: center;">
							<div class="col-2">${nameObject}</div>
							<div class="col-1">${quantity}</div>
							<div class="col-1">
								<div class="checkbox-wrapper-7">
									<input class="tgl tgl-ios" id="object-${idObject}" data-id="${idObject}" type="checkbox" ${isOk == 1 ? 'checked' : ''}/>
									<label class="tgl-btn" for="object-${idObject}"></label>
								</div>
							</div>
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
							<div class="col-1">
								<button class="attach-evidence" data-id="${idObject}">
									<i class="fal fa-paperclip"></i>
								</button>
							</div>
							<div class="col-1">
								<button class="take-photo" data-id="${idObject}">
									<i class="fal fa-camera"></i>
								</button>
							</div>
							<div class="col-1">
								<button class="send-object" data-id="${idObject}" disabled>
									<i class="fal fa-share-square"></i>
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
								<div class="col-1"></div>
								<div class="col-2">${urgency}</div>
								<div class="col-2">${description}</div>
								<div class="col-2 mt-2">
									<div class="preview-container" id="preview-${idObject}">
										<img src="${evidence}" style="max-width: 200px; max-height: 200px;" class="mt-2">
									</div>
								</div>
								<div class="col">
									<button class="btn btn-success btn-sm" id="correctedObject" data-id="${idObject}" data-evidence="${idEvidence}" data-chatid="${chatId}">
										<i class="fal fa-check"></i>
									</button>
								</div>
							</div>
						`;
					}

					objectRow = $(row);

					modalBody.append(objectRow);
					
					if (isOk == 1) {
						// Ocultar complementos
						$(`.id-${idObject} .urgency, 
						.id-${idObject} .description, 
						.id-${idObject} .attach-evidence, 
						.id-${idObject} .take-photo`).hide();
				
						// Habilitar el botón de enviar
						$(`.id-${idObject} .send-object`).prop('disabled', false);
					} else {
						// Mostrar complementos si se desmarca
						$(`.id-${idObject} .urgency, 
						.id-${idObject} .description, 
						.id-${idObject} .attach-evidence, 
						.id-${idObject} .take-photo`).show();
				
						// Deshabilitar el botón de enviar
						$(`.id-${idObject} .send-object`).prop('disabled', true);
					}
				
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
																		<i class="fal fa-sync-alt"></i>
																	</button>
																` : ''}
																<button type="button" class="capture-photo">
																	<i class="fal fa-camera"></i>
																</button>
																<button type="button" class="cancel-photo" data-bs-dismiss="modal">
																	<i class="fal fa-times"></i>
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
						let evidence = attachButton.data('evidence');
						const formData = new FormData();
						formData.append('action', 'uploadEvidence');
						formData.append('idObject', idObject);
						formData.append('urgency', urgencyField.val());
						formData.append('description', descriptionField.val());
						formData.append('evidence', evidence);
						if (urgencyField.val() === '') {
							return;
						}
						$.ajax({
							url: 'controller/forms.ajax.php',
							type: 'POST',
							data: formData,
							processData: false,
							contentType: false,
							dataType: 'json',
							success: function (response) {
								if(response.status == 'success') {
									sendTelegramMessage(idObject, 'Descripción del incidente: ' + descriptionField.val(), evidence, chatId);
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
				}
				idObjectbefore = idObject;
			});
		});
	});

	$(document).on('change', '.tgl', function () {
		const idObject = $(this).data('id');
		const isChecked = $(this).is(':checked');
	
		if (isChecked) {
			// Ocultar complementos
			$(`.id-${idObject} .urgency, 
			   .id-${idObject} .description, 
			   .id-${idObject} .attach-evidence, 
			   .id-${idObject} .take-photo`).hide();
	
			// Habilitar el botón de enviar
			$(`.id-${idObject} .send-object`).prop('disabled', false);
		} else {
			// Mostrar complementos si se desmarca
			$(`.id-${idObject} .urgency, 
			   .id-${idObject} .description, 
			   .id-${idObject} .attach-evidence, 
			   .id-${idObject} .take-photo`).show();
	
			// Deshabilitar el botón de enviar
			$(`.id-${idObject} .send-object`).prop('disabled', true);
		}
	});
	
	$(document).on('click', '.send-object', function () {
		const idObject = $(this).data('id');
	
		$.ajax({
			url: 'controller/forms.ajax.php',
			type: 'POST',
			data: {
				action: 'confirmCorrectObject',
				idObject: idObject,
				isCorrect: 1
			},
			success: function (response) {
				$(`.id-${idObject}`).remove();
			},
			error: function () {
				alert('Error al enviar los datos');
			}
		});
	});

	$(document).on('click', '#correctedObject', function () {
		const idObject = $(this).data('id');
		const idEvidence = $(this).data('evidence');
		const chatId = $(this).data('chatid');
		
		// Crear un modal para subir o tomar foto
		const modalOptions = `
			<div class="modal fade" id="correctedModal-${idObject}" tabindex="-1" aria-labelledby="correctedModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="correctedModalLabel">Evidencia del Objeto Corregido</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
						</div>
						<div class="modal-body text-center row mb-3" style="justify-content: space-evenly;">
							<button class="attach-evidence upload-photo" data-id="${idObject}">
								<i class="fal fa-paperclip" aria-hidden="true"></i>
							</button>
							<button class="take-photo" data-id="${idObject}">
								<i class="fal fa-camera" aria-hidden="true"></i>
							</button>
							<input type="file" accept="image/*" id="upload-${idObject}" style="display: none;">
							<div id="preview-corrected-${idObject}" class="mt-3"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-success confirm-corrected" data-id="${idObject}" disabled>Confirmar</button>
						</div>
					</div>
				</div>
			</div>
		`;
	
		$('body').append(modalOptions);
		const correctedModal = new bootstrap.Modal($(`#correctedModal-${idObject}`)[0]);
		correctedModal.show();
	
		// Cargar imagen
		$(`.upload-photo[data-id="${idObject}"]`).on('click', function () {
			$(`#upload-${idObject}`).click();
		});
	
		$(`#upload-${idObject}`).on('change', function (e) {
			const file = e.target.files[0];
			if (file) {
				const reader = new FileReader();
				reader.onload = function (e) {
					$(`#preview-corrected-${idObject}`).html(`<img src="${e.target.result}" style="max-width: 200px; max-height: 200px;">`);
					$(`.confirm-corrected[data-id="${idObject}"]`).prop('disabled', false).data('evidence', file);
				};
				reader.readAsDataURL(file);
			}
		});
		
		// Declarar el stream globalmente para accederlo desde varias funciones
		let currentStream = null;

		// Tomar foto
		$(`.take-photo[data-id="${idObject}"]`).on('click', function () {
			const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
			const facingMode = isMobile ? 'environment' : 'user';

			// Iniciar la cámara
			navigator.mediaDevices.getUserMedia({ video: { facingMode: facingMode } })
				.then((stream) => {
					currentStream = stream; // Guardar el stream globalmente
					const videoElement = $('<video autoplay style="width: 100%;"></video>');
					const captureButton = $('<button class="btn btn-danger mt-2">📸 Capturar</button>');
					$(`#preview-corrected-${idObject}`).html(videoElement).append(captureButton);
					videoElement[0].srcObject = stream;

					// Capturar imagen
					captureButton.on('click', function () {
						const canvas = document.createElement('canvas');
						canvas.width = videoElement[0].videoWidth;
						canvas.height = videoElement[0].videoHeight;
						canvas.getContext('2d').drawImage(videoElement[0], 0, 0);

						canvas.toBlob((blob) => {
							const file = new File([blob], "evidence.jpg", { type: "image/jpeg" });
							const imageUrl = URL.createObjectURL(blob);
							$(`#preview-corrected-${idObject}`).html(`<img src="${imageUrl}" style="max-width: 200px; max-height: 200px;">`);
							$(`.confirm-corrected[data-id="${idObject}"]`).prop('disabled', false).data('evidence', file);

							stopCamera();  // Apagar cámara después de capturar la imagen
						}, 'image/jpeg');
					});
				})
				.catch((err) => {
					alert('Error al acceder a la cámara: ' + err.message);
				});
		});

		// Detener la cámara
		function stopCamera() {
			if (currentStream) {
				currentStream.getTracks().forEach(track => track.stop());
				currentStream = null;  // Liberar el recurso
			}
		}

		// Confirmar con evidencia
		$(`.confirm-corrected[data-id="${idObject}"]`).on('click', function () {
			const evidenceFile = $(this).data('evidence');
			const formData = new FormData();
			formData.append('action', 'confirmCorrectObject');
			formData.append('idObject', idObject);
			formData.append('isCorrect', 1);
			formData.append('evidence', evidenceFile);
			formData.append('idEvidence', idEvidence);
	
			$.ajax({
				url: 'controller/forms.ajax.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function (response) {
					correctedModal.hide();
					$(`#correctedModal-${idObject}`).remove();
					$(`.id-${idObject}`).remove();
					// enviar mensaje de telegram como correcto
					sendTelegramMessage(idObject, 'Objeto corregido y evidencia enviada.', evidenceFile, chatId);
				},
				error: function () {
					alert('Error al enviar la evidencia.');
				}
			});
		});
	
		// Limpiar el modal al cerrarlo
		$(`#correctedModal-${idObject}`).on('hidden.bs.modal', function () {
			stopCamera();  // Apagar la cámara al cerrar el modal
			$(this).remove();
		});
	});

});

function sendTelegramMessage(idObject, message, file = null, chatId) {
    const formData = new FormData();
    formData.append('action', 'sendMessageTelegram');
    formData.append('idObject', idObject);
    formData.append('message', message);
	formData.append('chatId', chatId);

    // Verificar si hay un archivo para enviar
    if (file) {
        formData.append('file', file);
    }

    $.ajax({
        url: 'controller/forms.ajax.php',
        type: 'POST',
        data: formData,
        processData: false, // Importante para enviar FormData correctamente
        contentType: false, // Importante para enviar FormData correctamente
        success: function (response) {
        },
        error: function (xhr, status, error) {
            console.error('Error sending Telegram message:' + error);
        }
    });
}