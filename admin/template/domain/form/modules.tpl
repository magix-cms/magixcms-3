<form id="edit_module" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <div class="row">
        <div class="col-ph-12 col-md-4">
            <div class="row">
                <div class="col-ph-12 col-md-6">
                    <div class="form-group">
                        <label for="config_pages">{#pages#|ucfirst}&nbsp;?</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="config_pages" name="config[pages]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($setConfig.pages) && $setConfig.pages eq '1'} checked{/if}/>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-ph-12 col-md-6">
                    <div class="form-group">
                        <label for="config_news">{#news#|ucfirst}&nbsp;?</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="config_news" name="config[news]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($setConfig.news) && $setConfig.news eq '1'} checked{/if}/>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-md-6">
                    <div class="form-group">
                        <label for="config_catalog">{#catalog#|ucfirst}&nbsp;?</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="config_catalog" name="config[catalog]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($setConfig.catalog) && $setConfig.catalog eq '1'} checked{/if}/>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-ph-12 col-md-6">
                    <div class="form-group">
                        <label for="config_about">{#about#|ucfirst}&nbsp;?</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="config_about" name="config[about]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($setConfig.about) && $setConfig.about eq '1'} checked{/if}/>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div id="submit">
                <input type="hidden" id="data_type" name="data_type" value="modules">
                <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
            </div>
        </div>
    </div>
</form>