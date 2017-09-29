{extends file="layout.tpl"}
{block name='head:title'}contact{/block}
{block name='body:id'}contact{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="pull-right">
            <p class="text-right">
                {#nbr_contact#|ucfirst}: {$contact|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_country#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_contact#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2">contact</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-ph-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header panel-nav">
                    <h2 class="panel-heading h5">Gestion des contacts</h2>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">Mail</a></li>
                        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuration</a></li>
                    </ul>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {*<pre>{$pages|print_r}</pre>*}
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="general">
                            {include file="section/form/table-form-2.tpl" idcolumn='id_contact' data=$contact activation=false sortable=false controller="contact"}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="config">

                        </div>
                    </div>
                </div>
            </section>
        </div>
        {include file="modal/delete.tpl" data_type='address' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_contact_message#}}
        {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}