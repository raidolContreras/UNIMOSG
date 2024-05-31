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
    getSchools();
    getSupervisor();
    
    var calendarEl = document.getElementById("calendar");
    var calendar = new FullCalendar.Calendar(calendarEl, {
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
            let eventTitle = arg.event.title;
            let eventEl = document.createElement("div");
            eventEl.className = "custom-event";

            let buttonEl = document.createElement("div");
            buttonEl.className = "event-button col-11";
            buttonEl.textContent = eventTitle;
            buttonEl.onclick = function () {
                getPlan(arg.event.id);
            };

            eventEl.appendChild(buttonEl);

            if (arg.event.extendedProps.deletable !== false) {
                let deleteButtonEl = document.createElement("button");
                deleteButtonEl.className = "delete-button col-3";
                deleteButtonEl.innerHTML = "&times;";
                deleteButtonEl.onclick = function () {
                    if (confirm("¿Estás seguro de que deseas eliminar este evento?")) {
                        arg.event.remove();
                        $.ajax({
                            url: "controller/ajax/deletePlans.php",
                            type: "POST",
                            data: { idPlan: arg.event.id },
                            success: function (response) {
                                alert("Evento eliminado correctamente.");
                            },
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

  // Handle form submission
  $("#eventForm").on("submit", function (e) {
	e.preventDefault();

	var school = $("#school").val();
	var plan = $("#plan").val();
	var zone = $("#zone").val();
	var area = $("#area").val();
	var supervisor = $("#supervisor").val();
	var eventDate = $("#eventDate").val();

	if (school == "" || zone == "" || area == "" || supervisor == "") {
	  alert("Todos los campos son obligatorios.");
	} else {
	  $.ajax({
		url: "controller/ajax/addPlans.php",
		type: "POST",
		data: {
		  plan: plan,
		  school: school,
		  zone: zone,
		  area: area,
		  supervisor: supervisor,
		  eventDate: eventDate,
		},
		dataType: "json",
		success: function (data) {
		  // Eliminar el evento existente si es necesario
		  var existingEvent = calendar.getEventById(data.idPlan);
		  if (existingEvent) {
			existingEvent.remove();
		  }
		  // Crear un nuevo evento
		  calendar.addEvent({
			id: data.idPlan,
			title: `${data.nameSchool} - ${data.nameZone} - ${data.nameArea}`,
			start: data.datePlan,
		  });
		  $("#eventModal").modal("hide");
		},
		error: function (xhr, status, error) {
		  console.error("Error al añadir el evento:", error);
		},
	  });
	}
  });

  // Load initial events
  $.ajax({
	url: "controller/ajax/getPlans.php",
	type: "POST",
	dataType: "json",
	success: function (data) {
	  var events = [];
	  for (var i = 0; i < data.length; i++) {
		events.push({
		  id: data[i].idPlan,
		  title: `${data[i].nameSchool} - ${data[i].nameZone} - ${data[i].nameArea}`,
		  start: data[i].datePlan,
		});
	  }
	  calendar.addEventSource(events);
	},
  });
});

// Handle form submission
$("#supervisionEvent").on("submit", function (e) {
  e.preventDefault();

  var school = $("#schoolSupervision").val();
  var zone = $("#zoneSupervision").val();
  var area = $("#areaSupervision").val();
  var dia = $("#diaSupervision").val();
  var supervisor = $("#supervisorSupervision").val();

  if (
	school == "" ||
	zone == "" ||
	area == "" ||
	dia == "" ||
	supervisor == ""
  ) {
	alert("Todos los campos son obligatorios.");
  } else {
	$.ajax({
	  url: "controller/ajax/addDaySupervision.php",
	  type: "POST",
	  data: {
		school: school,
		zone: zone,
		area: area,
		dia: dia,
		supervisor: supervisor,
	  },
	  dataType: "json",
	  success: function (data) {
		// Eliminar el evento existente si es necesario
		var existingEvent = calendar.getEventById(data.idPlan);
		if (existingEvent) {
		  existingEvent.remove();
		}
		// Crear un nuevo evento
		calendar.addEvent({
		  id: data.idPlan,
		  title: `${data.nameSchool} - ${data.nameZone} - ${data.nameArea}`,
		  start: data.datePlan,
		});
		$("#eventModal").modal("hide");
	  },
	  error: function (xhr, status, error) {
		console.error("Error al añadir el evento:", error);
	  },
	});
  }
});

$("#school").on("change", function () {
  var idSchool = $("#school").val();
  if (idSchool != "") {
	getZones(idSchool, "");
  } else {
	$("#zone").prop("disabled", true);
	var area = '<option value="">Selecciona un área</option>';
	$("#area").html(area);
	$("#area").prop("disabled", true);
  }
});

$("#schoolSupervision").on("change", function () {
  var idSchool = $("#schoolSupervision").val();
  if (idSchool != "") {
	getZones(idSchool, "");
  } else {
	$("#zoneSupervision").prop("disabled", true);
	var area = '<option value="">Selecciona un área</option>';
	$("#areaSupervision").html(area);
	$("#areaSupervision").prop("disabled", true);
  }
});

$("#zone").on("change", function () {
  var idZone = $("#zone").val();
  if (idZone != "") {
	getAreas(idZone, "");
  } else {
	$("#area").prop("disabled", true);
  }
});

$("#zoneSupervision").on("change", function () {
  var idZone = $("#zoneSupervision").val();
  if (idZone != "") {
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
	  var html = '<option value="">Selecciona una escuela</option>';
	  for (var i = 0; i < data.length; i++) {
		html +=
		  '<option value="' +
		  data[i].idSchool +
		  '">' +
		  data[i].nameSchool +
		  "</option>";
	  }
	  $("#school").html(html);
	  $("#schoolSupervision").html(html);
	},
  });
}

function getSupervisor() {
  $.ajax({
	url: "controller/ajax/getUsers.php",
	type: "POST",
	dataType: "json",
	success: function (data) {
	  var html = '<option value="">Selecciona un supervisor</option>';
	  for (var i = 0; i < data.length; i++) {
		if (data[i].level == 2) {
		  html +=
			'<option value="' +
			data[i].idUsers +
			'">' +
			data[i].name +
			"</option>";
		}
	  }
	  $("#supervisor").html(html);
	  $("#supervisorSupervision").html(html);
	},
  });
}

function getAreas(idZone, idArea) {
  $.ajax({
	url: "controller/ajax/getAreas.php",
	type: "POST",
	data: {
	  idZone: idZone,
	},
	dataType: "json",
	success: function (data) {
	  var html = '<option value="">Selecciona un área</option>';
	  for (var i = 0; i < data.length; i++) {
		var select = idArea != "" && data[i].idArea == idArea ? "selected" : "";
		html +=
		  "<option " +
		  select +
		  ' value="' +
		  data[i].idArea +
		  '">' +
		  data[i].nameArea +
		  "</option>";
	  }

	  $("#area").prop("disabled", false); // Quitar el disabled
	  $("#area").html(html);
	  $("#areaSupervision").prop("disabled", false); // Quitar el disabled
	  $("#areaSupervision").html(html);
	},
  });
}

function getZones(idSchool, idZone) {
  $.ajax({
	url: "controller/ajax/getZones.php",
	type: "POST",
	data: {
	  idSchool: idSchool,
	},
	dataType: "json",
	success: function (data) {
	  var html = '<option value="">Selecciona una zona</option>';
	  for (var i = 0; i < data.length; i++) {
		var select = idZone != "" && data[i].idZone == idZone ? "selected" : "";
		html +=
		  "<option " +
		  select +
		  ' value="' +
		  data[i].idZone +
		  '">' +
		  data[i].nameZone +
		  "</option>";
	  }

	  $("#zone").prop("disabled", false); // Quitar el disabled
	  $("#zone").html(html);
	  $("#zoneSupervision").prop("disabled", false); // Quitar el disabled
	  $("#zoneSupervision").html(html);

	  var area = '<option value="">Selecciona un área</option>';
	  $("#area").html(area);
	  $("#area").prop("disabled", true);
	  $("#areaSupervision").html(area);
	  $("#areaSupervision").prop("disabled", true);
	},
  });
}

function closeModal(modal) {
  $("#" + modal).modal("hide");
}

function getPlan(plan) {
  $("#eventModal").modal("show");
  var school = $("#school");
  var zone = $("#zone");
  var area = $("#area");
  var eventDate = $("#eventDate");
  var supervisor = $("#supervisor");
  $("#eventModal").modal("show");
  $("#plan").val(plan);
  school.val("");
  zone.val("");
  area.val("");
  zone.prop("disabled", true);
  area.prop("disabled", true);

  $.ajax({
	url: "controller/ajax/getPlans.php",
	type: "POST",
	data: {
	  plan: plan,
	},
	dataType: "json",
	success: function (data) {
	  setTimeout(() => {
		school.val(data.idSchool);
		school.val(data.idSchool);

		getZones(data.idSchool, data.idZone);

		setTimeout(() => {
		  getAreas(data.idZone, data.idArea);
		}, "100");

		supervisor.val(data.idSupervisor);
		eventDate.val(data.datePlan);

		eventDate.prop("readonly", false);
	  }, "100");
	},
  });
}

$(".eventDailyModal").on("click", function () {
  $("#eventDailyModal").modal("show");
});

$(document).ready(function () {
  $("#supervitionRoute").DataTable({
	language: {
	  url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
	},
  });
});
