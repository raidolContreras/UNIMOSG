    <script src='view/assets/node_modules/fullcalendar/index.global.js'></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          height: 600,
          contentHeight: 300,
          aspectRatio: 5,
          hiddenDays: [ 0, 6 ],
          dateClick: function(info) {
            document.getElementById('eventDate').value = info.dateStr;
            $('#eventTitle').val('');
            $('#eventDescription').val('');
            $('#eventModal').modal('show');
          }
        });
        calendar.render();
        
        document.getElementById('eventForm').addEventListener('submit', function(e) {
          e.preventDefault();
          var title = $('#eventTitle').val();
          var description = $('#eventDescription').val();
          var date = $('#eventDate').val();
          
          calendar.addEvent({
            title: title,
            start: date,
            description: description
          });
          
          $('#eventModal').modal('hide');
          $('#eventTitle').val('');
          $('#eventDescription').val('');
        });
      });
      function closeModal() {
          $('#eventModal').modal('hide');
          $('#eventTitle').val('');
          var description = $('#eventDescription').val('');
      }
    </script>

    <div class="card-custom">
        <div class="card-header-custom">
            <strong>Plan de superviciones</strong>
        </div>
    </div>

    <div class="card mt-5">
        <div id='calendar' class="p-3"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title mx-auto" id="eventModalLabel">Crear Evento</h5>
                    <button type="button" class="btn btn-danger btn-circle" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm" class="row">
                        <div class="form-group col-6">
                            <label for="school">Escuela</label>
                            <select name="school" id="school" class="form-select">
                                <option value="">Selecciona una escuela</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="zone">Zona</label>
                            <select name="zone" id="zone" class="form-select">
                                <option value="">Selecciona una zona</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="area">Area</label>
                            <select name="area" id="area" class="form-select">
                                <option value="">Selecciona un area</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="supervisor">Supervisor</label>
                            <select name="supervisor" id="supervisor" class="form-select">
                                <option value="">Selecciona una escuela</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="eventDate">Fecha del Evento</label>
                            <input type="text" class="form-control" id="eventDate" readonly>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger" onclick="closeModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>