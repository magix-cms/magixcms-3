<div class="row">
    <form id="add_snippet" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form col-ph-12 col-lg-8 collapse in">
        <div class="row">
            <div class="col-ph-12 col-md-6">
                <div class="form-group">
                    <label for="title_sp">{#title_sp#|ucfirst} :</label>
                    <input type="text" class="form-control" id="title_sp" name="content[title_sp]" value="" placeholder="{#ph_title_sp#|ucfirst}">
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
        <div id="submit" class="col-ph-12 col-md-6">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
        </div>
    </form>
</div>