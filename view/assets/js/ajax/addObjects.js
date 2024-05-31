var numCSV = 0;
$(document).ready(function(){
    var myDropzone = new Dropzone("#addObjectsDropzone", {
        maxFiles: 1,
        url: "controller/ajax/ajax.form.php",
        maxFilesize: 2,
        acceptedFiles: "text/csv",
        dictDefaultMessage: 'Arrastra y suelta el archivo aquí o haz clic para seleccionar uno <p class="subtitulo-sup">Tipo de archivo permitido .csv (Tamaño máximo 2 MB)</p>',
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
                }
                toggleSubmitButton(); // Actualizar estado del botón de enviar
                var removeButton = Dropzone.createElement('<button class="rounded-button">&times;</button>');
                var _this = this;
                removeButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (file.type === "text/csv") {
                        numCSV--;
                    } 
                
                    _this.removeFile(file);
                    toggleSubmitButton(); // Actualizar estado del botón de enviar
                });
                file.previewElement.appendChild(removeButton);
            });
        }
    });    
    
    $('.sendObjects').click(function(e){
        idArea = $('#idArea').val();
        myDropzone.processQueue();
    });

    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append("uploadObjects", idArea);
    });

    myDropzone.on("success", function(file, response) {
        
        if (response == 'ok') {
            closeMenu('modalObjects');
            myDropzone.removeAllFiles();
            showAlertBootstrap('Éxito', 'Archivo cargado excitosamente.');
            $('#objects').DataTable().ajac().reload();
        } else {
            myDropzone.removeAllFiles();
            showAlertBootstrap('¡Alerta!', 'El archivo no se pudo cargar, intentalo más tarde.');
        }
    });

});

function toggleSubmitButton() {
    var submitButton = document.querySelector('.sendObjects');
    if (numCSV == 1) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}