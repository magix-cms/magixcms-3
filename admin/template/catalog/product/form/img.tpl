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
    <form id="add_img_product" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_product}" method="post" enctype="multipart/form-data" class="form-gen">
        <div class="col-xs-12">
            <div class="form-group">
                <input type="hidden" name="MAX_FILE_SIZE" value="4048576" />
                <input type="file" id="img_multiple" name="img_multiple[]" class="inputfile inputimg" value="" multiple />
                <label for="img_multiple">
                    <figure>
                        <span class="fa-stack fa-lg">
                          <span class="fa fa-circle fa-stack-2x"></span>
                          <span class="fa fa-upload fa-stack-1x fa-inverse"></span>
                        </span>
                    </figure>
                    <span id="input-label">Choisissez une image&hellip;</span>
                </label>
                <input type="hidden" id="id_product" name="id" value="{$page.id_product}">
                <button class="btn btn-main-theme" type="submit" name="action" value="img">{#save#|ucfirst}</button>
            </div>
        </div>
    </form>
</div>