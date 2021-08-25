{extends file="layout.tpl"}
{block name="stylesheets" append}
    {capture name="cssColorpicker"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/css/bootstrap-colorpicker.min.css
    {/strip}{/capture}
    {headlink rel="stylesheet" href=$smarty.capture.cssColorpicker media="screen"}
{/block}
{block name='head:title'}{#setting#|ucfirst}{/block}
{block name='body:id'}setting{/block}

{block name='article:header'}
    <h1 class="h2">{#setting#|ucfirst}</h1>
{/block}
{block name='article:content'}
{if {employee_access type="edit" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header panel-nav">
            <h2 class="panel-heading h5">{#setting_params#}</h2>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"{if (isset($smarty.get.tab) && $smarty.get.tab === 'general') || !isset($smarty.get.tab)} class="active"{/if}><a href="#general" aria-controls="info" role="tab" data-toggle="tab">{#general_setting#}</a></li>
                <li role="presentation"{if isset($smarty.get.tab) && $smarty.get.tab === 'seo'} class="active"{/if}><a href="#seo" aria-controls="google" role="tab" data-toggle="tab">{#seo_setting#}</a></li>
                <li role="presentation"{if isset($smarty.get.tab) && $smarty.get.tab === 'email'} class="active"{/if}><a href="#email" aria-controls="email" role="tab" data-toggle="tab">{#email_setting#}</a></li>
                <li role="presentation"{if isset($smarty.get.tab) && $smarty.get.tab === 'advanced'} class="active"{/if}><a href="#advanced" aria-controls="advanced" role="tab" data-toggle="tab">{#advanced_setting#}</a></li>
            </ul>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane{if (isset($smarty.get.tab) && $smarty.get.tab === 'general') || !isset($smarty.get.tab)} active{/if}" id="general">
                    {include file="setting/form/general.tpl" controller="setting"}
                </div>
                <div role="tabpanel" class="tab-pane{if isset($smarty.get.tab) && $smarty.get.tab === 'seo'} active{/if}" id="seo">
                    {include file="setting/form/seo.tpl" controller="setting"}
                </div>
                <div role="tabpanel" class="tab-pane{if isset($smarty.get.tab) && $smarty.get.tab === 'email'} active{/if}" id="email">
                    {include file="setting/form/email.tpl" controller="setting"}
                    {include file="setting/form/cssinliner.tpl" controller="setting"}
                </div>
                <div role="tabpanel" class="tab-pane{if isset($smarty.get.tab) && $smarty.get.tab === 'advanced'} active{/if}" id="advanced">
                    {include file="setting/form/advanced.tpl" controller="setting"}
                </div>
            </div>
        </div>
        {*<pre>{$settings|print_r}</pre>*}
    </section>
</div>
{else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}
{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/bootstrap-colorpicker.min.js,
        {baseadmin}/template/js/fancybox.init.min.js,
        {baseadmin}/template/js/setting.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof setting == "undefined")
            {
                console.log("setting is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
                var btnData = ['Choisir','Sélectionné'];
                setting.run(controller,btnData);
            }
        });
    </script>
{/block}