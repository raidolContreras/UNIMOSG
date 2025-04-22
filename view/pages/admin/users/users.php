<div class="card-custom">
    <div class="card-header-custom">
        <strong id='namePage'></strong>
    </div>
</div>
<div class="col-12">
    <div class="row">
        <div class="col">
            <button class="btn btn-secondary" onclick="openMenu('modalNav', 'newUsers')"><i class="fa-duotone fa-plus"></i> Registrar usuario</button>
        </div>
        <div class="col-2">
            <button class="btn btn-info" onclick="openApi()"><i class="fal fa-paper-plane"></i> Ver API Telegram</button>
        </div>
    </div>
    <table id="tableUsers" class="table">
        <thead>
            <tr>
                <th width="20%">Usuario</th>
                <th width="20%">Puesto</th>
                <th width="20%">Teléfono</th>
                <th width="20%">Status</th>
                <th width="20%"></th>
            </tr>
        </thead>
        <tbody class="users">
        </tbody>
    </table>
</div>

<div class="modal-collapse" id="modalNav">
    <span class="close-btn" onclick="closeMenu('modalNav')">&times;</span>
    <div class="modal-nav">
        <?php include "view/pages/admin/users/newUser.php"; ?>
    </div>
</div>

<div class="modal-collapse" id="modalNavUpdate">
    <span class="close-btn" onclick="closeMenu('modalNavUpdate')">&times;</span>
    <div class="modal-nav">
        <?php include "view/pages/admin/users/editUser.php"; ?>
    </div>
</div>

<script src="view/assets/js/ajax/getUsers.js"></script>
