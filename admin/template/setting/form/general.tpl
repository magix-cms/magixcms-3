{assign var="collectionformCache" value=[
"none"=>"None",
"files"=>"Files",
"apc"=>"APC"
]}
{assign var="collectionformMode" value=[
"dev"=>"Developpement",
"prod"=>"Production"
]}
<form id="edit_setting_general" action="{$smarty.server.SCRIPT_NAME}?controller={$controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <div class="row">
        <div class="col-ph-12 col-md-4">
            <div class="form-group">
                <label for="content_css">{#content_css#|ucfirst}</label>
                <input type="text" id="content_css" name="setting[content_css]" class="form-control" value="{$settings.content_css}" />
            </div>
            <div class="row">
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="concat">{#concat#|ucfirst}&nbsp;?</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="concat" name="setting[concat]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($settings.concat) && $settings.concat eq '1'} checked{/if}/>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="ssl">{#ssl#|ucfirst}&nbsp;?</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="ssl" name="setting[ssl]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($settings.ssl) && $settings.ssl eq '1'} checked{/if}/>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="service_worker">{#service_worker#|ucfirst}&nbsp;?</label>
                        <a href="#" class="text-info" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="SSL Requis">
                            <span class="fa fa-question-circle"></span>
                        </a>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="service_worker" name="setting[service_worker]" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($settings.service_worker) && $settings.service_worker eq '1'} checked{/if}{if isset($settings.ssl) && $settings.ssl !== '1'} disabled{/if}/>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="cache">{#cache#|ucfirst}</label>
                        <select name="setting[cache]" id="cache" class="form-control required" required>
                            {foreach $collectionformCache as $key => $val}
                                <option value="{$key}" {if $settings.cache == $key} selected{/if}>{$val|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="mode">{#mode#|ucfirst}</label>
                        <select name="setting[mode]" id="mode" class="form-control required" required>
                            {foreach $collectionformMode as $key => $val}
                                <option value="{$key}" {if $settings.mode == $key} selected{/if}>{$val|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                {*<div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="fav">FontAwesome Version&nbsp;?</label>
                        <input type="text" id="fav" name="setting[fav]" class="form-control" value="{$settings.fav}" />
                    </div>
                </div>*}
            </div>
            <div id="submit">
                <input type="hidden" id="type" name="type" value="general">
                <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
            </div>
        </div>
    </div>
</form>