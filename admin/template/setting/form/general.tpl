{assign var="collectionformCache" value=[
"none"=>"None",
"files"=>"Files",
"apc"=>"APC"
]}
{assign var="collectionformMode" value=[
"dev"=>"Dev",
"prod"=>"Production"
]}
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
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="concat">{#concat#|ucfirst}&nbsp;?</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="concat" name="setting[concat]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($settings.concat) && $settings.concat eq '1'} checked{/if}/>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="concat">{#ssl#|ucfirst}&nbsp;?</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="ssl" name="setting[ssl]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($settings.ssl) && $settings.ssl eq '1'} checked{/if}/>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="cache">{#cache#|ucfirst}</label>
                    <select name="setting[cache]" id="cache" class="form-control required" required>
                        {foreach $collectionformCache as $key => $val}
                            <option value="{$key}" {if $settings.cache == $key} selected{/if}>{$val|ucfirst}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="cache">{#mode#|ucfirst}</label>
                    <select name="setting[mode]" id="cache" class="form-control required" required>
                        {foreach $collectionformMode as $key => $val}
                            <option value="{$key}" {if $settings.mode == $key} selected{/if}>{$val|ucfirst}</option>
                        {/foreach}
                    </select>
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