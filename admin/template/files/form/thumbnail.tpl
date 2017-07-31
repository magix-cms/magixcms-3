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
    <form id="new_thumbnail" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="form-gen col-ph-12 col-sm-6 col-md-4">
        <div class="form-group">
            <label for="attr_name">{#config_module_available#}</label>
            <select name="attr_name" id="attr_name" class="form-control required" required>
                <option value="">{#ph_config#|ucfirst}</option>
                {foreach $setConfig as $key => $val}
                    <option value="{$val.attr_name}">{#$val.attr_name#|ucfirst}</option>
                {/foreach}
            </select>
        </div>
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>