$(document).ready(function () {
    $.ajax ({
        url: 'controller/ajax/getSchools.php',
        dataSrc: '',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            var html = `
                <a href="./" class="mt-3 menu-top py-2">
                    <div class="d-flex align-items-center">
                        <div class="col-2">
                            <i class="fa-duotone fa-house"></i>
                        </div>
                        <div class="col-8" style="font-size: 14px;">Tablero</div> 
                    </div>
                </a>
                <a href="?pagina=school" class="mt-3 menu-top py-2">
                    <div class="d-flex align-items-center">
                        <div class="col-2">
                            <i class="fa-duotone fa-cabinet-filing"></i>
                        </div>
                        <div style="font-size: 14px;">Registro de incidentes</div>
                    </div>
                </a>
                <a href="?pagina=plan" class="mt-3 menu-top py-2">
                    <div class="d-flex align-items-center">
                        <div class="col-2">
                            <i class="fa-duotone fa-radar"></i>
                        </div>
                        <div class="col-8" style="font-size: 14px;">Plan de supervición</div> 
                    </div>
                </a>
            `;
            var html2 = `
                <div class="row align-items-center" style="justify-content: center;">
                    <a href="./" class="mt-3 mx-1 menu-top py-2 col-3">
                        <div class="d-flex align-items-center">
                            <div class="col-2">
                                <i class="fa-duotone fa-house"></i>
                            </div>
                            <div class="col-8" style="font-size: 14px;">Tablero</div> 
                        </div>
                    </a>
                    <a href="?pagina=school" class="mt-3 mx-1 menu-top py-2 col-3">
                        <div class="d-flex align-items-center">
                            <div class="col-2">
                                <i class="fa-duotone fa-cabinet-filing"></i>
                            </div>
                            <div style="font-size: 14px;">Registro de incidentes</div>
                        </div>
                    </a>
                    <a href="?pagina=plan" class="mt-3 mx-1 menu-top py-2 col-3">
                        <div class="d-flex align-items-center">
                            <div class="col-2">
                                <i class="fa-duotone fa-radar"></i>
                            </div>
                            <div class="col-8" style="font-size: 14px;">Plan de supervición</div> 
                        </div>
                    </a>
                </div>
            `;

            var html3 = '';
            data.forEach(school => {
                html3 += `
                <a href="?pagina=school&idSchool=${school.idSchool}" class="dropdown-item">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 14px;">${school.nameSchool}</div> 
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
                `;
            });

            $('.schoolsData').html(html3);
            $('#schools').html(html2);
            $('.schools').html(html);
        }
    });
});
