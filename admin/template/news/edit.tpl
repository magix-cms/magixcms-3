{extends file="layout.tpl"}
{block name="stylesheets" append}
    {capture name="cssDatePicker"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/css/bootstrap-datetimepicker.min.css
    {/strip}{/capture}
    {headlink rel="stylesheet" href=$smarty.capture.cssDatePicker media="screen"}
{/block}
{block name='head:title'}{#edit_news#|ucfirst}{/block}
{block name='body:id'}news{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des actualités">{#news#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_news#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" {if !$smarty.get.plugin}class="active"{/if}><a href="{if $smarty.get.plugin}{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_news}{/if}#general" aria-controls="general" role="tab" {if !$smarty.get.plugin}data-toggle="tab"{/if}>{#text#}</a></li>
                    <li role="presentation"><a href="{if $smarty.get.plugin}{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_news}{/if}#image" aria-controls="image" role="tab" {if !$smarty.get.plugin}data-toggle="tab"{/if}>{#image#}</a></li>
                    {foreach $setTabsPlugins as $key => $value}
                        <li role="presentation" {if $smarty.get.plugin eq $value.name}class="active"{/if}><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_news}&plugin={$value.name}" aria-controls="plugins-{$value.name}" role="tab">{$value.name}</a></li>
                    {/foreach}
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    {*<pre>{$page|print_r}</pre>*}
                    <div role="tabpanel" class="tab-pane {if !$smarty.get.plugin}active{/if}" id="general">
                        {include file="news/form/edit.tpl" controller="news"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="image">
                        {include file="news/form/img.tpl" controller="news"}
                        {*<div class="block-img">
                            {if $page.imgSrc != null}
                                {include file="news/brick/img.tpl"}
                            {/if}
                        </div>*}
                    </div>
                    {foreach $setTabsPlugins as $key => $value}
                        <div role="tabpanel" class="tab-pane {if $smarty.get.plugin eq $value.name}active{/if}" id="plugins-{$value.name}">
                            {block name="plugin:content"}{/block}
                        </div>
                    {/foreach}
                </div>
                {*<pre>{$page|print_r}</pre>*}
            </div>
        </section>
    </div>
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/vendor/typeahead.bundle.js,
        libjs/vendor/moment.min.js,
        libjs/vendor/datetimepicker/{iso}.js,
        libjs/vendor/bootstrap-datetimepicker.min.js,
        {baseadmin}/template/js/img-drop.min.js,
        {baseadmin}/template/js/news.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function(){
            if (typeof news == "undefined")
            {
                console.log("news is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
                news.run(controller,iso);
                news.runEdit(controller);
            }
        });
    </script>
{/block}