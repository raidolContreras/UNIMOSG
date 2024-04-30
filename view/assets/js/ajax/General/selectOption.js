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
    $('.schoolName').html(' - '+nameSchool);
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
                data.forEach(zone => {
                    html += `
                    <li class="list-group-item group" onclick="searchAreas(${zone.idZone}, '${zone.nameZone}')">
                        <div class="d-flex justify-content-between align-items-center">
                            ${zone.nameZone} <i class="fa-duotone fa-chevron-right"></i>
                        </div>
                    </li>
                    `;
                });
                $('.zones-list').html(html);
                $('.areas-list').html('');
            } else {
                $('.zones-list').html(`
                    <div class="d-flex justify-content-between align-items-center">
                        No hay zonas registradas
                    </div>
                `);
            }
        }
    });
}

function searchAreas(idZone, zoneName) {
    $('.zoneName').html(' - '+zoneName);
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
                    html += `
                    <li class="list-group-item group" onclick="showForms(${zone.idArea})">
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