<div class="row">
    <form id="edit_img_pages" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}" method="post" enctype="multipart/form-data" class="validate_form edit_form_img">
        <div class="col-ph-12">
            <div class="form-group">
                <input type="hidden" name="MAX_FILE_SIZE" value="2048576" />
                <input type="file" id="img_pages" name="img" class="inputfile inputimg" value="" />
                <label for="img_pages">
                    <figure>
                        <span class="fa-stack fa-lg">
                          <span class="fa fa-circle fa-stack-2x"></span>
                          <span class="fa fa-upload fa-stack-1x fa-inverse"></span>
                        </span>
                    </figure>
                    <span id="input-label">Choisissez une image&hellip;</span>
                </label>
                <input type="hidden" id="id_pages" name="id" value="{$page.id_pages}">
                <button class="btn btn-main-theme" type="submit" name="action" value="img">{#save#|ucfirst}</button>
            </div>
        </div>
    </form>
</div>