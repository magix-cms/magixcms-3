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
            <h2 class="panel-heading h5">{#setting_params#|ucfirst}</h2>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#general" aria-controls="info" role="tab" data-toggle="tab">Informations générale</a></li>
                <li role="presentation"><a href="#cssinliner" aria-controls="cssinliner" role="tab" data-toggle="tab">css inliner</a></li>
                {*<li role="presentation"><a href="#theme" aria-controls="theme" role="tab" data-toggle="tab">Thème</a></li>*}
                <li role="presentation"><a href="#google" aria-controls="google" role="tab" data-toggle="tab">Google</a></li>
            </ul>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="general">
                    {include file="setting/form/general.tpl" controller="setting"}
                </div>
                <div role="tabpanel" class="tab-pane" id="cssinliner">
                    {include file="setting/form/cssinliner.tpl" controller="setting"}
                </div>
                {*<div role="tabpanel" class="tab-pane" id="theme">
                    {include file="setting/form/skin.tpl" controller="setting"}
                </div>*}
                <div role="tabpanel" class="tab-pane" id="google">
                    {include file="setting/form/google.tpl" controller="setting"}
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