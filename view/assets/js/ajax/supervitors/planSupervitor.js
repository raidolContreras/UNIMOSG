$(document).ready(function () {
  // Centralized configuration
  const CONFIG = {
    urls: {
      searchEdificer: "controller/forms.ajax.php",
      searchFloor: "controller/forms.ajax.php",
      getAreasForZone: "controller/forms.ajax.php",
      getSupervitionDays: "controller/forms.ajax.php",
      getSupervitionAreas: "controller/forms.ajax.php",
      addSupervitionAreas: "controller/forms.ajax.php",
      deleteSupervitionArea: "controller/forms.ajax.php", // Ajustar esta URL según tu backend
    },
    zonasPredefinidas: [
      { id: "Norte", name: "Norte" },
      { id: "Sur", name: "Sur" },
      { id: "Este", name: "Este" },
      { id: "Oeste", name: "Oeste" },
    ],
  };

  /**
   * Centralized AJAX utility function with error handling
   */
  function ajaxRequest(url, data, successCallback, errorCallback = null) {
    $.ajax({
      url: url,
      type: "POST",
      data: data,
      dataType: "json",
      success: successCallback,
      error: function (xhr, status, error) {
        console.error(`Error: ${status} - ${error}`);
        if (errorCallback) errorCallback(error);
      },
    });
  }

  /**
   * Enhanced dropdown update function with optional callback
   */
  function updateDropdown(
    $dropdown,
    placeholder,
    options,
    disabled = false,
    callback = null
  ) {
    $dropdown
      .empty()
      .append(`<option value="" disabled selected>${placeholder}</option>`)
      .append(
        options
          .map(
            (option) => `<option value="${option.id}">${option.name}</option>`
          )
          .join("")
      )
      .prop("disabled", disabled);

    if (callback) callback();
  }

  /**
   * Cascade dropdown setup for events and routes
   */
  function setupCascadeDropdowns(prefix) {
    const selectors = {
      plantel: $(`#${prefix}Plantel`),
      edificio: $(`#${prefix}Edificio`),
      piso: $(`#${prefix}Piso`),
      zona: $(`#${prefix}Zona`),
      area: $(`#${prefix}Area`),
    };

    selectors.plantel.on("change", function () {
      const selectedPlantel = $(this).val();
      ajaxRequest(
        CONFIG.urls.searchEdificer,
        { action: "searchEdificer", idSchool: selectedPlantel },
        function (response) {
          const edificios = response.edificers.map((edificio) => ({
            id: edificio.idEdificers,
            name: edificio.nameEdificer,
          }));
          updateDropdown(
            selectors.edificio,
            "Seleccione un edificio",
            edificios,
            false
          );
          updateDropdown(selectors.piso, "Seleccione un piso", [], true);
          updateDropdown(selectors.zona, "Seleccione una zona", [], true);
          updateDropdown(selectors.area, "Seleccione una área", [], true);
        }
      );
    });

    selectors.edificio.on("change", function () {
      const selectedEdificio = $(this).val();
      ajaxRequest(
        CONFIG.urls.searchFloor,
        { action: "searchFloor", idEdificers: selectedEdificio },
        function (response) {
          const pisos = response.floors.map((piso) => ({
            id: piso.idFloor,
            name: piso.nameFloor,
          }));
          updateDropdown(selectors.piso, "Seleccione un piso", pisos, false);
          updateDropdown(selectors.zona, "Seleccione una zona", [], true);
          updateDropdown(selectors.area, "Seleccione una área", [], true);
        }
      );
    });

    selectors.piso.on("change", function () {
      const selectedPiso = $(this).val();
      updateDropdown(
        selectors.zona,
        "Seleccione una zona",
        CONFIG.zonasPredefinidas,
        false,
        function () {
          selectors.zona.off("change").on("change", function () {
            const selectedZona = $(this).val();
            selectors.area.prop("disabled", false);

            ajaxRequest(
              CONFIG.urls.getAreasForZone,
              {
                action: "getAreasForZone",
                idFloor: selectedPiso,
                zone: selectedZona,
              },
              function (response) {
                const areas = response.areas.map((area) => ({
                  id: area.idArea,
                  name: area.nameArea,
                }));
                updateDropdown(
                  selectors.area,
                  "Seleccione una área",
                  areas,
                  false
                );
              }
            );
          });
        }
      );
    });
  }

  // Initialize cascade dropdowns for both event and route modals
  setupCascadeDropdowns("event");
  setupCascadeDropdowns("recorrido");
  setupCascadeDropdowns("editar");

  // Initialize calendar on DOM ready
  initializeCalendar();

  $("#recorridoForm").on("submit", function (e) {
    e.preventDefault();
    var formData = {
      action: "registerRoute",
      recorridoSupervisor: $("#recorridoSupervisor").val(),
      recorridoPlantel: $("#recorridoPlantel").val(),
      recorridoEdificio: $("#recorridoEdificio").val(),
      recorridoPiso: $("#recorridoPiso").val(),
      recorridoZona: $("#recorridoZona").val(),
      recorridoArea: $("#recorridoArea").val(),
      recorridoDia: $("#recorridoDia").val(),
      recorridoHora: $("#recorridoHora").val(),
    };

    $.ajax({
      url: "controller/forms.ajax.php",
      method: "POST",
      data: formData,
      success: function (response) {
        if (response == "ok") {
          initializeCalendar();
          $("#recorridosTable").DataTable().ajax.reload();
        } else {
          alert("Error al registrar el recorrido");
        }
      },
    });
  });

  $("#recorridosTable").DataTable({
    ajax: {
      url: "controller/forms.ajax.php",
      type: "POST",
      data: { action: "getSupervitionDays" },
      dataSrc: ""
    },
    columns: [
      { 
        data: null,
        title: '#',
        render: function (data, type, row, meta) {
          return meta.row + 1; // Número de fila
        },
        className: "text-center"
      },
      { 
        data: "name", 
        title: 'Nombre del Supervisor',
        className: "text-left"
      },
      { 
        data: "nameSchool", 
        title: 'Plantel' 
      },
      { 
        data: "nameEdificer", 
        title: 'Edificio' 
      },
      { 
        data: "nameFloor", 
        title: 'Piso' 
      },
      { 
        data: "zone", 
        title: 'Zona' 
      },
      { 
        data: "nameArea", 
        title: 'Área' 
      },
      { 
        data: "day", 
        title: 'Día',
        render: function (data) {
          const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
          return days[data] || 'Desconocido'; // Convertir día numérico a texto
        },
        className: "text-center"
      },
      { 
        data: "supervisionTime", 
        title: 'Hora de Supervisión',
        render: function (data) {
          return data ? data.slice(0, 5) : '-'; // Mostrar solo HH:mm
        },
        className: "text-center"
      },
      {
        data: null,
        title: 'Acciones',
        render: function (data, type, row) {
          return `
            <button class="btn btn-danger btn-sm btn-delete-supervision" data-id="${row.idSupervisionDays}">
                <i class="fal fa-trash-alt"></i>
            </button>`;
        },
        className: "text-center"
      }      
    ],
    responsive: true, // Activar diseño responsive
    autoWidth: false, // Evitar que las columnas se autoajusten
    pageLength: 10, // Cantidad de filas por página
    lengthMenu: [5, 10, 25, 50], // Opciones de cantidad de filas por página
    order: [[0, 'asc']] // Ordenar por la primera columna de forma ascendente
  });

  // Calendar initialization with enhanced configuration
  function initializeCalendar() {
    const calendarEl = document.getElementById("calendar");
    const calendar = new FullCalendar.Calendar(calendarEl, {
      locale: "es",
      initialView: "timeGridWeek",
      slotMinTime: "07:00:00",
      slotMaxTime: "19:00:00",
      selectable: true,
      // Se deshabilita la edición para eliminar arrastrar y soltar
      editable: false,
      slotDuration: "00:30:00",
      height: "auto",
      allDaySlot: false,
      headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
      },
      buttonText: {
        today: "Hoy",
        month: "Mes",
        week: "Semana",
        day: "Día",
        list: "Agenda",
      },
	  slotLabelFormat: {
		hour: '2-digit',
		minute: '2-digit',
		hour12: false
	  },
	  slotLabelDidMount: function(info) {
		// Aquí puedes agregar clases, modificar el DOM, etc.
		// Por ejemplo, agregar una clase personalizada:
		info.el.classList.add('fc-custom-time-label');
	  },
      hiddenDays: [0],
      validRange: (nowDate) => ({ start: nowDate }),
      events: function (fetchInfo, successCallback, failureCallback) {
        // Haremos dos peticiones AJAX: una para eventos por día (repetitivos)
        // y otra para eventos de área (fechas específicas).

        let dayEvents = [];
        let areaEvents = [];

        // Primero obtenemos los eventos que se repiten por día de la semana
        ajaxRequest(
          CONFIG.urls.getSupervitionDays,
          { action: "getSupervitionDays" },
          function (responseDays) {
            const startDate = new Date(fetchInfo.start);
            const endDate = new Date(fetchInfo.end);

            responseDays.forEach((item) => {
              let current = new Date(startDate);
              while (current < endDate) {
                if (current.getDay() === item.day) {
                  const dateStr = current.toISOString().split("T")[0];
                  dayEvents.push({
                    id: `day-${item.idSupervisionDays}`,
                    title: `${item.nameArea}`,
                    start: `${dateStr}T${item.supervisionTime}`,
                    backgroundColor: "#236823",
                    extendedProps: {
                      repeating: item.repeating == 1,
                      supervisor: item.name,
                      area: item.nameArea,
                      school: item.nameSchool,
                      building: item.nameEdificer,
                      floor: item.nameFloor,
                      zone: item.zone,
                      time: item.supervisionTime,
                    },
                  });
                }
                current.setDate(current.getDate() + 1);
              }
            });
            
            ajaxRequest(
              CONFIG.urls.getSupervitionAreas,
              { action: "getSupervitionAreas" },
              function (responseAreas) {
                areaEvents = responseAreas.map((areaEvent) => {
                  return {
                    id: `area-${areaEvent.idSupervisionAreas}`,
                    title: `${areaEvent.title + " - " + areaEvent.nameArea}`,
                    start: `${areaEvent.day}T${areaEvent.time}`,
                    backgroundColor: "#69938a",
                    extendedProps: {
                      repeating: false,
                      supervisor: areaEvent.name,
                      area: areaEvent.nameArea,
                      school: areaEvent.nameSchool,
                      building: areaEvent.nameEdificer,
                      floor: areaEvent.nameFloor,
                      zone: areaEvent.zone,
                      time: areaEvent.time,
                    },
                  };
                });

                // Combinar ambos arrays y devolverlos al calendario
                const allEvents = [...dayEvents, ...areaEvents];
                successCallback(allEvents);
              },
              failureCallback
            );
          },
          failureCallback
        );
      },
      eventMouseEnter: function (info) {
        var event = info.event;
        var props = event.extendedProps;
        // Verificamos si repeating = true (repeating: item.repeating == 1)
          var tooltipContent = `
						<div>
              <strong>Área:</strong> ${props.area}<br>
							<strong>Supervisor:</strong> ${props.supervisor}<br>
							<strong>Escuela:</strong> ${props.school}<br>
							<strong>Edificio:</strong> ${props.building}<br>
							<strong>Piso:</strong> ${props.floor}<br>
							<strong>Zona:</strong> ${props.zone}<br>
							<strong>Hora:</strong> ${props.time}
						</div>
					`;

          $(info.el)
            .tooltip({
              title: tooltipContent,
              html: true,
              container: "body",
              trigger: "hover",
            })
            .tooltip("show");
      },
      eventMouseLeave: function (info) {
        $(info.el).tooltip("hide");
      },
      // Estructura del evento en pantalla
      eventContent: function (arg) {
        const { event } = arg;
        const el = document.createElement("div");
        el.classList.add("fc-event-content-wrapper");

        const titleEl = document.createElement("div");
        titleEl.classList.add("fc-event-title");
        titleEl.textContent = event.title;

        el.appendChild(titleEl);

        // Mostrar botón solo si el evento no es recurrente
        if (!event.extendedProps.repeating) {
          const deleteBtn = document.createElement("button");
          deleteBtn.textContent = "×";
          deleteBtn.classList.add("delete-event-btn");
          deleteBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            if (confirm("¿Desea eliminar este evento?")) {
              let id = event.id.replace("area-", "");
              $.ajax({
                url: CONFIG.urls.deleteSupervitionArea,
                type: "POST",
                data: {
                  action: "deleteSupervitionArea",
                  eventId: id,
                },
                success: function (response) {
                  if (response.trim() === "ok") {
                    event.remove();
                  } else {
                    alert("Error al eliminar el evento.");
                  }
                },
                error: function () {
                  alert("Error en la conexión con el servidor.");
                },
              });
            }
          });
          el.appendChild(deleteBtn);
        }

        return { domNodes: [el] };
      },

      select: function (info) {
        const startDate = info.start;
        const dayFormatted = startDate.toISOString().split("T")[0];
        const hours = startDate.getHours().toString().padStart(2, "0");
        const minutes = startDate.getMinutes().toString().padStart(2, "0");
        const selectedTime = `${hours}:${minutes}`;

        $("#eventTime").val(selectedTime);
        $("#eventModal").modal("show");

        $("#eventForm")
          .off("submit")
          .on("submit", function (e) {
            e.preventDefault();
            const formData = {
              action: "addSupervitionAreas",
              title: $("#eventTitle").val(),
              supervisor: $("#eventSupervisor").val(),
              plantel: $("#eventPlantel").val(),
              edificio: $("#eventEdificio").val(),
              piso: $("#eventPiso").val(),
              zona: $("#eventZona").val(),
              area: $("#eventArea").val(),
              day: dayFormatted,
              time: selectedTime,
            };

            $.ajax({
              url: CONFIG.urls.addSupervitionAreas,
              method: "POST",
              data: formData,
              success: function (response) {
                if (response.trim() === "ok") {
                  // Se agrega el evento como no recurrente (repeating: false)
                  calendar.addEvent({
                    id: new Date().getTime(), // Generar un ID temporal, ajustar según backend
                    title: formData.title,
                    start: `${dayFormatted}T${selectedTime}`,
                    extendedProps: {
                      repeating: false,
                    },
                  });
                  $("#eventModal").modal("hide");
                } else {
                  alert("Error al guardar el evento.");
                }
              },
              error: () => alert("Error en la conexión con el servidor."),
            });
          });
        calendar.unselect();
      },
      eventClick: function (info) {
        const event = info.event;
        // si el event.id empieza con "day-" es un evento repetitivo
        if (event.id.startsWith("day-")) {
          //nquitar e day- para obtener el id del evento
          const idSupervisionDays = event.id.replace("day-", "");
          editSupervision(idSupervisionDays, "day");
        } else {
          //quitar el area- para obtener el id del evento
          const idSupervisionAreas = event.id.replace("area-", "");
          editSupervision(idSupervisionAreas, "area");
        }
      },
    });

    calendar.render();
  }
  
  function setValueAndChange($element, value, delay = 100) {
    return new Promise((resolve) => {
      $element.val(value).trigger("change");
      setTimeout(resolve, delay);
    });
  }

  function editSupervision(id, type) {
    $.ajax({
      url: "controller/forms.ajax.php",
      type: "POST",
      data: { action: "getSupervision", type, id },
      dataType: "json",
    })
      .done(async function (data) {
        // Asignamos supervisor directamente, pues no depende de otro select
        $("#editarSupervisor").val(data.idSupervisor);

        // Encadenamos las asignaciones de los selects dependientes usando Promesas/await
        await setValueAndChange($("#editarPlantel"), data.idSchool);
        await setValueAndChange($("#editarEdificio"), data.idEdificers);
        await setValueAndChange($("#editarPiso"), data.idFloor);
        await setValueAndChange($("#editarZona"), data.zone);

        // Este último simplemente se asigna, si no requiere refrescar nada
        $("#editarArea").val(data.idArea);
        if (type === "day") {
          $("#editarDia")
            .removeClass("d-none")
            .prop("required", true)
            .prop("disabled", false)
            .val(data.day);
        
          $("#editarFecha")
            .addClass("d-none")
            .prop("required", false)
            .prop("disabled", true);
        
          $("#editarTitulo")
            .addClass("d-none")
            .prop("required", false)
            .prop("disabled", true); // <--- Quitar el required y deshabilitar
        
          $("#tituloEvento").addClass("d-none");
        
          $("#editarHora").val(data.supervisionTime);
        
        } else {
          $("#editarFecha")
            .removeClass("d-none")
            .prop("required", true)
            .prop("disabled", false)
            .val(data.day);
        
          $("#editarDia")
            .addClass("d-none")
            .prop("required", false)
            .prop("disabled", true);
        
          $("#editarTitulo")
            .removeClass("d-none")
            .prop("required", true)
            .prop("disabled", false) // <--- Si se requiere título aquí, marcamos como required
            .val(data.title);
        
          $("#tituloEvento").removeClass("d-none");
        
          $("#editarHora").val(data.time);
        }
        

        $("#editEventModal").modal("show");
        $("#editarForm").off("submit").on("submit", function (e) {
          e.preventDefault();
          let formData;

          if (type == 'day') {
            formData = {
              action: "editSupervision",
              type,
              id: id,
              supervisor: $("#editarSupervisor").val(),
              plantel: $("#editarPlantel").val(),
              edificio: $("#editarEdificio").val(),
              piso: $("#editarPiso").val(),
              zona: $("#editarZona").val(),
              area: $("#editarArea").val(),
              day: $("#editarDia").val(),
              time: $("#editarHora").val(),
            };

          } else {
            formData = {
              action: "editSupervision",
              type,
              id: id,
              supervisor: $("#editarSupervisor").val(),
              plantel: $("#editarPlantel").val(),
              edificio: $("#editarEdificio").val(),
              piso: $("#editarPiso").val(),
              zona: $("#editarZona").val(),
              area: $("#editarArea").val(),
              day: $("#editarFecha").val(),
              time: $("#editarHora").val(),
              title: $("#editarTitulo").val(),
            };
          }

          updateSupervition(formData);
          
        });
      })
      .fail(function (error) {
        console.error("Error al obtener supervisión:", error);
      });
  }

  function updateSupervition(formdata) {
    $.ajax({
      url: "controller/forms.ajax.php",
      type: "POST",
      data: formdata,
      success: function (response) {
        if (response == "ok") {
          alert("Supervisión actualizada correctamente");
          // actualizar fullcalendar

          initializeCalendar();
          $("#editEventModal").modal("hide");
          
          } else {
          alert("Error al actualizar la supervisión");
        }
      },
    });
  }

  $(document).on("click", ".btn-delete-supervision", function () {
    const idSupervisionDays = $(this).data("id");
    if (confirm("¿Desea eliminar este evento?")) {
      $.ajax({
        url: "controller/forms.ajax.php",
        type: "POST",
        data: { action: "deleteSupervisionDays", idSupervisionDays },
        success: function (response) {
          if (response == "ok") {
            initializeCalendar();
            $("#recorridosTable").DataTable().ajax.reload();
          } else {
            alert("Error al eliminar la supervisión");
          }
        },
      });
    }
  });

});