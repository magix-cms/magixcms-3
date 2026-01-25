<div class="row">
    <form id="edit_snippet" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_snippet}" method="post" class="validate_form edit_form col-ph-12">
        <div class="row">
            <div class="col-ph-12 col-md-6">
                <div class="form-group">
                    <label for="title_sp">{#title_sp#|ucfirst} :</label>
                    <input type="text" class="form-control" id="title_sp" name="content[title_sp]" value="{$page.title_sp}" placeholder="{#ph_title_sp#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="description_sp">{#resume#|ucfirst} :</label>
            <textarea name="content[description_sp]" id="description_sp" class="form-control">{$page.description_sp}</textarea>
        </div>
        <div class="form-group">
            <label for="content_sp">{#content#|ucfirst} :</label>
            <textarea name="content[content_sp]" id="content_sp" class="form-control mceEditor">{call name=cleantextarea field=$page.content_sp}</textarea>
        </div>
        <input type="hidden" id="id_snippet" name="id" value="{$page.id_snippet}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>