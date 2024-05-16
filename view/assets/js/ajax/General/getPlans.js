$(document).ready(function () {
    getSchools();
    getSupervisor();
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        contentHeight: 300,
        aspectRatio: 5,
        hiddenDays: [0, 6],
        dateClick: function(info) {
            $('#eventDate').val(info.dateStr);
            $('#eventModal').modal('show');
            $('#plan').val('');
            $('#school').val('');
            $('#zone').val('');
            $('#area').val('');
            $('#zone').prop('disabled', true);
            $('#area').prop('disabled', true);
            $('#eventDate').prop('readonly', true);
            $('#supervisor').val('');
        },
        eventContent: function(arg) {
            let eventTitle = arg.event.title;
            let eventEl = document.createElement('div');
            eventEl.className = 'custom-event';
    
            let buttonEl = document.createElement('div');
            buttonEl.className = 'event-button col-11'; // Asigna una clase al botón
            buttonEl.textContent = eventTitle;
            buttonEl.onclick = function() {
                getPlan(arg.event.id);
            };
    
            let deleteButtonEl = document.createElement('button');
            deleteButtonEl.className = 'delete-button col-3'; // Asigna una clase al botón de eliminar
            deleteButtonEl.innerHTML = '&times;'; // Usar entidad HTML para "x"
            deleteButtonEl.onclick = function() {
                if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
                    arg.event.remove(); // Elimina el evento del calendario
                    // Aquí puedes agregar una llamada AJAX para eliminar el evento del servidor si es necesario
                    $.ajax({
                        url: 'controller/ajax/deletePlan.php', // Asegúrate de crear este endpoint en tu servidor
                        type: 'POST',
                        data: { id: arg.event.id },
                        success: function(response) {
                            alert('Evento eliminado correctamente.');
                        }
                    });
                }
            };
    
            eventEl.appendChild(buttonEl);
            eventEl.appendChild(deleteButtonEl);
            return { domNodes: [eventEl] };
        }
    });    

    // Render the calendar
    calendar.render();

    // Handle form submission
    $('#eventForm').on('submit', function(e) {
        e.preventDefault();
    
        var school = $('#school').val();
        var plan = $('#plan').val();
        var zone = $('#zone').val();
        var area = $('#area').val();
        var supervisor = $('#supervisor').val();
        var eventDate = $('#eventDate').val();
    
        if (school == '' || zone == '' || area == '' || supervisor == '') {
            alert("Todos los campos son obligatorios.");
        } else {
            $.ajax({
                url: 'controller/ajax/addPlans.php',
                type: 'POST',
                data: {
                    plan: plan,
                    school: school,
                    zone: zone,
                    area: area,
                    supervisor: supervisor,
                    eventDate: eventDate,
                },
                dataType: 'json',
                success: function(data) {
                    if (plan != '') {
                        // Eliminar el evento existente si es necesario
                        var existingEvent = calendar.getEventById(data.idPlan);
                        if (existingEvent) {
                            existingEvent.remove();
                        }
                        // Crear un nuevo evento
                        calendar.addEvent({
                            id: data.idPlan,
                            title: `${data.nameSchool} - ${data.nameZone} - ${data.nameArea}`,
                            start: data.datePlan
                        });
                    }
                    $('#eventModal').modal('hide');
                }
            });
        }
    });

    // Load initial events
    $.ajax({
        url: 'controller/ajax/getPlans.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            var events = [];
            for (var i = 0; i < data.length; i++) {
                events.push({
                    id: data[i].idPlan,
                    title: `${data[i].nameSchool} - ${data[i].nameZone} - ${data[i].nameArea}`,
                    start: data[i].datePlan
                });
            }
            calendar.addEventSource(events);
        }
    });

    $('#school').on('change', function() {
        var idSchool = $('#school').val();
        if (idSchool != '') {
            getZones(idSchool, '');
        } else {
            $('#zone').prop('disabled', true);
            var area = '<option value="">Selecciona un área</option>';
            $('#area').html(area);
            $('#area').prop('disabled', true);
        }
    });

    $('#zone').on('change', function() {
        var idZone = $('#zone').val();
        if (idZone != '') {
            getAreas(idZone, '');
        } else {
            $('#area').prop('disabled', true);
        }
    });

    $('#openModalBtn').on('click', function() {
        $('#eventModal').modal('show');
    });
});

function getSchools() {
    $.ajax({
        url: 'controller/ajax/getSchools.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            var html = '<option value="">Selecciona una escuela</option>';
            for (var i = 0; i < data.length; i++) {
                html += '<option value="' + data[i].idSchool + '">' + data[i].nameSchool + '</option>';
            }
            $('#school').html(html);
        }
    });
}

function getSupervisor() {
    $.ajax({
        url: 'controller/ajax/getUsers.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            var html = '<option value="">Selecciona un supervisor</option>';
            for (var i = 0; i < data.length; i++) {
                if (data[i].level == 2){
                    html += '<option value="' + data[i].idUsers + '">' + data[i].name + '</option>';
                }
            }
            $('#supervisor').html(html);
        }
    });
}

function getAreas(idZone, idArea) {
    
    $.ajax({
        url: 'controller/ajax/getAreas.php',
        type: 'POST',
        data: {
            idZone: idZone
        },
        dataType: 'json',
        success: function(data) {
            var html = '<option value="">Selecciona un área</option>';
            for (var i = 0; i < data.length; i++) {
                var select = (idArea != '' && data[i].idArea == idArea) ?'selected' :'' ;
                html += '<option ' + select + ' value="' + data[i].idArea + '">' + data[i].nameArea + '</option>';
            }
            
            $('#area').prop('disabled', false); // Quitar el disabled
            $('#area').html(html);
        }
    });
}

function getZones(idSchool, idZone) {
    $.ajax({
        url: 'controller/ajax/getZones.php',
        type: 'POST',
        data: {
            idSchool: idSchool
        },
        dataType: 'json',
        success: function(data) {
            var html = '<option value="">Selecciona una zona</option>';
            for (var i = 0; i < data.length; i++) {
                var select = (idZone != '' && data[i].idZone == idZone) ?'selected' :'' ;
                html += '<option ' + select + ' value="' + data[i].idZone + '">' + data[i].nameZone + '</option>';
            }
            
            $('#zone').prop('disabled', false); // Quitar el disabled
            $('#zone').html(html);
            
            var area = '<option value="">Selecciona un área</option>';
            $('#area').html(area);
            $('#area').prop('disabled', true);
        }
    });
}

function closeModal() {
    $('#eventModal').modal('hide');
}

function getPlan(plan) {
    $('#eventModal').modal('show');
    var school = $('#school');
    var zone = $('#zone');
    var area = $('#area');
    var eventDate = $('#eventDate');
    var supervisor = $('#supervisor');

    $('#plan').val(plan);

    school.val('');
    zone.val('');
    area.val('');
    zone.prop('disabled', true);
    area.prop('disabled', true);

    $.ajax({
        url: 'controller/ajax/getPlans.php',
        type: 'POST',
        data: {
            plan: plan
        },
        dataType: 'json',
        success: function(data) {

            setTimeout(() => {
                school.val(data.idSchool);

                getZones(data.idSchool, data.idZone);

                setTimeout(() => {
                    getAreas(data.idZone, data.idArea);
                }, "100");

                supervisor.val(data.idSupervisor);
                eventDate.val(data.datePlan);

                eventDate.prop('readonly', false);
              }, "100");
        }
    });
}
