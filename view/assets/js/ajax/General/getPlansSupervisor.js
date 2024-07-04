var idUser = $('#idUser').val();

function getSupervisionDays(calendar) {
    $.ajax({
        url: "controller/ajax/getSupervisionDays.php",
        type: "POST",
        dataType: "json",
        data: {user: idUser},
        success: function (data) {
            if (data) {
                const events = data.flatMap(generateWeeklyEvents);
                calendar.addEventSource(events);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching supervision days:", error);
        }
    });
}

function generateWeeklyEvents(event) {
    const events = [];
    const today = new Date();
    let currentDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

    for (let i = 0; i < 52; i++) {
        const eventDate = getNextDate(currentDate, event.day);
        events.push({
            title: `${event.nameSchool} - ${event.nameZone} - ${event.nameArea}`,
            start: eventDate.toISOString().split('T')[0],
            id: event.id,
            deletable: false,
            backgroundColor: 'green'
        });
        currentDate.setDate(currentDate.getDate() + 7);
    }
    return events;
}

function getNextDate(startDate, dayOfWeek) {
    const date = new Date(startDate);
    const day = date.getDay();
    const diff = (dayOfWeek - day + 7) % 7 || 7; // Simplified
    date.setDate(date.getDate() + diff);
    return date;
}

$(document).ready(function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        contentHeight: 300,
        aspectRatio: 5,
        hiddenDays: [0, 6],
        dateClick: function(info) {
            $('#eventDate').val(info.dateStr);
        },
        eventClick: function(info) {
            alert('Evento: ' + info.event.title);
        }
    });

    calendar.render();

    $.ajax({
        url: 'controller/ajax/getPlans.php',
        type: 'POST',
        dataType: 'json',
        data: {user: idUser},
        success: function(data) {
            if (data) {
                const events = data.map(item => ({
                    title: `${item.nameSchool} - ${item.nameZone} - ${item.nameArea}`,
                    start: item.datePlan
                }));
                calendar.addEventSource(events);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching plans:", error);
        }
    });

    getSupervisionDays(calendar);
});

function seeInspectionsback() {
    $.ajax({
        url: 'controller/ajax/searchIncidentsDaily.php',
        dataType: 'json',
        success: function(data) {
            // Handle the response data here if needed
        },
        error: function (xhr, status, error) {
            console.error("Error fetching incidents:", error);
        }
    });
}
