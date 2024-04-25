$(document).ready(function() {
	var tablaEscuelas = $('#tablaEscuelas').DataTable({
	  paging: false
	});
  
	$('#tablaEscuelas tbody').on('click', 'tr', function() {
	  var fila = tablaEscuelas.row(this);
	  var datosFila = fila.data();
  
	  // Verificar si ya se ha creado un detalle para esta fila
	  if (fila.child.isShown()) {
		// Si se muestra, ocultarlo
		fila.child.hide();
		$(this).removeClass('details');
	  } else {
		// Si no se muestra, crear el detalle
		fila.child(formatoDetalle(datosFila)).show();
		$(this).addClass('details');
	  }
	});
  });
  
  function formatoDetalle(data) {
	// Generar el HTML para el detalle (información de zonas)
	var html = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
	html += '<tr><td><b>Zonas:</b></td></tr>';
	// Aquí puedes agregar un bucle para cada zona y generar una fila de detalle para cada una
	html += '<tr><td>Zona 1</td></tr>';
	html += '<tr><td>Zona 2</td></tr>';
	// Puedes agregar más zonas aquí si es necesario
	html += '</table>';
	return html;
  }
  