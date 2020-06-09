<div class="row">
    <div class="col-ph-12">
        <div id="progress-thumbnail" class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar-state">
                    <span class="state">Connexion au serveur ...</span>
                </div>
            </div>
            <span class="state">Connexion au serveur ...</span>
        </div>
    </div>
    <form id="add_img_pages" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}" method="post" enctype="multipart/form-data" class="form-gen col-ph-12">
        <div id="drop-zone">
            Déposez vos images ici...
            <div id="drop-buttons" class="form-group">
                <label id="clickHere" class="btn btn-default">
                    ou cliquez ici.. <span class="fa fa-upload"></span>
                    <input type="hidden" name="MAX_FILE_SIZE" value="4048576" />
                    <input type="file" id="img_multiple" name="img_multiple[]" value="" multiple />
                    <input type="hidden" id="page[id]" name="id" value="{$page.id_pages}">
                </label>
                <button class="btn btn-main-theme" type="submit" name="action" value="img" disabled>{#send#|ucfirst}</button>
            </div>
        </div>
    </form>
</div>