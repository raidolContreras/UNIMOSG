$(document).ready(function () {
    var school = $('#school').val();
    $.ajax ({
        url: 'controller/ajax/getSchools.php',
        dataSrc: '',
        type: 'POST',
        dataType: 'json',
        data: {idSchool: school},
        success: function (data) {
            $('#nameSchool').append(data.nameSchool);
        }
    });
    
});