<div class="row">
    <form id="edit_img_pages" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}" method="post" enctype="multipart/form-data" class="validate_form col-xs-12 col-md-6">
        <div class="row">
            <div class="col-xs-12 col-md-2">
            <div class="form-group">
                <label for="img_pages">Image :</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="2048576" />
                <input type="file" id="img_pages" name="img" value="" />
            </div>
        </div>
        </div>
        <div class="row">
            <div id="submit" class="col-xs-12 col-md-6">
                <input type="hidden" id="id_pages" name="id" value="{$page.id_pages}">
                <button class="btn btn-main-theme pull-right" type="submit" name="action" value="img">{#save#|ucfirst}</button>
            </div>
        </div>
    </form>
</div>