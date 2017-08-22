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
    <form id="create_sitemap" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$domain.id_domain}" method="post" class="form-gen col-ph-12 col-sm-6 col-md-4">
        <input type="hidden" id="data_type" name="data_type" value="sitemap">
        <input type="hidden" id="id_domain" name="id" value="{$domain.id_domain}">
        <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#gen_sitemap#|ucfirst}</button>
    </form>
</div>