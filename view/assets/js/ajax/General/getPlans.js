
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
            $('#school').val('');
            $('#zone').val('');
            $('#area').val('');
            
            $('#zone').prop('disabled', true);
            $('#area').prop('disabled', true);
            $('#supervisor').val('');
        }
    });

    // Render the calendar
    calendar.render();

    // Handle form submission
    $('#eventForm').on('submit', function(e) {
        e.preventDefault();
        
        var school = $('#school').val();
        var zone = $('#zone').val();
        var area = $('#area').val();
        var supervisor = $('#supervisor').val();
        var eventDate = $('#eventDate').val();
        if (school == '' || zone == '' || area == '' || supervisor == ''){
            
        } else {
            
            $.ajax({
                url: 'controller/ajax/addPlans.php',
                type: 'POST',
                data: {
                    school: school,
                    zone: zone,
                    area: area,
                    supervisor: supervisor,
                    eventDate: eventDate,
                },
                dataType: 'json',
                success: function(data) {
                    calendar.addEvent({
                        title: data.nameSchool + ' - ' + data.nameZone + ' - ' + data.nameArea,
                        start: data.datePlan
                    });
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
                    title: data[i].nameSchool + ' - ' + data[i].nameZone + ' - ' + data[i].nameArea,
                    start: data[i].datePlan
                });
            }
            calendar.addEventSource(events);
        }
    });

    $('#school').on('change', function() {
        var idSchool = $('#school').val();
        if (idSchool != '') {
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
                        html += '<option value="' + data[i].idZone + '">' + data[i].nameZone + '</option>';
                    }
                    
                    $('#zone').prop('disabled', false); // Quitar el disabled
                    $('#zone').html(html);
                    
                    var area = '<option value="">Selecciona un area</option>';
                    $('#area').html(area);
                    $('#area').prop('disabled', true);
                }
            });
        } else {
            $('#zone').prop('disabled', true);
            var area = '<option value="">Selecciona un area</option>';
            $('#area').html(area);
            $('#area').prop('disabled', true);
        }
    });

    $('#zone').on('change', function() {
        var idZone = $('#zone').val();
        if (idZone != '') {
            $.ajax({
                url: 'controller/ajax/getAreas.php',
                type: 'POST',
                data: {
                    idZone: idZone
                },
                dataType: 'json',
                success: function(data) {
                    var html = '<option value="">Selecciona un area</option>';
                    for (var i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].idArea + '">' + data[i].nameArea + '</option>';
                    }
                    
                    $('#area').prop('disabled', false); // Quitar el disabled
                    $('#area').html(html);
                }
            });
        } else {
            $('#area').prop('disabled', true);
        }
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

function closeModal() {
    $('#eventModal').modal('hide');
}