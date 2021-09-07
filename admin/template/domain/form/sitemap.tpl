<div class="row">
    <div class="col-ph-12">
        {include file="section/form/progressBar.tpl"}
    </div>
    <form id="create_sitemap" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$domain.id_domain}" method="post" class="form-gen col-ph-12 col-sm-6 col-md-4">
        <input type="hidden" id="data_type" name="data_type" value="sitemap">
        <input type="hidden" id="id_domain" name="id" value="{$domain.id_domain}">
        <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#gen_sitemap#|ucfirst}</button>
    </form>
</div>