{extends file="layout.tpl"}
{block name='head:title'}{#access#|ucfirst}{/block}
{block name='body:id'}access{/block}

{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="pull-right">
        <p class="text-right">
            {#nbr_role#|ucfirst}: {$roles|count}<a href="#" data-toggle="modal" data-target="#add_modal" title="Ajouter des rôles" class="btn btn-link">
                <span class="fa fa-plus"></span> {#add_role#|ucfirst}
            </a>
        </p>
    </div>
    {/if}
    <h1 class="h2">{#access#|ucfirst}</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#root_access#|ucfirst}</h2>
            </header>
            <div class="panel-body">
                <div class="mc-message-container clearfix">
                    <div class="mc-message mc-message-access">{if isset($message)}{$message}{/if}</div>
                </div>
                {include file="section/form/table-form-2.tpl" idcolumn='id_role' data=$roles controller="access" readonly=[1]}
            </div>
        </section>
    </div>
    {include file="access/modal/add.tpl"}
    {include file="modal/delete.tpl" data_type='access'}
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