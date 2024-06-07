function getSupervisionDays(calendar) {
    $.ajax({
        url: "controller/ajax/getSupervisionDays.php",
        type: "POST",
        dataType: "json",
        success: function (data) {
            const events = data.flatMap(generateWeeklyEvents);
            calendar.addEventSource(events);
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
    let diff = (dayOfWeek - day + 7) % 7;
    if (diff === 0 && dayOfWeek !== day) {
        diff += 7;
    }
    date.setDate(date.getDate() + diff);
    return date;
}

$(document).ready(function () {
    getSchools();
    getSupervisor();

    const calendarEl = document.getElementById("calendar");
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        height: 600,
        contentHeight: 300,
        aspectRatio: 5,
        hiddenDays: [0, 6],
        dateClick: function (info) {
            $("#eventDate").val(info.dateStr);
            $("#eventModal").modal("show");
            $("#plan").val("");
            $("#school").val("");
            $("#zone").val("");
            $("#area").val("");
            $("#zone").prop("disabled", true);
            $("#area").prop("disabled", true);
            $("#eventDate").prop("readonly", true);
            $("#supervisor").val("");
        },
        eventContent: function (arg) {
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
                    if (confirm("¿Estás seguro de que deseas eliminar este evento?")) {
                        arg.event.remove();
                        $.ajax({
                            url: "controller/ajax/deletePlans.php",
                            type: "POST",
                            data: { idPlan: arg.event.id },
                            success: function () {
                                alert("Evento eliminado correctamente.");
                            },
                            error: function (xhr, status, error) {
                                console.error("Error al eliminar el evento:", error);
                            }
                        });
                    }
                };
                eventEl.appendChild(deleteButtonEl);
            }

            return { domNodes: [eventEl] };
        }
    });

    calendar.render();
    getSupervisionDays(calendar);

    $("#eventForm").on("submit", function (e) {
        e.preventDefault();

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
    });

    $.ajax({
        url: "controller/ajax/getPlans.php",
        type: "POST",
        dataType: "json",
        success: function (data) {
            const events = data.map(item => ({
                id: item.idPlan,
                title: `${item.nameSchool} - ${item.nameZone} - ${item.nameArea}`,
                start: item.datePlan
            }));
            calendar.addEventSource(events);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching plans:", error);
        }
    });
});

$("#supervisionEvent").on("submit", function (e) {
    e.preventDefault();

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
});

$("#school").on("change", function () {
    const idSchool = $(this).val();
    if (idSchool) {
        getZones(idSchool, "");
    } else {
        $("#zone").prop("disabled", true).html('<option value="">Selecciona una zona</option>');
        $("#area").prop("disabled", true).html('<option value="">Selecciona un área</option>');
    }
});

$("#schoolSupervision").on("change", function () {
    const idSchool = $(this).val();
    if (idSchool) {
        getZones(idSchool, "");
    } else {
        $("#zoneSupervision").prop("disabled", true).html('<option value="">Selecciona una zona</option>');
        $("#areaSupervision").prop("disabled", true).html('<option value="">Selecciona un área</option>');
    }
});

$("#zone").on("change", function () {
    const idZone = $(this).val();
    if (idZone) {
        getAreas(idZone, "");
    } else {
        $("#area").prop("disabled", true);
    }
});

$("#zoneSupervision").on("change", function () {
    const idZone = $(this).val();
    if (idZone) {
        getAreas(idZone, "");
    } else {
        $("#areaSupervision").prop("disabled", true);
    }
});

$("#openModalBtn").on("click", function () {
    $("#eventModal").modal("show");
});

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

function getAreas(idZone, idArea) {
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
            $("#area, #areaSupervision").prop("disabled", false).html(options);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching areas:", error);
        }
    });
}

function getZones(idSchool, idZone) {
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
            $("#zone, #zoneSupervision").prop("disabled", false).html(options);

            $("#area, #areaSupervision").prop("disabled", true).html('<option value="">Selecciona un área</option>');
        },
        error: function (xhr, status, error) {
            console.error("Error fetching zones:", error);
        }
    });
}

function closeModal(modal) {
    $(`#${modal}`).modal("hide");
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
                getZones(data.idSchool, data.idZone);
                setTimeout(() => {
                    getAreas(data.idZone, data.idArea);
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

$(".eventDailyModal").on("click", function () {
    $("#eventDailyModal").modal("show");
});

$(document).ready(function () {
    $("#supervitionRoute").DataTable({
        ajax: {
            url: "controller/ajax/getSupervisionDays.php",
            type: "POST",
            dataSrc: ""
        },
        columns: [
            { data: null,
				render: function (data, type, row) {
                    return `${data.nameSchool} - ${data.nameZone} - ${data.nameArea}`;
                }
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
            success: function (response) {
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