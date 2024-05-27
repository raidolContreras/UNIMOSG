$(document).ready(function(){

    $('.update').click(function(e){
        e.preventDefault();
        var name = $('#nameZoneEdit').val();
        var idZone = $('#edit').val();

        $.ajax({
            type: "POST",
            url: "controller/ajax/ajax.form.php",
            data: {
                nameZoneEdit: name,
                idZoneEdit: idZone
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