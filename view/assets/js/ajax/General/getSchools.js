$(document).ready(function () {
    $.ajax ({
        url: 'controller/ajax/getSchools.php',
        dataSrc: '',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            var html = `
                <a href="./" class="mt-3 btn btn-success">
                    <div class="d-flex align-items-center">
                        <div class="col-2">
                            <i class="fa-duotone fa-house"></i>
                        </div>
                        <div class="col-8" style="font-size: 14px;">Tablero</div> 
                    </div>
                </a>
            `;
            data.forEach(school => {
                html += `
                <a href="school&idSchool=${school.idSchool}" class="mt-3 btn btn-success">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 14px;">${school.nameSchool}</div> 
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
                `;
            });
            
            html += `
                <a href="plan" class="mt-3 btn btn-success">
                    <div class="d-flex align-items-center">
                        <div class="col-2">
                            <i class="fa-duotone fa-radar"></i>
                        </div>
                        <div class="col-8" style="font-size: 14px;">Plan de supervición</div> 
                    </div>
                </a>
        
            `;

            $('.schools').html(html);
            $('#schools').html(html);
        }
    });
});
