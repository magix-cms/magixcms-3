{extends file="layout.tpl"}
{block name='head:title'}{#appearance#|ucfirst}{/block}
{block name='body:id'}appearance{/block}

{block name='article:header'}
    <h1 class="h2">{#appearance#|ucfirst}</h1>
{/block}
{block name='article:content'}
{if {employee_access type="edit" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header panel-nav">
            <h2 class="panel-heading h5">{#appearance#|ucfirst}</h2>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="#theme" aria-controls="theme" role="tab" data-toggle="tab">Thème</a></li>
                <li role="presentation" class="active"><a href="#theme-nav" aria-controls="theme" role="tab" data-toggle="tab">Menu</a></li>
            </ul>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane" id="theme">
                    {include file="theme/form/skin.tpl" controller="theme"}
                </div>
                <div role="tabpanel" class="tab-pane active" id="theme-nav">
                    {include file="theme/form/menu.tpl" controller="theme"}
                </div>
            </div>
        </div>
        {*<pre>{$settings|print_r}</pre>*}
    </section>
</div>
{include file="modal/delete.tpl" data_type='theme' info_text=true}
{else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}
{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        libjs/vendor/tabcomplete.min.js,
        libjs/vendor/livefilter.min.js,
        libjs/vendor/bootstrap-select.min.js,
        libjs/vendor/filterlist.min.js,
        {baseadmin}/template/js/fancybox.init.min.js,
        {baseadmin}/template/js/theme.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof theme == "undefined")
            {
                console.log("setting is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
                var btnData = ['Choisir','Sélectionné'];
                theme.run(controller,btnData);
            }
        });
    </script>
{/block}