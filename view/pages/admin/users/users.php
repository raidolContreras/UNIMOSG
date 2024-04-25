<div class="card-custom">
    <div class="card-header-custom">
        <strong id='namePage'></strong>
    </div>
</div>
<div class="col-12">
    <div class="p-3">
        <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
            <button class="btn btn-secondary" onclick="openMenu('modalNav', 'newUsers')"><i class="fa-duotone fa-plus"></i> Registrar usuario</button>
        </div>
    </div>
    <table id="tableUsers" class="table">
        <thead>
            <tr>
                <th width="20%">Usuario</th>
                <th width="20%">Puesto</th>
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
    <ul class="modal-nav">
        <?php include "view/pages/admin/users/newUser.php"; ?>
    </ul>
</div>

<div class="modal-collapse" id="modalNavUpdate">
    <span class="close-btn" onclick="closeMenu('modalNavUpdate')">&times;</span>
    <ul class="modal-nav">
        <?php include "view/pages/admin/users/editUser.php"; ?>
    </ul>
</div>

<script src="view/assets/js/ajax/getUsers.js"></script>
