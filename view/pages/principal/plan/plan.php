
    <style>
        .fc-daygrid-day-frame {
            cursor: pointer !important;
        }

        .custom-event {
            display: flex;
            align-items: center;
        }
        .event-button {
            flex: 1;
            cursor: pointer;
        }
        .delete-button {
            margin-left: 5px;
            cursor: pointer;
            background: transparent;
            border: none;
            color: red;
        }
        .fc-event {
            color: white !important; /* Ensure text is white for better readability */
        }
    </style>

    <div class="card-custom">
        <div class="card-header-custom">
            <strong>Plan de superviciones</strong>
        </div>
    </div>

    <div class="mt-5">
        <button class="btn btn-secondary eventDailyModal">
            <i class="fa-duotone fa-clipboard"></i>
            Gestionar ruta de supervisión
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="eventDailyModal" tabindex="-1" role="dialog" aria-labelledby="eventDailyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title mx-auto" id="eventDailyModalLabel">Ruta de supervisión</h5>
                    <button type="button" class="btn btn-danger btn-circle" onclick="closeModal('eventDailyModal')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <form id="supervisionEvent" class="row">
                        <div class="form-group col-6">
                            <label for="schoolSupervision">Escuela</label>
                            <select name="schoolSupervision" id="schoolSupervision" class="form-select">
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="zoneSupervision">Zona</label>
                            <select name="zoneSupervision" id="zoneSupervision" class="form-select" disabled>
                                <option value="">Selecciona una zona</option>
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <label for="areaSupervision">Area</label>
                            <select name="areaSupervision" id="areaSupervision" class="form-select" disabled>
                                <option value="">Selecciona un area</option>
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <label for="supervisorSupervision">Supervisor</label>
                            <select name="supervisorSupervision" id="supervisorSupervision" class="form-select">
                                <option value="">Selecciona un supervisor</option>
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <label for="diaSupervision">Día</label>
                            <select name="diaSupervision" id="diaSupervision" class="form-select">
                                <option value="">Selecciona un día</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miercoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Enviar <i class="fa-duotone fa-paper-plane-top"></i></button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="supervitionRoute" class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Localización</th>
                                    <th scope="col">Supervisor asignado</th>
                                    <th scope="col">Días</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div id='calendar' class="p-3"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title mx-auto" id="eventModalLabel">Crear Evento</h5>
                    <button type="button" class="btn btn-danger btn-circle" onclick="closeModal('eventModal')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm" class="row">
                        <div class="form-group col-6">
                            <label for="school">Escuela</label>
                            <select name="school" id="school" class="form-select">
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="zone">Zona</label>
                            <select name="zone" id="zone" class="form-select" disabled>
                                <option value="">Selecciona una zona</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="area">Area</label>
                            <select name="area" id="area" class="form-select" disabled>
                                <option value="">Selecciona un area</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="supervisor">Supervisor</label>
                            <select name="supervisor" id="supervisor" class="form-select">
                                <option value="">Selecciona un supervisor</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="eventDate">Fecha del Evento</label>
                            <input type="date" class="form-control" id="eventDate" readonly>
                            <input type="hidden" id="plan">
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger" onclick="closeModal('eventModal')">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
<script src="view/assets/js/ajax/General/getPlans.js"></script>