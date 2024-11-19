<!DOCTYPE html>
<html lang="zxx">

<head>
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<title>UNIMO - Servicios generales</title>
	<?php include "css.php"; ?>
</head>

<body>
<div class="loader-section">
     <span class="loader"></span>
</div>
<?php
    include 'whiteList.php';
?>

<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Permitir Notificaciones</h5>
                <button type="button" class="btn btn-danger" id="denegateNotify" onclick="closeModal('notificationModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Este sitio web desea enviarle notificaciones. ¿Desea permitirlas?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="denyNotifications" onclick="closeModal('notificationModal')">Denegar</button>
                <button type="button" class="btn btn-success" id="allowNotifications">Permitir</button>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_SESSION['level'])):?>
    <input type="hidden" id="level" value="<?php echo $_SESSION['level'] ?>">
<?php endif ?>

<script src="view/assets/js/bootstrap.bundle.min.js"></script>
<script>

    document.onload = pageLoaded();

    document.addEventListener('DOMContentLoaded', function() {
        // Tu código JavaScript aquí
        $("#namePage").html(`<?php 
            if ($pagina === 'inicio'){
                if ($_SESSION['level'] != 1){
                    echo 'Servicios generales UNIMO';
                } else {
                    echo 'Estadísticas de reparaciones';
                }
            } else if ($pagina === 'newUser'){
                echo 'Registrar nuevos usuarios';
            } else if ($pagina === 'users'){
                echo 'Usuarios registrados';
            } else if ($pagina === 'schools'){
                echo 'Planteles registrados';
            } else if ($pagina === 'edifices'){
                echo 'Edificios registrados';
            } else if ($pagina === 'zones'){
                echo 'Zonas';
            } else if ($pagina === 'objects'){
                echo 'Objetos por area';
            } else {
                echo $pagina;
            }
        ?>`);
    });

    function closeMenu(collapse) {
        document.querySelector('#' + collapse).classList.remove('show');
            var modalBackdrop = document.querySelector('.modal-backdrop');
            if (modalBackdrop) {
                modalBackdrop.parentNode.removeChild(modalBackdrop);
            }
        document.querySelector('.navbar-toggler').classList.remove('active');
    }

    function openMenu(collapse, idForm) {
        document.querySelector('#' + collapse).classList.add('show');
        var modalBackdrop = document.createElement('div');
        modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
        document.body.appendChild(modalBackdrop);

        // Restablecer los campos del formulario
        resetFormFields(idForm);
        
        // Cerrar el modal cuando se hace clic en el fondo
        $('.modal-backdrop').on('click', function() {
            closeMenu(collapse);
        });
    }

    function openMenuZoneSchool(collapse, idForm, idReference, nameschool) {
        
        // Borrar archivos cargados en el Dropzone
        var myDropzone = Dropzone.forElement("#addZonesDropzone");
        if (myDropzone) {
            myDropzone.removeAllFiles();
            numCSV = 0;
            var submitButton = document.querySelector('.sendZones');
            submitButton.disabled = true;
        }
        document.querySelector('#' + collapse).classList.add('show');
        var modalBackdrop = document.createElement('div');
        modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
        document.body.appendChild(modalBackdrop);
        $('.idReference').val(idReference);
        $('.nameschool').html(nameschool);
        // Restablecer los campos del formulario
        resetFormFields(idForm);
    }

    function resetFormFields(idForm) {
        // Obtén el formulario
        var form = document.getElementById(idForm);

        // Restablece los campos de texto
        var inputs = form.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type == 'text' || inputs[i].type == 'password') {
                inputs[i].value = '';
            }
        }

        // Restablece los selectores
        var selects = form.getElementsByTagName('select');
        for (var i = 0; i < selects.length; i++) {
            selects[i].selectedIndex = 0;
        }
    }

    function pageLoaded() {
        let loaderSection = document.querySelector('.loader-section');
        loaderSection.classList.add('loaded');
    }

    function closeModal(modal) {
        $('#' + modal).modal('hide');
    }

</script>

</html>
