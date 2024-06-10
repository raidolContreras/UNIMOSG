<div class="card-custom">
    <div class="card-header-custom">
        <strong id='namePage'></strong>
    </div>
</div>
<?php if ($_SESSION['level']== 0):?>
<div class="card">
    <div class="row justify-content-center">
        <div class="col-12 col-md-4 my-3">
            <a href="users" class="btn btn-success w-100">Lista de usuarios</a>
        </div>
        <div class="col-12 col-md-4 my-3">
            <a href="schools" class="btn btn-success w-100">Lista de escuelas</a>
        </div>
    </div>
</div>
<?php elseif($_SESSION['level']== 1): ?>
    <div class="row justify-content-center">
        <div class="col-8 card p-3">
            <canvas id="myChart"></canvas>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="view/assets/js/ajax/principal/getStats.js"></script>

<?php else: 
    include 'view/pages/principal/general/lista.php';
?>
    
<?php endif ?>


</div>
</div>