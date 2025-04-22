function updateMinEndDate() {
    const startDate = document.getElementById('fecha_inicio').value;
    const endDateInput = document.getElementById('fecha_fin');
    endDateInput.min = startDate;
    
    // Si la fecha fin es menor que la inicio, la actualizamos
    if (endDateInput.value && endDateInput.value < startDate) {
        endDateInput.value = startDate;
    }
}
// Ejecutar al cargar la página
updateMinEndDate();

$(document).ready(function () {
    let dotsInterval;
  
    /**
     * Función que centraliza toda la lógica de generación y descarga
     * del reporte de incidentes.
     *
     * @param {string} trigger — Origen de la llamada: 'submit' o 'completo'
     */
    function generarReporte(trigger) {
      const form = $('#reportForm');
      const fechaInicio = $('#fecha_inicio').val();
      const fechaFin    = $('#fecha_fin').val() || fechaInicio;
  
      // Validación de rango de fechas
      if (fechaFin < fechaInicio) {
        alert('La fecha final no puede ser menor a la fecha inicial');
        return;
      }
  
      // Elegir el botón que disparó la acción para mostrar spinner
      let btn;
      if (trigger === 'completo') {
        btn = $('#btnGenerarReporteCompleto');
      } else {
        btn = form.find('button[type="submit"]');
      }
  
      // Deshabilitar y mostrar spinner
      btn.prop('disabled', true)
         .html('<i class="fas fa-spinner fa-spin me-1"></i><span>Generando</span><span id="dots">.</span>');
  
      // Animación de puntos suspensivos
      let dotCount = 1;
      dotsInterval = setInterval(() => {
        dotCount = (dotCount % 3) + 1;
        $('#dots').text('.'.repeat(dotCount));
      }, 500);
  
      // AJAX que recibe blob para el Excel
      $.ajax({
        url: 'controller/forms.ajax.php',
        type: 'POST',
        data: {
          action: 'generateIncidentReport',
          fecha_inicio: fechaInicio,
          fecha_fin: fechaFin,
          tipo: trigger  // opcional: si tu backend necesita saber quién llamó
        },
        xhrFields: { responseType: 'blob' },
        success: function (data, status, xhr) {
          // Limpiar animación y restaurar texto original
          clearInterval(dotsInterval);
          btn.prop('disabled', false)
             .html('<i class="fas fa-file-excel me-1"></i>Exportar reporte');
  
          // Extraer nombre de archivo de la cabecera
          let filename = 'reporte_incidentes.xlsx';
          const disp = xhr.getResponseHeader('Content-Disposition');
          if (disp && disp.indexOf('filename=') !== -1) {
            filename = disp.split('filename=')[1].trim().replace(/["']/g, '');
          }
  
          // Crear URL y disparar descarga
          const blob = new Blob([data], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          });
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = filename;
          document.body.appendChild(link);
          link.click();
  
          // Limpieza
          window.URL.revokeObjectURL(link.href);
          link.remove();
        },
        error: function (xhr, status, error) {
          clearInterval(dotsInterval);
          btn.prop('disabled', false)
             .html('<i class="fas fa-file-excel me-1"></i>Exportar reporte');
          alert('Error al generar el reporte: ' + (xhr.responseText || xhr.statusText));
        }
      });
    }
  
    // Bind al submit del formulario
    $('#reportForm').on('submit', function (e) {
      e.preventDefault();
      generarReporte('submit');
    });
  
    // Bind al clic del botón completo
    $('#btnGenerarReporteCompleto').on('click', function (e) {
      e.preventDefault();
      generarReporte('completo');
    });
  });
  