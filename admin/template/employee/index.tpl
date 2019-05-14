{extends file="layout.tpl"}
{block name='head:title'}{#employees#|ucfirst}{/block}
{block name='body:id'}employee{/block}

{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="pull-right">
        <p class="text-right">
            {#nbr_employee#|ucfirst}: {$employees|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="Ajouter un employé" class="btn btn-link">
                <span class="fa fa-plus"></span> {#add_employee#|ucfirst}
            </a>
        </p>
    </div>
    {/if}
    <h1 class="h2">{#employees#|ucfirst}</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#root_employees#|ucfirst}</h2>
            </header>
            <div class="panel-body">
                <div class="mc-message-container clearfix">
                    <div class="mc-message mc-message-employee">{if isset($message)}{$message}{/if}</div>
                </div>
                {include file="section/form/table-form-2.tpl" idcolumn='id_admin' data=$employees activation=true controller="employee"}
            </div>
        </section>
    </div>
    {include file="modal/delete.tpl" data_type='employee' title={#delete_employee#|ucfirst} info_text=true}
    {include file="modal/error.tpl"}
    {else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/table-form.min.js
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
        });
    </script>
{/block}