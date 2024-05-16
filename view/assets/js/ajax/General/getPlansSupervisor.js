$(document).ready(function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        contentHeight: 300,
        aspectRatio: 5,
        hiddenDays: [0, 6],
        dateClick: function(info) {
            $('#eventDate').val(info.dateStr);
        },
        eventClick: function(info) {
            // Mostrar alerta con el título del evento
            alert('Evento: ' + info.event.title);
        }
    });

    // Render the calendar
    calendar.render();

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
});