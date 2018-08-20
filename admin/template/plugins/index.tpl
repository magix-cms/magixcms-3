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
                                    <p class="text-right"><a href="{$url}/{baseadmin}/index.php?controller={$item.name}"><span class="fa fa-cog"></span> Administration</a></p>
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
{/block}