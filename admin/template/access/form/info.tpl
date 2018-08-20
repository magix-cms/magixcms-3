<form id="add_setup" action="{$url}/{baseadmin}/index.php?controller=access&amp;action=edit&edit={$role.id_role}" method="post" class="validate_form edit_form">
    <div class="row">
        <div class="col-ph-12 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="role_name">{#role_name#|ucfirst}</label>
                <input type="text" id="role_name" name="role_name" class="form-control" value="{$role.role_name}" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-ph-12 col-lg-8">
            <hr>
            <div class="form-group text-center">
                <input type="hidden" name="id" value="{$role.id_role}">
                <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
            </div>
        </div>
    </div>
</form>
