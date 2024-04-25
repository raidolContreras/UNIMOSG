$(document).ready(function(){

    $('.update').click(function(e){
        e.preventDefault();
        var name = $('#edit-1').val();
        var email = $('#edit-3').val();
        var level = $('#edit-5').val();
        var idUser = $('#edit').val();

        $.ajax({
            type: "POST",
            url: "controller/ajax/ajax.form.php",
            data: {
                nameEdit: name,
                emailEdit: email,
                levelEdit: level,
                idUser: idUser
                },
            success: function(data) {
                if (data == 'ok'){
                    $('#edit-1').val('');
                    $('#edit-3').val('');
                    $('#edit-4').val('');
                    $('#edit-5').val(0);
                    closeMenu('modalNavUpdate');
                    $('#tableUsers').DataTable().ajax.reload();
                }
            }
        });   
    });
});

function openMenuEdit(collapse, idForm, id) {

    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            searchUser: id
            },
        dataType: 'json',
        success: function(data) {
            $('#edit-1').val(data.name);
            $('#edit-3').val(data.email);
            $('#edit-5').val(data.level);
            $('#edit').val(data.idUsers);

            document.querySelector('#' + collapse).classList.add('show');
            var modalBackdrop = document.createElement('div');
            modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(modalBackdrop);
        }
    });
}