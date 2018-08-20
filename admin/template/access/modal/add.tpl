{*-- Modal --*}
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="addModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{#add_role#|ucfirst}</h4>
            </div>
            <form id="add_provider" class="validate_form add_modal_form" action="{$url}/{baseadmin}/index.php?controller=access&amp;action=add" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role_name">{#role#|ucfirst}&nbsp;*</label>
                        <input type="text" id="role_name" name="role_name" class="form-control required" placeholder="{#ph_role#|ucfirst}" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">{#cancel#|ucfirst}</button>
                    <button type="submit" class="btn btn-danger">{#save#|ucfirst}</button>
                </div>
            </form>
        </div>
    </div>
</div>