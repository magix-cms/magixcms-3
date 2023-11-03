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
    <h1 class="h2">Contact</h1>
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
                        <li role="presentation"{if !$smarty.get.plugin} class="active"{/if}><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#text#}</a></li>
                        <li role="presentation"><a href="#mail" aria-controls="mail" role="tab" data-toggle="tab">Mail</a></li>
                        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuration</a></li>
                        {foreach $setTabsPlugins as $value}
                            <li role="presentation" {if $smarty.get.plugin eq $value.name}class="active"{/if}><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&plugin={$value.name}" aria-controls="plugins-{$value.name}" role="tab">{$value.title|ucfirst}</a></li>
                        {/foreach}
                    </ul>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {*<pre>{$pages|print_r}</pre>*}
                    <div class="tab-content">
                        {if !$smarty.get.plugin}
                        <div role="tabpanel" class="tab-pane active" id="general">
                            {include file="form/content.tpl" controller="contact"}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="mail">
                            {include file="section/form/table-form-3.tpl" idcolumn='id_contact' data=$contact activation=false sortable=false controller="contact"}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="config">
                            <div class="row">
                                <form id="edit_config" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$config.id_config}" method="post" class="validate_form edit_form col-ph-12 col-md-4">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="address_enabled">{#address_enabled#|ucfirst}</label>
                                                <input id="address_enabled" data-toggle="toggle" type="checkbox" name="address_enabled" data-toggle="toggle" type="checkbox" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if $config.address_enabled} checked{/if}>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="address_required">{#address_required#|ucfirst}</label>
                                                <input id="address_required" data-toggle="toggle" type="checkbox" name="address_required" data-toggle="toggle" type="checkbox" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if $config.address_required} checked{/if}>
                                            </div>
                                        </div>
                                    </div>
                                    {*<div>
                                        <div class="form-group">
                                            <label for="mail_sender">{#mail_sender#|ucfirst}&nbsp;</label>
                                            <input type="text" name="mail_sender" id="mail_sender" class="form-control" placeholder="{#mail_sender#}" value="{$config.mail_sender}" />
                                        </div>
                                    </div>*}
                                    <div id="submit">
                                        <input type="hidden" id="id_config" name="id_config" value="{$config.id_config}">
                                        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {/if}
                        {foreach $setTabsPlugins as $value}{if $smarty.get.plugin eq $value.name}
                        <div role="tabpanel" class="tab-pane active" id="plugins-{$value.name}">
                            {if $smarty.get.plugin eq $value.name}{block name="plugin:content"}{/block}{/if}
                            </div>{/if}
                        {/foreach}
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

{block name="foot" append}
    {include file="section/footer/editor.tpl"}
{/block}