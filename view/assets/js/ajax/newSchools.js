$(document).ready(function(){
    $('.success').click(function(e){
        
        var name = $('#nameSchool').val();
        $.ajax({
            type: 'POST',
            url: 'controller/ajax/ajax.form.php',
            data: {
                nameSchool: name
            },
            success: function(response){
                if (response = 'ok') {
                    closeMenu('modal-collapse');
                    $('#schools').DataTable().ajax.reload();
                }
            },
            error: function(xhr, status, error){
                // Manejar errores de la solicitud AJAX
                alert('Error al registrar la escuela. Por favor, inténtalo de nuevo.');
            }
        });
    });
});
