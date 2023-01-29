<div class="row">
    <div class="col-ph-12">
        {*<div id="progress-thumbnail" class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar-state">
                    <span class="state">Connexion au serveur ...</span>
                </div>
            </div>
            <span class="state">Connexion au serveur ...</span>
        </div>*}
        {include file="section/form/progressBar.tpl"}
    </div>
    <form id="add_img_pages" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}" method="post" enctype="multipart/form-data" class="form-gen col-ph-12">
        <div class="dropzone multi-img-drop">
            {*Déposez vos images ici...*}
            <div class="drop-buttons form-group">
                <div class="drop-text">{#drop_imgs_here#}</div>
                <label class="btn btn-default" for="imgs">ou cliquez ici.. <span class="fa fa-upload"></span></label>
                <button class="btn btn-main-theme" type="submit" name="action" value="img" disabled>{#send#|ucfirst}</button>
            </div>
            <input type="hidden" name="MAX_FILE_SIZE" value="4048576" />
            <input type="hidden" id="page[id]" name="id" value="{$page.id_pages}">
            <input type="file" accept="image/*" id="imgs" name="img_multiple[]" value="" multiple />
        </div>
    </form>
</div>