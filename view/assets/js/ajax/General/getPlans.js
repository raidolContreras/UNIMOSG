function getSupervisionDays(calendar) {
    $.ajax({
        url: "controller/ajax/getSupervisionDays.php",
        type: "POST",
        dataType: "json",
        success: function (data) {
            if (data) {
                const events = data.flatMap(event => generateWeeklyEvents(event));
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
            title: `${event.nameSchool} - ${event.nameZone} - ${event.nameArea}: ${event.name}`,
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
    initCalendar();

    getSchools();
    getSupervisor();

    $("#eventForm").on("submit", function (e) {
        e.preventDefault();
        submitEventForm();
    });

    $.ajax({
        url: "controller/ajax/getPlans.php",
        type: "POST",
        dataType: "json",
        success: function (data) {
            if (data) {
                const events = data.map(item => ({
                    id: item.idPlan,
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

    $("#supervisionEvent").on("submit", function (e) {
        e.preventDefault();
        submitSupervisionEventForm();
    });

    $("#school, #schoolSupervision").on("change", function () {
        const idSchool = $(this).val();
        handleSchoolChange(idSchool, $(this).attr("id"));
    });

    $("#zone, #zoneSupervision").on("change", function () {
        const idZone = $(this).val();
        handleZoneChange(idZone, $(this).attr("id"));
    });

    $("#openModalBtn").on("click", function () {
        $("#eventModal").modal("show");
    });

    initDataTable();
});

function initCalendar() {
    const calendarEl = document.getElementById("calendar");
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        height: 600,
        contentHeight: 300,
        aspectRatio: 5,
        hiddenDays: [0, 6],
        dateClick: function (info) {
            showModalForDate(info.dateStr);
        },
        eventContent: function (arg) {
            return createEventContent(arg);
        }
    });
    calendar.render();
    getSupervisionDays(calendar);
}

function showModalForDate(dateStr) {
    $("#eventDate").val(dateStr);
    $("#eventModal").modal("show");
    resetEventFormFields();
}

function resetEventFormFields() {
    $("#plan, #school, #zone, #area, #supervisor").val("");
    $("#zone, #area").prop("disabled", true);
    $("#eventDate").prop("readonly", true);
}

function createEventContent(arg) {
    const eventEl = document.createElement("div");
    eventEl.className = "custom-event";

    const buttonEl = document.createElement("div");
    buttonEl.className = "event-button col-11";
    buttonEl.textContent = arg.event.title;
    buttonEl.onclick = function () {
        getPlan(arg.event.id);
    };

    eventEl.appendChild(buttonEl);

    if (arg.event.extendedProps.deletable !== false) {
        const deleteButtonEl = document.createElement("button");
        deleteButtonEl.className = "delete-button col-3";
        deleteButtonEl.innerHTML = "&times;";
        deleteButtonEl.onclick = function () {
            deleteEvent(arg.event);
        };
        eventEl.appendChild(deleteButtonEl);
    }

    return { domNodes: [eventEl] };
}

function deleteEvent(event) {
    if (confirm("¿Estás seguro de que deseas eliminar este evento?")) {
        event.remove();
        $.ajax({
            url: "controller/ajax/deletePlans.php",
            type: "POST",
            data: { idPlan: event.id },
            success: function () {
                alert("Evento eliminado correctamente.");
            },
            error: function (xhr, status, error) {
                console.error("Error al eliminar el evento:", error);
            }
        });
    }
}

function submitEventForm() {
    const school = $("#school").val();
    const plan = $("#plan").val();
    const zone = $("#zone").val();
    const area = $("#area").val();
    const supervisor = $("#supervisor").val();
    const eventDate = $("#eventDate").val();

    if (!school || !zone || !area || !supervisor) {
        alert("Todos los campos son obligatorios.");
    } else {
        $.ajax({
            url: "controller/ajax/addPlans.php",
            type: "POST",
            data: {
                plan,
                school,
                zone,
                area,
                supervisor,
                eventDate
            },
            dataType: "json",
            success: function (data) {
                const existingEvent = calendar.getEventById(data.idPlan);
                if (existingEvent) {
                    existingEvent.remove();
                }
                calendar.addEvent({
                    id: data.idPlan,
                    title: `${data.nameSchool} - ${data.nameZone} - ${data.nameArea}`,
                    start: data.datePlan
                });
                $("#eventModal").modal("hide");
            },
            error: function (xhr, status, error) {
                console.error("Error al añadir el evento:", error);
            }
        });
    }
}

function submitSupervisionEventForm() {
    const school = $("#schoolSupervision").val();
    const zone = $("#zoneSupervision").val();
    const area = $("#areaSupervision").val();
    const dia = $("#diaSupervision").val();
    const supervisor = $("#supervisorSupervision").val();

    if (!school || !zone || !area || !dia || !supervisor) {
        alert("Todos los campos son obligatorios.");
    } else {
        $.ajax({
            url: "controller/ajax/addDaySupervision.php",
            type: "POST",
            data: {
                school,
                zone,
                area,
                dia,
                supervisor
            },
            dataType: "json",
            success: function () {
                $("#supervitionRoute").DataTable().ajax.reload();
                $("#eventModal").modal("hide");
            },
            error: function (xhr, status, error) {
                console.error("Error al añadir el evento:", error);
            }
        });
    }
}

function handleSchoolChange(idSchool, elementId) {
    if (idSchool) {
        getZones(idSchool, "", elementId);
    } else {
        $(`#${elementId.replace("school", "zone")}, #${elementId.replace("school", "area")}`)
            .prop("disabled", true)
            .html('<option value="">Selecciona una zona</option><option value="">Selecciona un área</option>');
    }
}

function handleZoneChange(idZone, elementId) {
    if (idZone) {
        getAreas(idZone, "", elementId);
    } else {
        $(`#${elementId.replace("zone", "area")}`).prop("disabled", true);
    }
}

function getSchools() {
    $.ajax({
        url: "controller/ajax/getSchools.php",
        type: "POST",
        dataType: "json",
        success: function (data) {
            let options = '<option value="">Selecciona una escuela</option>';
            data.forEach(item => {
                options += `<option value="${item.idSchool}">${item.nameSchool}</option>`;
            });
            $("#school, #schoolSupervision").html(options);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching schools:", error);
        }
    });
}

function getSupervisor() {
    $.ajax({
        url: "controller/ajax/getUsers.php",
        type: "POST",
        dataType: "json",
        success: function (data) {
            let options = '<option value="">Selecciona un supervisor</option>';
            data.forEach(item => {
                if (item.level === 2) {
                    options += `<option value="${item.idUsers}">${item.name}</option>`;
                }
            });
            $("#supervisor, #supervisorSupervision").html(options);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching supervisors:", error);
        }
    });
}

function getAreas(idZone, idArea, elementIdPrefix) {
    $.ajax({
        url: "controller/ajax/getAreas.php",
        type: "POST",
        data: { idZone },
        dataType: "json",
        success: function (data) {
            let options = '<option value="">Selecciona un área</option>';
            data.forEach(item => {
                const selected = idArea && item.idArea === idArea ? "selected" : "";
                options += `<option value="${item.idArea}" ${selected}>${item.nameArea}</option>`;
            });
            $(`#${elementIdPrefix.replace("zone", "area")}`).prop("disabled", false).html(options);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching areas:", error);
        }
    });
}

function getZones(idSchool, idZone, elementIdPrefix) {
    $.ajax({
        url: "controller/ajax/getZones.php",
        type: "POST",
        data: { idSchool },
        dataType: "json",
        success: function (data) {
            let options = '<option value="">Selecciona una zona</option>';
            data.forEach(item => {
                const selected = idZone && item.idZone === idZone ? "selected" : "";
                options += `<option value="${item.idZone}" ${selected}>${item.nameZone}</option>`;
            });
            $(`#${elementIdPrefix.replace("school", "zone")}`).prop("disabled", false).html(options);
            $(`#${elementIdPrefix.replace("school", "area")}`).prop("disabled", true).html('<option value="">Selecciona un área</option>');
        },
        error: function (xhr, status, error) {
            console.error("Error fetching zones:", error);
        }
    });
}

function getPlan(plan) {
    $("#eventModal").modal("show");
    const school = $("#school");
    const zone = $("#zone");
    const area = $("#area");
    const eventDate = $("#eventDate");
    const supervisor = $("#supervisor");

    $("#plan").val(plan);
    school.val("");
    zone.val("").prop("disabled", true);
    area.val("").prop("disabled", true);

    $.ajax({
        url: "controller/ajax/getPlans.php",
        type: "POST",
        data: { plan },
        dataType: "json",
        success: function (data) {
            setTimeout(() => {
                school.val(data.idSchool);
                getZones(data.idSchool, data.idZone, "school");
                setTimeout(() => {
                    getAreas(data.idZone, data.idArea, "zone");
                }, 100);
                supervisor.val(data.idSupervisor);
                eventDate.val(data.datePlan).prop("readonly", false);
            }, 100);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching plan:", error);
        }
    });
}

function initDataTable() {
    $("#supervitionRoute").DataTable({
        ajax: {
            url: "controller/ajax/getSupervisionDays.php",
            type: "POST",
            dataSrc: ""
        },
        columns: [
            { 
                data: null,
                render: function (data) {
                    return `${data.nameSchool} - ${data.nameZone} - ${data.nameArea}`;
                }
            },
            {
                data: "name"
            },
            {
                data: "day",
                render: function (data) {
                    return numberToDay(data);
                }
            },
            {
                data: null,
                render: function (data) {
                    return `<button class="btn btn-danger btn-sm" onclick="deletePlan(${data.idSupervisionDays})">&times;</button>`;
                }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });
}

$(".eventDailyModal").on("click", function () {
    $("#eventDailyModal").modal("show");
});

function numberToDay(number) {
    const days = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    return days[number];
}

function deletePlan(idSupervisionDays) {
    if (confirm("¿Estás seguro de que deseas eliminar este plan de supervisión?")) {
        $.ajax({
            url: "controller/ajax/deleteSupervisionDay.php",
            type: "POST",
            data: { deleteSupervisionDays: idSupervisionDays },
            success: function () {
                alert("Plan de supervisión eliminado correctamente.");
                $("#supervitionRoute").DataTable().ajax.reload();
            },
            error: function (xhr, status, error) {
                console.error("Error al eliminar el plan de supervisión:", error);
                alert("Error al eliminar el plan de supervisión.");
            }
        });
    }
}