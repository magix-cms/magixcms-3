{extends file="layout.tpl"}
{block name='head:title'}{#edit_home#|ucfirst}{/block}
{block name='body:id'}home{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="{#edit_home#|ucfirst}">{#root_home#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_home#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" {if !$smarty.get.plugin}class="active"{/if}><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}{if !$smarty.get.plugin}#general{/if}" aria-controls="general" role="tab" {if !$smarty.get.plugin}data-toggle="tab"{/if}>{#text#}</a></li>
                    {foreach $setTabsPlugins as $key => $value}
                    <li role="presentation" {if $smarty.get.plugin eq $value.name}class="active"{/if}><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&plugin={$value.name}" aria-controls="plugins-{$value.name}" role="tab">{$value.name}</a></li>
                    {/foreach}
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                {*<pre>{$setCorePlugins|print_r}</pre>*}
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane {if !$smarty.get.plugin}active{/if}" id="general">
                        {include file="home/form/edit.tpl" controller="home"}
                    </div>
                    {foreach $setTabsPlugins as $key => $value}
                    <div role="tabpanel" class="tab-pane {if $smarty.get.plugin eq $value.name}active{/if}" id="plugins-{$value.name}">
                        {block name="plugin:content"}{/block}
                    </div>
                    {/foreach}
                </div>
            </div>
        </section>
    </div>
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/home.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof home == "undefined")
            {
                console.log("home is not defined");
            }else{
                home.run();
            }
        });
    </script>
{/block}