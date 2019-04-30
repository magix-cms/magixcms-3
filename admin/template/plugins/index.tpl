{extends file="layout.tpl"}
{block name='head:title'}Extensions{/block}
{block name='body:id'}extensions{/block}
{block name='article:header'}
    <h1 class="h2">Extensions</h1>
{/block}
{block name='main:id'}plugins{/block}
{block name='article:content'}
    <div class="row">
        <h2 class="col-ph-12">liste des extensions installées</h2>
    {if is_array($getListPlugins) && !empty($getListPlugins)}
        {foreach $getListPlugins as $item}
            {if {employee_access type="view" class_name="plugins_{$item.name}_admin"} eq 1}
            <section class="col-ph-12 col-md-6 col-lg-3">
                <header>
                    <h2 class="h5">{$item.title}</h2>
                </header>
                <div>
                    <table class="folder-box">
                        <tbody>
                            <tr>
                                <td>
                                {if $item.translate eq '1'}
                                    <p><a href="{$url}/{baseadmin}/index.php?controller={$item.name}&amp;action=translate"><span class="fa fa-language"></span> {#translate#}</a></p>
                                {/if}
                                </td>
                                <td>
                                    <p class="text-right"><a href="{$url}/{baseadmin}/index.php?controller={$item.name}"><span class="fa fa-cog"></span> Administration</a>
                                    {if $item.uninstall eq '1'}&nbsp;<a href="#" data-id="uninstall" class="action_on_record modal_action" data-controller="{$item.name}" data-target="#uninstall_modal"><i class="fa fa-trash"></i><span class="sr-only">{#uninstall#}</span></a>{/if}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {*<div>
                    <div class="row folder-box">
                        <div class="col-xs-6">
                            *}{*<p><span class="fa fa-check"></span> Installé</p>*}{*
                            {if $item.translate eq '1'}
                            <p><a href="{$url}/{baseadmin}/index.php?controller={$item.name}&amp;action=translate"><span class="fa fa-language"></span> {#translate#}</a></p>
                            {/if}
                        </div>
                        <div class="col-xs-6">
                            <p class="text-right"><a href="{$url}/{baseadmin}/index.php?controller={$item.name}"><span class="fa fa-cog"></span> Administration</a></p>
                        </div>
                    </div>
                </div>*}
            </section>
            {/if}
        {/foreach}
    {/if}
    </div>
    <div class="row">
        <h2 class="col-ph-12">liste des extensions non installées</h2>
    {if is_array($getListPluginsNotRegister) && !empty($getListPluginsNotRegister)}
        {foreach $getListPluginsNotRegister as $item}
            <section class="col-ph-12 col-md-6 col-lg-3">
                <header>
                    <h2 class="h5">{$item.title}</h2>
                </header>
                {*<div>
                    <div class="row folder-box">
                        <div class="col-xs-6">
                            *}{*<p><span class="fa fa-check"></span> Installé</p>*}{*
                        </div>
                        <div class="col-xs-6">
                            <p class="text-right"><a href="{$url}/{baseadmin}/index.php?controller={$item.name}&amp;action=setup">Installation</a></p>
                        </div>
                    </div>
                </div>*}
                <div>
                    <table class="folder-box">
                        <tbody>
                        <tr>
                            <td>
                                {*<p><span class="fa fa-check"></span> Installé</p>*}
                            </td>
                            <td>
                                <p class="text-right"><a href="{$url}/{baseadmin}/index.php?controller={$item.name}&amp;action=setup">Installation</a></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        {/foreach}
    {/if}
    </div>
    <div class="modal fade" id="uninstall_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{#modal_uninstall_title#}</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <p><span class="fa fa-warning"></span> <strong>{#warning#}&thinsp;!</strong> {#modal_uninstall_message#}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="unsintall_form" class="unsintall_form" action="{$url}/{baseadmin}/index.php" method="get">
                        <input type="hidden" name="action" value="">
                        <input type="hidden" name="controller" value="">
                        <button type="button" class="btn btn-info" data-dismiss="modal">{#cancel#|ucfirst}</button>
                        <button type="submit" value="{$data_type}" class="btn btn-danger">{#uninstall#|ucfirst}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/block}