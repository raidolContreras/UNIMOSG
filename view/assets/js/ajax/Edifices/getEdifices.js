$(document).ready(function() {
    let myDropzone = null;

    // Función para habilitar o deshabilitar el botón "Aceptar" según los valores de los campos
    function toggleAcceptButton() {
        const edificeName = $('#edificeName').val().trim();
        
        if (edificeName) {
            $('.saveNewEdifice').prop('disabled', false);
            
            // Quitar cualquier evento de clic previamente asignado para evitar duplicación
            $('.saveNewEdifice').off('click').on('click', function() {
                // $ajax que crea el edificio
                $.ajax({
                    url: "/ruta_del_servicio_para_crear_el_edificio",
                    type: "POST",
                    data: {
                        edificeName: edificeName,
                        schoolId: $('#selectSchool').val()
                    },
                    success: function(response) {
                        alert("Edificio creado correctamente");
                        // Limpiar los campos de entrada
                        $('#edificeName').val('');
                        $('#selectSchool').val('');
                        // Deshabilitar el botón "Aceptar"
                        $('.saveNewEdifice').prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        alert("Error al crear el edificio: " + error);
                    }
                });
                
                // Opcional: Deshabilitar el botón después de enviar para evitar múltiples envíos
                $(this).prop('disabled', true);
            });
        } else {
            $('.saveNewEdifice').prop('disabled', true);
        }
    }

    // Asignar el evento a los campos de entrada y selección
    $('#edificeName').on('keyup', toggleAcceptButton);
    $('#selectSchool').on('change', toggleAcceptButton);


    $('.addMassiveEdifice').click(function() {
        $('.moduleAddEdifices').removeClass('d-none');
        $('.newEdificeForm').addClass('d-none');
        $('.addEdifices').addClass('d-none');

        if (!myDropzone) {
            myDropzone = new Dropzone("#addEdificesDropzone", {
                url: "/ruta_de_subida",
                acceptedFiles: ".xls,.xlsx",
                init: function() {
                    this.on("success", function(file, response) {
                        alert("Archivo subido correctamente");
                    });
                }
            });
        }
    });

    $('.cancelMassiveEdifices').click(function() {
        $('.moduleAddEdifices').addClass('d-none');
        $('.newEdificeForm').removeClass('d-none');
        $('.addEdifices').removeClass('d-none');

        if (myDropzone) {
            myDropzone.destroy();
            myDropzone = null;
        }
    });
});
