$(document).ready(function(){

    $('.update').click(function(e){
        e.preventDefault();
        var name = $('#nameSchoolEdit').val();
        var idSchool = $('#edit').val();

        $.ajax({
            type: "POST",
            url: "controller/ajax/ajax.form.php",
            data: {
                nameSchoolEdit: name,
                idSchoolEdit: idSchool
                },
            success: function(data) {
                if (data == 'ok'){
                    $('#edit-1').val('');
                    closeMenu('modalNavUpdate');
                    $('#schools').DataTable().ajax.reload();
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
            searchSchool: id
            },
        dataType: 'json',
        success: function(data) {
            $('#nameSchoolEdit').val(data.nameSchool);
            $('#edit').val(data.idSchool);

            document.querySelector('#' + collapse).classList.add('show');
            var modalBackdrop = document.createElement('div');
            modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(modalBackdrop);
        }
    });
}