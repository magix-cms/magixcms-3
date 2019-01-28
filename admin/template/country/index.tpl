{extends file="layout.tpl"}
{block name='head:title'}{#country#|ucfirst}{/block}
{block name='body:id'}country{/block}

{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="pull-right">
        <p class="text-right">
            {#nbr_country#|ucfirst}: {$countries|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_country#}" class="btn btn-link">
                <span class="fa fa-plus"></span> {#add_country#|ucfirst}
            </a>
        </p>
    </div>
    {/if}
    <h1 class="h2">{#country#|ucfirst}</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#root_country#|ucfirst}</h2>
            </header>
            <div class="panel-body">
                <div class="mc-message-container clearfix">
                    <div class="mc-message mc-message-country">{if isset($message)}{$message}{/if}</div>
                </div>
                {include file="section/form/table-form-2.tpl" idcolumn='id_country' data=$countries activation=false sortable=true controller="country" change_offset=true}
            </div>
        </section>
    </div>
    {include file="modal/delete.tpl" data_type='country' title={#delete_country#|ucfirst} info_text=true}
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
        {baseadmin}/template/js/country.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run();
            }
            if (typeof country == "undefined")
            {
                console.log("country is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
                country.run(controller);
            }
        });
    </script>
{/block}