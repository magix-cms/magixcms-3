<form id="edit_setting_general" action="{$smarty.server.SCRIPT_NAME}?controller={$controller}&amp;action=edit" method="post" class="validate_form add_form collapse in">
    <div class="row">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="content_css">{#content_css#|ucfirst}</label>
                    <input type="text" id="content_css" name="setting[content_css]" class="form-control" value="{$settings.content_css}" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="concat">{#concat#|ucfirst}&nbsp;?</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="concat" name="setting[concat]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($setting.settings)} checked{/if}/>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="submit" class="col-xs-12 col-md-6">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </div>
    </div>
</form>