{extends file="layout.tpl"}
{block name='head:title'}{#news#|ucfirst}{/block}
{block name='body:id'}news{/block}

{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="pull-right">
        <p class="text-right">
            {#nbr_news#|ucfirst}: {$news|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_news#}" class="btn btn-link">
                <span class="fa fa-plus"></span> {#add_news#|ucfirst}
            </a>
        </p>
    </div>
    {/if}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des news">{#news#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#root_news#|ucfirst}</h2>
            </header>
            <div class="panel-body">
                <div class="mc-message-container clearfix">
                    <div class="mc-message mc-message-news">{if isset($message)}{$message}{/if}</div>
                </div>
                {include file="section/form/table-form-3.tpl" data=$news idcolumn='id_news' activation=false controller="news" change_offset=true}
            </div>
        </section>
    </div>
    {include file="modal/delete.tpl" data_type='news' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_news_message#}}
    {include file="modal/error.tpl"}
    {else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/table-form.min.js,
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
                news.run(controller);
            }
        });
    </script>
{/block}