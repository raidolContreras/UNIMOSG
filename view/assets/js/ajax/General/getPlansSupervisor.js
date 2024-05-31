function getSupervisionDays(calendar) {
    $.ajax({
        url: "controller/ajax/getSupervisionDays.php",
        type: "POST",
        dataType: "json",
        success: function (data) {
            let events = data.flatMap(event => {
                return generateWeeklyEvents(event);
            });
            calendar.addEventSource(events);
        }
    });
}

// Helper function to generate weekly events
function generateWeeklyEvents(event) {
    let events = [];
    let today = new Date();
    let currentDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

    // Loop through the next 52 weeks
    for (let i = 0; i < 52; i++) {
        let eventDate = getNextDate(currentDate, event.day);
        events.push({
            title: `${event.nameSchool} - ${event.nameZone} - ${event.nameArea}`,
            start: eventDate.toISOString().split('T')[0], // format as YYYY-MM-DD
            id: event.id, // Assuming each event has a unique id
            deletable: false, // Mark these events as non-deletable
            backgroundColor: 'green' // Set the color to green
        });
        // Move to the next week
        currentDate.setDate(currentDate.getDate() + 7);
    }
    return events;
}

// Helper function to get the next occurrence of a specific day of the week
function getNextDate(startDate, dayOfWeek) {
    let date = new Date(startDate);
    let day = date.getDay();
    let diff = (dayOfWeek - day + 7) % 7;
    if (diff === 0 && dayOfWeek !== day) {
        diff += 7; // ensure the next occurrence is in the future
    }
    date.setDate(date.getDate() + diff);
    return date;
}
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
    getSupervisionDays(calendar);
});

function seeInspectionsback() {
    $.ajax({
        url: 'controller/ajax/searchIncidentsDaily.php',
        dataType: 'json',
        success: function(data) {
        }
    });
}