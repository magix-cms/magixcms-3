{extends file="layout.tpl"}
{block name="stylesheets" append}
    {capture name="cssDatePicker"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/css/bootstrap-datetimepicker.min.css
    {/strip}{/capture}
    {headlink rel="stylesheet" href=$smarty.capture.cssDatePicker media="screen"}
{/block}
{block name='head:title'}{#add_news#|ucfirst}{/block}
{block name='body:id'}news{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des actualités">{#news#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-ph-12 col-md-8">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">{#add_news#|ucfirst}</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {include file="news/form/add.tpl" controller="news"}
                </div>
            </section>
        </div>
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/moment.min.js,
        libjs/vendor/datetimepicker/{iso}.js,
        libjs/vendor/bootstrap-datetimepicker.min.js,
        {baseadmin}/template/js/news.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function(){
            if (typeof news == "undefined")
            {
                console.log("news is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
                news.run(controller,iso);
            }
        });
    </script>
{/block}