var numCSV = 0;

$(document).ready(function() {
    // Obtener los elementos
    var $zoneNameInput = $('#ZoneName');
    var $schoolSelect = $('#selectSchool');
    var $saveButton = $('.saveNewZone');

    // Función para verificar si ambos campos están llenos
    function checkFields() {
        var zoneNameFilled = $zoneNameInput.val().trim() !== '';
        var schoolSelected = $schoolSelect.val() !== null && $schoolSelect.val() !== '';

        if (zoneNameFilled && schoolSelected) {
            $saveButton.prop('disabled', false); // Habilitar el botón
        } else {
            $saveButton.prop('disabled', true); // Deshabilitar el botón
        }
    }

    // Añadir eventos a los campos
    $zoneNameInput.on('input', checkFields);
    $schoolSelect.on('change', checkFields);

    var idSchool;
    var myDropzone = new Dropzone("#addZonesDropzone", {
        maxFiles: 1,
        url: "controller/ajax/ajax.form.php",
        maxFilesize: 2,
        acceptedFiles: "text/csv",
        dictDefaultMessage: 'Arrastra y suelta el archivo aquí o haz clic para seleccionar uno <p class="subtitulo-sup">Tipos de archivo permitidos .csv (Tamaño máximo 2 MB)</p>',
        autoProcessQueue: false,
        dictInvalidFileType: "Archivo no permitido. Por favor, sube un archivo en formato CSV.",
        dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo permitido: {{maxFilesize}}MB.",
        errorPlacement: function(error, element) {
            var $element = $(element),
                errContent = $(error).text();
            $element.attr('data-toggle', 'tooltip');
            $element.attr('title', errContent);
            $element.tooltip({
                placement: 'top'
            });
            $element.tooltip('show');
    
            // Agregar botón de eliminar archivo
            var removeButton = Dropzone.createElement('<button class="rounded-button">&times;</button>');
            removeButton.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                myDropzone.removeFile(element);
            });
            $element.parent().append(removeButton); // Agregar el botón al contenedor del input
        },
        init: function() {
            this.on("addedfile", function(file) {
                if (file.type === "text/csv") {
                    numCSV++;
                    console.log(numCSV);
                }
                toggleSubmitButton(); // Actualizar estado del botón de enviar
                var removeButton = Dropzone.createElement('<button class="rounded-button">&times;</button>');
                var _this = this;
                removeButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (file.type === "text/csv") {
                        numCSV--;
                        console.log(numCSV);
                    } 
                
                    _this.removeFile(file);
                    toggleSubmitButton(); // Actualizar estado del botón de enviar
                });
                file.previewElement.appendChild(removeButton);
            });
        }
    });

    $('.cancelMassiveZones').on('click', function() {
        $('.moduleAddZones').addClass('d-none');
        $('.newZoneForm').removeClass('d-none');
        $('.addMassiveZone').removeClass('d-none');
        $('#idSchool').val('');
        numCSV = 0;
        toggleSubmitButton();
        myDropzone.removeAllFiles();
    });
    
    $('.sendZones').click(function(e){
        idSchool = $('#idSchool').val();
        myDropzone.processQueue();
    });

    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append("uploadZones", idSchool);
    });

    myDropzone.on("success", function(file, response) {
        
        if (response == 'ok') {
            closeMenu('modalZones');
            myDropzone.removeAllFiles();
            showAlertBootstrap('Éxito', 'Archivo cargado excitosamente.');
        } else {
            myDropzone.removeAllFiles();
            showAlertBootstrap('¡Alerta!', 'El archivo no se pudo cargar, intentalo más tarde.');
        }
    });

});

function toggleSubmitButton() {
    var submitButton = document.querySelector('.sendZones');
    var zone = $('#zone').val();
    if (numCSV == 1) {
        if (zone != '1'){
            submitButton.disabled = false;
        } else {
            var select = $('#idSchool').val();
            if (select >= 1) {
                submitButton.disabled = false;
            }
        }
    } else {
        submitButton.disabled = true;
    }
}
document.getElementById('idSchool').addEventListener('change', function() {
    toggleSubmitButton();
});

$('.addMassiveZone').on('click', function() {
    $('.newZoneForm').addClass('d-none');
    $('.addMassiveZone').addClass('d-none');
    $('.moduleAddZones').removeClass('d-none');
});

function resetZoneForm() {
    $('.newZoneForm').addClass('d-none');
    $('.addZones').removeClass('d-none');
    $('#ZoneName').val('');
    $('#selectSchool').val('');
    toggleSubmitButton();
}

$('.saveNewZone').on('click', function() {
    //validar el formulario
    var zoneName = $('#ZoneName').val();
    var school = $('#selectSchool').val();
    // hacer la petición AJAX para guardar la zona
    $.ajax({
        type: 'POST',
        url: 'controller/ajax/ajax.form.php',
        data: {
            addNewZone: true,
            nameZone: zoneName,
            idSchool: school
        },
        success: function(response) {
            if (response == 'ok') {
                closeMenu('modalZones');
                showAlertBootstrap('Éxito', 'Zona creada exitosamente.');
                // resetear el datatable
                $('#zones').DataTable().ajax.reload();
                //borrar el formulario
                $('#ZoneName').val('');
                $('#selectSchool').val('');
            } else {
                showAlertBootstrap('¡Alerta!', 'La zona no se pudo crear, intentalo más tarde.');
            }
        }
    });
});