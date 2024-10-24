var schoolName = '';
var zoneName = '';
var areaName = '';
var idSchoolSend = '';

$(document).ready(function() {
    var html = '';
    $.ajax({
        url: 'controller/ajax/getSchools.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            data.forEach(school => {
                html += `
                <li class="list-group-item group" onclick="searchZones(${school.idSchool}, '${school.nameSchool}')">
                    <div class="d-flex justify-content-between align-items-center">
                        ${school.nameSchool} <i class="fa-duotone fa-chevron-right"></i>
                    </div>
                </li>
                `;
            });
            $('.schools-list').html(html);
        }
    });
});

function searchZones(idSchool, nameSchool) {
    var html = '';
    $.ajax({
        url: 'controller/ajax/getZones.php',
        type: 'POST',
        dataType: 'json',
        data: {
            idSchool: idSchool
        },
        success: function(data) {
            if (data != false) {
                idSchoolSend = idSchool;
                schoolName = nameSchool;
                data.forEach(zone => {
                    html += `
                    <li class="list-group-item group" onclick="searchAreas(${zone.idZone}, '${zone.nameZone}')">
                        <div class="d-flex justify-content-between align-items-center">
                            ${zone.nameZone} <i class="fa-duotone fa-chevron-right"></i>
                        </div>
                    </li>
                    `;
                });
                $('.schoolName').html(' - '+nameSchool);
                $('.zones-list').html(html);
                $('.areas-list').html('');
            } else {
                $('.zoneName').html('');
                $('.zones-list').html(`
                <div class="d-flex justify-content-between align-items-center">
                No hay zonas registradas
                </div>
                `);
                $('.areas-list').html('');
            }
        }
    });
}

function searchAreas(idZone, nameZone) {
    $('.zoneName').html(' - '+nameZone);
    var html = '';
    $.ajax({
        url: 'controller/ajax/getAreas.php',
        type: 'POST',
        dataType: 'json',
        data: {
            idZone: idZone
        },
        success: function(data) {
            
            if (data != false) {
                data.forEach(zone => {
                    zoneName = nameZone;
                    html += `
                    <li class="list-group-item group" onclick="showForms(${zone.idArea}, '${zone.nameArea}')">
                        <div class="d-flex justify-content-between align-items-center">
                            ${zone.nameArea} <i class="fa-duotone fa-chevron-right"></i>
                        </div>
                    </li>
                    `;
                });
                $('.areas-list').html(html);
            } else {
                $('.areas-list').html(`
                    <div class="d-flex justify-content-between align-items-center">
                        No hay areas registradas
                    </div>
                `);
            }
        }
    });
}

function showForms(idArea, nameArea) {
    var html = '';
    $('#modalList').modal('show');

    $.ajax({
        url: 'controller/ajax/getObjetos.php',
        type: 'POST',
        dataType: 'json',
        data: { idArea: idArea },
        success: function(data) {
            areaName = nameArea;
            if (data) {
                data.forEach(object => {
                    html += `
                        <tr class="${object.idObject}">
                            <th>${object.nameObject.charAt(0).toUpperCase() + object.nameObject.slice(1).toLowerCase()}</th>
                            <td>${object.cantidad}</td>
                            <td class="datetime">${updateDateTime()}</td>
                            <td><input type="text" id="description_${object.idObject}" class="form-control"></td>
                            <td>
                                <label><input type="radio" id="concepto_${object.idObject}" name="concepto_${object.idObject}" value="En espera"> No urgente</label><br>
                                <label><input type="radio" id="concepto_${object.idObject}" name="concepto_${object.idObject}" value="Urgente"> Urgente (24 hrs)</label><br>
                                <label><input type="radio" id="concepto_${object.idObject}" name="concepto_${object.idObject}" value="Inmediata"> Inmediata</label>
                            </td>
                            <td>
                                <input type="file" id="file_${object.idObject}" class="form-control" accept="image/*" multiple>
                            </td>
                            <td>
                                <button class="sendButton" id="button_${object.idObject}" onclick="sendForm(${object.idObject})" disabled>
                                    <i class="fad fa-external-link"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
            $('.tbodyObjects').html(html);
            $('#modalListLabel').html(`${schoolName} - ${zoneName} - ${areaName}`);

            // Inicializa el DataTable
            $('#objects').DataTable();

            // Agregar control de eventos para verificar campos antes de enviar
            addFormEventListeners();
        }
    });
}

function addFormEventListeners() {
    $('.form-control, input[type=radio], input[type=file]').on('change keyup', function() {
        var id = $(this).attr('id');
        if (id) { // Verificar si el atributo 'id' está definido
            var idObject = id.split('_')[1];
            var description = $(`#description_${idObject}`).val();
            var concepto = $(`input[name=concepto_${idObject}]:checked`).val();
            // Verificar si los tres campos están llenos o seleccionados
            if (description.trim() !== '' && concepto) {
                $(`#button_${idObject}`).prop('disabled', false); // Activar el botón de enviar
            } else {
                $(`#button_${idObject}`).prop('disabled', true); // Desactivar el botón de enviar
            }
        }
    });
}

function updateDateTime() {
    var currentDate = new Date(); // Obtiene la fecha y hora actual
    var formattedDate = currentDate.toLocaleString(); // Formatea la fecha y hora
    return formattedDate;
}

function sendForm(idObject) {
    var estado = 2;
    var description = $('#description_' + idObject).val();
    var files = $('#file_' + idObject).get(0).files;
    var importancia = $('input[name=concepto_' + idObject + ']:checked').val();

    var formData = new FormData();
    formData.append('idObject', idObject);
    formData.append('estado', estado);
    formData.append('description', description);
    formData.append('importancia', importancia);
    formData.append('schoolName', schoolName);
    formData.append('zoneName', zoneName);
    formData.append('areaName', areaName);
    formData.append('idSchool', idSchoolSend);

    var filesData = [];
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        formData.append('files[]', file);
        filesData.push({
            name: file.name,
            type: file.type
        });
    }
    formData.append('filesData', JSON.stringify(filesData));

    $.ajax({
        url: 'controller/ajax/sendForm.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data == 'ok') {
                $('.'+idObject).remove();
            }
        }
    });
}