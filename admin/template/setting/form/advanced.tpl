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
    <fieldset>
        <legend>{#tinymce_setting#}</legend>
        <div class="form-group">
            <label for="content_css">{#tinymce_css#}</label>
            <input type="text" id="content_css" name="setting[content_css]" class="form-control" placeholder="{#ph_tinymce_css#}" value="{$settings.content_css}" />
        </div>
    </fieldset>
    <fieldset>
        <legend>{#performance_setting#}</legend>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="concat" name="setting[concat]" class="switch-native-control"{if isset($settings.concat) && $settings.concat eq '1'} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="concat">
                {#concat_setting#}&nbsp;?
                <a href="#" class="text-warning advanced-popover" data-trigger="hover" data-toggle="popover">
                    <span class="fa fa-question-circle"></span>
                </a>
            </label>
            <div id="popover-content-concat" class="hide">
                {#concat_warning#}
            </div>
        </div>
        <div class="row">
            <div class="col-ph-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="mode">
                        {#mode_setting#}
                        <a href="#" class="text-info advanced-popover" data-trigger="hover" data-toggle="popover">
                            <span class="fa fa-question-circle"></span>
                        </a>
                    </label>
                    <div id="popover-content-mode" class="hide">
                        {#mode_warning#}
                    </div>
                    <select name="setting[mode]" id="mode" class="form-control required" required>
                        {foreach $collectionformMode as $key => $val}
                            <option value="{$key}" {if $settings.mode == $key} selected{/if}>{$val}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-ph-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="cache">
                        {#cache_setting#}
                        <a href="#" class="text-info advanced-popover" data-trigger="hover" data-toggle="popover">
                            <span class="fa fa-question-circle"></span>
                        </a>
                    </label>
                    <div id="popover-content-cache" class="hide">
                        {#cache_warning#}
                    </div>
                    <select name="setting[cache]" id="cache" class="form-control required" required>
                        {foreach $collectionformCache as $key => $val}
                            <option value="{$key}" {if $settings.cache == $key} selected{/if}>{$val}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{#protocol_setting#}</legend>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="ssl" name="setting[ssl]" class="switch-native-control"{if isset($settings.ssl) && $settings.ssl eq '1'} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="ssl">
                {#ssl_setting#}&nbsp;?
                <a href="#" class="text-warning" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="{#ssl_warning#}">
                    <span class="fa fa-question-circle"></span>
                </a>
            </label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="http2" name="setting[http2]" class="switch-native-control"{if isset($settings.http2) && $settings.http2 eq '1'} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="http2">
                {#http2_setting#}&nbsp;?
                <a href="#" class="text-warning" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="{#http2_warning#}">
                    <span class="fa fa-question-circle"></span>
                </a>
            </label>
        </div>
    </fieldset>
    <fieldset>
        <legend>{#additionnal_feature_setting#}</legend>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="amp" name="setting[amp]" class="switch-native-control"{if isset($settings.amp) && $settings.amp eq '1'} checked{/if}{if isset($settings.ssl) && $settings.ssl !== '1'} disabled{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="amp">
                {#amp_setting#}&nbsp;?
                <a href="#" class="text-warning" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="{#amp_warning#}">
                    <span class="fa fa-question-circle"></span>
                </a>
            </label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="service_worker" name="setting[service_worker]" class="switch-native-control"{if isset($settings.service_worker) && $settings.service_worker eq '1'} checked{/if}{if isset($settings.ssl) && $settings.ssl !== '1'} disabled{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="service_worker">
                {#service_worker_setting#}&nbsp;?
                <a href="#" class="text-warning" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="{#service_worker_warning#}">
                    <span class="fa fa-question-circle"></span>
                </a>
            </label>
        </div>
    </fieldset>
    <fieldset>
        <legend>{#maintenance_setting#}</legend>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="maintenance" name="setting[maintenance]" class="switch-native-control"{if isset($settings.maintenance) && $settings.maintenance eq '1'} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="maintenance">
                {#enable_maintenance#}&nbsp;?
                <a href="#" class="text-warning" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="{#maintenance_warning#}">
                    <span class="fa fa-question-circle"></span>
                </a>
            </label>
        </div>
    </fieldset>
    <div id="submit">
        <input type="hidden" id="type" name="type" value="general">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </div>
</form>