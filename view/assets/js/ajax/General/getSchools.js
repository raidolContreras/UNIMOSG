$(document).ready(function () {
    $.ajax ({
        url: 'controller/ajax/getSchools.php',
        dataSrc: '',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            var html = `
                <a href="./" class="col-xl-12 col-xs-3 mt-3 btn btn-success">
                    Tablero
                </a>
            `;
            data.forEach(school => {
                html += `
                    <a href="school&idSchool=${school.idSchool}" class="col-xl-12 col-xs-3 mt-3 btn btn-success">
                        ${school.nameSchool}
                    </a>
                `;
            });
            $('.schools').html(html);
        }
    });
});
