$(document).ready(function () {
    $.ajax ({
        url: 'controller/ajax/getSchools.php',
        dataSrc: '',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            var html = `
                <a href="./" class="mt-3 btn btn-success">
                    <div class="row">
                        <div class="col-2">
                            <i class="fa-duotone fa-house"></i> 
                        </div>
                        <div class="col-8">Tablero</div> 
                    </div>
                </a>
            `;
            data.forEach(school => {
                html += `
                    <a href="school&idSchool=${school.idSchool}" class="mt-3 btn btn-success">
                        <div class="row">
                            <div class="col-10">${school.nameSchool}</div> 
                            <div class="col-2">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                `;
            });
            $('.schools').html(html);
            $('#schools').html(html);
        }
    });
});
