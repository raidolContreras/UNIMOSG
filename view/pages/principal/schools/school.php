    <div class="card-custom">
        <div class="card-header-custom">
            <strong id='nameSchool'></strong>
        </div>
    </div>

    <div class="card">
        <div class="row justify-content-center p-3">
            <div class="col-12 col-md-4 my-3">
                <button onclick="pendiente(<?php echo $_GET['idSchool'] ?>)" class="btn btn-success w-100">Incidencias pendientes<span class="pendiente position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">1</span></button>
            </div>
            <div class="col-12 col-md-4 my-3">
                <button onclick="ungente(<?php echo $_GET['idSchool'] ?>)" class="btn btn-success w-100">Incidencias urgentes<span class="ungente position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">5</span></button>
            </div>
            <div class="col-12 col-md-4 my-3">
                <button onclick="importante(<?php echo $_GET['idSchool'] ?>)" class="btn btn-success w-100">Incidencias importantes<span class="importante position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span></button>
            </div>
        </div>
    </div>

    <input type="hidden" id="school" value="<?php echo $_GET['idSchool'] ?>">

    <script src="view/assets/js/ajax/General/getSchool.js"></script>