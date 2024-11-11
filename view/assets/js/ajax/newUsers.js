$(document).ready(function(){
    $('.success').click(function(e){
        e.preventDefault();
        var name = $('#regular-form-1').val();
        var email = $('#regular-form-3').val();
        var password = $('#regular-form-4').val();
        var phone = $('#regular-form-6').val();
        var level = $('#regular-form-5').val();

        $.ajax({
            type: "POST",
            url: "controller/forms.ajax.php",
            data: {
                action: "registerUser",
                name: name,
                email: email,
                password: password,
                phone: phone,
                level: level
                },
            success: function(data) {
                if (data == 'ok'){
                    $('#regular-form-1').val('');
                    $('#regular-form-3').val('');
                    $('#regular-form-4').val('');
                    $('#regular-form-5').val(0);
                    $('#regular-form-6').val('');
                    closeMenu('modalNav');
                    $('#tableUsers').DataTable().ajax.reload();
                }
            }
        });   
    });
});
