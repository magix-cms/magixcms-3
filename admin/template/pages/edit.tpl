{extends file="layout.tpl"}
{block name='head:title'}{#edit_pages#|ucfirst}{/block}
{block name='body:id'}pages{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des pages">{#pages#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_pages#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" {if !$smarty.get.plugin && !$smarty.get.tab}class="active"{/if}><a href="{if $smarty.get.plugin}{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}{else}#general{/if}" aria-controls="general" role="tab" {if !$smarty.get.plugin}data-toggle="tab"{/if}>{#text#}</a></li>
                    <li role="presentation" {if !$smarty.get.plugin && $smarty.get.tab === 'images'}class="active"{/if}><a href="{if $smarty.get.plugin}{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}&tab=images{else}#images{/if}" aria-controls="images" role="tab" {if !$smarty.get.plugin}data-toggle="tab"{/if}>{#images#|ucfirst}</a></li>
                    <li role="presentation" {if !$smarty.get.plugin && $smarty.get.tab === 'child'}class="active"{/if}><a href="{if $smarty.get.plugin}{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}&tab=child{else}#child{/if}" aria-controls="child" role="tab" {if !$smarty.get.plugin}data-toggle="tab"{/if}>{#child_page#|ucfirst}</a></li>
                    {foreach $setTabsPlugins as $key => $value}
                        <li role="presentation" {if $smarty.get.plugin eq $value.name}class="active"{/if}><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}&plugin={$value.name}" aria-controls="plugins-{$value.name}" role="tab">{$value.title|ucfirst}</a></li>
                    {/foreach}
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane{if !$smarty.get.plugin && !$smarty.get.tab} active{/if}" id="general">
                        {include file="pages/form/edit.tpl" controller="pages"}
                    </div>
                    <div role="tabpanel" class="tab-pane{if !$smarty.get.plugin && $smarty.get.tab === 'images'} active{/if}" id="images">
                        {include file="pages/form/img.tpl" controller="pages"}
                        <div id="gallery-pages" class="block-img">
                            {if $images != null}
                                {include file="pages/brick/img.tpl"}
                            {/if}
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane tab-table{if !$smarty.get.plugin && $smarty.get.tab === 'child'} active{/if}" id="child">
                        <p class="text-right">
                            {#nbr_pages#|ucfirst}: {$pagesChild|count} <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add&parent_id={$smarty.get.edit}" title="{#add_pages#}" class="btn btn-link">
                                <span class="fa fa-plus"></span> {#add_pages#|ucfirst}
                            </a>
                        </p>
                        {if $smarty.get.search}{$sortable = false}{else}{$sortable = true}{/if}
                        {include file="section/form/table-form-3.tpl" ajax_form=true idcolumn='id_pages' data=$pagesChild activation=true sortable=$sortable controller="pages"}
                    </div>
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
    {include file="modal/delete.tpl" data_type='pages' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_pages_message#}}
    {include file="modal/error.tpl"}
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        libjs/vendor/progressBar.min.js,
        libjs/vendor/tabcomplete.min.js,
        libjs/vendor/livefilter.min.js,
        libjs/vendor/src/bootstrap-select.js,
        libjs/vendor/filterlist.min.js,
        {baseadmin}/template/js/table-form.min.js,
        {baseadmin}/template/js/img-drop.min.js,
        {baseadmin}/template/js/pages.min.js
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
            if (typeof pages === "undefined")
            {
                console.log("pages is not defined");
            }else{
                //pages.run(controller);
                pages.runAdd(controller);
                var edit = "{$smarty.get.edit}";
                pages.runEdit(globalForm,tableForm,edit);
            }
        });
    </script>
{/block}