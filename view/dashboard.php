<!DOCTYPE html>
<html lang="zxx">

<head>
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<title>UNIMO - Servicios generales</title>
	<?php include "css.php"; ?>
</head>

<body>
	
<?php
    include 'whiteList.php';
?>

    
<script src="view/assets/js/bootstrap.bundle.min.js"></script>
<script>

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
                echo 'Escuelas registradas';
            } else if ($pagina === 'zones'){
                echo 'Zonas registradas';
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
        if (collapse == 'modalAreas') {
            // Borrar archivos cargados en el Dropzone
            var myDropzone = Dropzone.forElement("#addAreasDropzone");
            if (myDropzone) {
                myDropzone.removeAllFiles();
                numCSV = 0;
                var submitButton = document.querySelector('.sendAreas');
                submitButton.disabled = true;
            }
        }
        document.querySelector('#' + collapse).classList.add('show');
        var modalBackdrop = document.createElement('div');
        modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
        document.body.appendChild(modalBackdrop);

        // Restablecer los campos del formulario
        resetFormFields(idForm);
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

</script>

</html>
