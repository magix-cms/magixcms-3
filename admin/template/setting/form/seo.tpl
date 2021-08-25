{assign var="collectionformRobots" value=[
"noindex,nofollow"=>"No index",
"index,follow,all"=>"Index"
]}
<form id="edit_setting_seo" action="{$smarty.server.SCRIPT_NAME}?controller={$controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <div class="row">
        <div class="col-ph-12 col-md-4">
            <div class="form-group">
                <label for="robots">{#robots_setting#}</label>
                <select name="setting[robots]" id="robots" class="form-control required" required>
                    {foreach $collectionformRobots as $key => $val}
                        <option value="{$key}" {if $settings.robots == $key} selected{/if}>{$val}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label for="analytics">{#analytics_setting#}</label>
                <input type="text" id="analytics" name="setting[analytics]" class="form-control" placeholder="{#ph_analytics_setting#}" value="{$settings.analytics}" />
            </div>
            <div id="submit">
                <input type="hidden" id="type" name="type" value="google">
                <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
            </div>
        </div>
    </div>
</form>