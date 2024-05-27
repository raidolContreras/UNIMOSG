$(document).ready(function(){

    $('.update').click(function(e){
        e.preventDefault();
        var name = $('#nameAreaEdit').val();
        var idArea = $('#edit').val();

        $.ajax({
            type: "POST",
            url: "controller/ajax/ajax.form.php",
            data: {
                nameAreaEdit: name,
                idAreaEdit: idArea
                },
            success: function(data) {
                if (data == 'ok'){
                    $('#edit').val('');
                    closeMenu('modalNavUpdate');
                    $('#zones').DataTable().ajax.reload();
                }
            }
        });   
    });
});