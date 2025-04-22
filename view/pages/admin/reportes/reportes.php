<style>
    #btnGenerarReporte {
  white-space: nowrap;
  min-width: 160px; /* Ajusta según el tamaño máximo esperado del botón */
}

#dots {
  display: inline-flex;
  width: 1.5em; /* Espacio reservado para 3 puntos */
}

</style>
<div class="p-3 mb-4 rounded">
    <h5 id="namePage" class="page-title"></h5>
</div>

<!-- Formulario de filtros -->
<form id="reportForm" class="row g-3 needs-validation" novalidate>
    <!-- Fechas -->
    <div class="col-md-6">
        <label for="fecha_inicio" class="form-label">Fecha de inicio <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required value="<?php echo date('Y-m-d'); ?>" onchange="updateMinEndDate()">
    </div>
    <div class="col-md-6">
        <label for="fecha_fin" class="form-label">Fecha de fin</label>
        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" max="<?php echo date('Y-m-d'); ?>">
        <div class="form-text">Si se deja vacío, se usará la misma fecha de inicio.</div>
    </div>

    <!-- Botones de acción -->
    <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-success" id="btnGenerarReporte">
            <i class="fas fa-file-excel me-1"></i>Exportar reporte
        </button>
        <button type="button" class="btn btn-primary" id="btnGenerarReporteCompleto">
            <i class="fas fa-file-excel me-1"></i>Exportar reporte completo
        </button>
    </div>
</form>


<script src="view/assets/js/ajax/Reports/reports.js"></script>