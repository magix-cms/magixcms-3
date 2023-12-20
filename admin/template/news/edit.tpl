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
                    <li role="presentation" {if !$smarty.get.plugin && !$smarty.get.tab}class="active"{/if}><a href="{if $smarty.get.plugin}{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_news}{else}#general{/if}" aria-controls="general" role="tab" {if !$smarty.get.plugin}data-toggle="tab"{/if}>{#text#}</a></li>
                    <li role="presentation" {if !$smarty.get.plugin && $smarty.get.tab === 'images'}class="active"{/if}><a href="{if $smarty.get.plugin}{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}&tab=images{else}#images{/if}" aria-controls="images" role="tab" {if !$smarty.get.plugin}data-toggle="tab"{/if}>{#images#|ucfirst}</a></li>
                    {foreach $setTabsPlugins as $key => $value}
                        <li role="presentation" {if $smarty.get.plugin eq $value.name}class="active"{/if}><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_news}&plugin={$value.name}" aria-controls="plugins-{$value.name}" role="tab">{$value.title|ucfirst}</a></li>
                    {/foreach}
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    {*<pre>{$page|print_r}</pre>*}
                    {if !$smarty.get.plugin}
                    <div role="tabpanel" class="tab-pane{if !$smarty.get.plugin && !$smarty.get.tab} active{/if}" id="general">
                        {include file="news/form/edit.tpl" controller="news"}
                    </div>
                    <div role="tabpanel" class="tab-pane{if !$smarty.get.plugin && $smarty.get.tab === 'images'} active{/if}" id="images">
                        {include file="news/form/img.tpl" controller="news"}
                        {*<div class="block-img">
                            {if $page.imgSrc != null}
                                {include file="news/brick/img.tpl"}
                            {/if}
                        </div>*}
                        <div id="gallery-pages" class="block-img">
                            {if $images != null}
                                {include file="news/brick/img.tpl"}
                            {/if}
                        </div>
                    </div>
                    {/if}
                    {foreach $setTabsPlugins as $key => $value}
                        <div role="tabpanel" class="tab-pane {if $smarty.get.plugin eq $value.name}active{/if}" id="plugins-{$value.name}">
                            {if $smarty.get.plugin eq $value.name}{block name="plugin:content"}{/block}{/if}
                        </div>
                    {/foreach}
                </div>
                {*<pre>{$page|print_r}</pre>*}
            </div>
        </section>
    </div>
        {include file="modal/delete.tpl" data_type='news' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_pages_message#}}
        {include file="modal/error.tpl"}
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/vendor/typeahead.bundle.js,
        libjs/vendor/moment.min.js,
        libjs/vendor/datetimepicker/{iso}.js,
        libjs/vendor/bootstrap-datetimepicker.min.js,
        libjs/vendor/progressBar.min.js,
        {baseadmin}/template/js/table-form.min.js,
        libjs/vendor/tabcomplete.min.js,
        {baseadmin}/template/js/img-drop.min.js,
        {baseadmin}/template/js/news.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function(){
            var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run(controller);
            }
            if (typeof news == "undefined")
            {
                console.log("news is not defined");
            }else{
                var edit = "{$smarty.get.edit}";
                news.run(controller,iso);
                news.runEdit(controller,globalForm,tableForm,edit);
            }
        });
    </script>
{/block}