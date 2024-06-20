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
        data: {
            idArea: idArea
        },
        success: function(data) {
            areaName = nameArea;
            if (data != false) {
                data.forEach(object => {
                        html += `
                            <tr class="${object.idObject}">
                                <th>${object.nameObject}</th>
                                <td>${object.cantidad}</td>
                                <td class="datetime">${updateDateTime()}</td>
                                <td>
                                    <select id="estado_${object.idObject}" class="form-control">
                                        <option value="">Seleccione una opción</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </td>
                                <td><input type="text" id="description_${object.idObject}" class="form-control"></td>
                                <td>
                                    <label><input type="radio" id="concepto_${object.idObject}" name="concepto_${object.idObject}" value="Pendiente"> Pendiente</label><br>
                                    <label><input type="radio" id="concepto_${object.idObject}" name="concepto_${object.idObject}" value="Urgente"> Urgente</label><br>
                                    <label><input type="radio" id="concepto_${object.idObject}" name="concepto_${object.idObject}" value="Inmediata"> Inmediata</label>
                                </td>
                                <td>
                                    <button class="sendButton" id="button_${object.idObject}" onclick="sendForm(${object.idObject})" disabled>
                                        <i class="fa-duotone fa-paper-plane-top mr-2"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                });
            }
            $('.tbodyObjects').html(html);
            $('#modalListLabel').html(schoolName + ' - ' + zoneName + ' - ' + areaName);
            // Inicializa el DataTable
            $('#objects').DataTable();

            // Agregar control de eventos para verificar campos antes de enviar
            $('.form-control, input[type=radio]').on('change keyup', function() {
                var id = $(this).attr('id');
                if (id) { // Verificar si el atributo 'id' está definido
                    var idObject = id.split('_')[1];
                    var estado = $('#estado_' + idObject).val();
                    var description = $('#description_' + idObject).val();
                    var concepto = $('input[name=concepto_' + idObject + ']:checked').val();
                    // Verificar si los tres campos están llenos o seleccionados
                    if (estado.trim() !== '' && description.trim() !== '' && concepto) {
                        $('#button_' + idObject).prop('disabled', false); // Activar el botón de enviar
                    } else {
                        $('#button_' + idObject).prop('disabled', true); // Desactivar el botón de enviar
                    }
                }
            });
            
        }
    });
}

function updateDateTime() {
    var currentDate = new Date(); // Obtiene la fecha y hora actual
    var formattedDate = currentDate.toLocaleString(); // Formatea la fecha y hora
    return formattedDate;
}

function sendForm(idObject){
    var estado = $('#estado_' + idObject).val();
    var description = $('#description_' + idObject).val();
    var importancia = $('input[name=concepto_' + idObject + ']:checked').val();

    $.ajax({
        url: 'controller/ajax/sendForm.php',
        type: 'POST',
        dataSrc: '',
        data: {
            idObject: idObject,
            estado: estado,
            description: description,
            importancia: importancia,
            schoolName: schoolName,
            zoneName: zoneName,
            areaName: areaName,
            idSchool: idSchoolSend
        },
        success: function(data) {
            if (data == 'ok') {
                $('.'+idObject).remove();
            }
        }
    });
}
