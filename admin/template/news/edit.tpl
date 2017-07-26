{extends file="layout.tpl"}
{block name="stylesheets" append}
    {capture name="cssDatePicker"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/css/bootstrap-datetimepicker.min.css
    {/strip}{/capture}
    {headlink rel="stylesheet" href=$smarty.capture.cssDatePicker media="screen"}
{/block}
{block name='head:title'}{#edit_news#|ucfirst}{/block}
{block name='body:id'}news{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des actualités">{#news#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-xs-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_news#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#text#}</a></li>
                    <li role="presentation"><a href="#image" aria-controls="image" role="tab" data-toggle="tab">{#image#}</a></li>
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    {*<pre>{$page|print_r}</pre>*}
                    <div role="tabpanel" class="tab-pane active" id="general">
                        {include file="news/form/edit.tpl" controller="news"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="image">
                        {include file="news/form/img.tpl" controller="news"}
                        <div class="row">
                            <div class="block-img">
                            {if $page.imgSrc != null}
                            {include file="news/brick/img.tpl"}
                            {/if}
                            </div>
                        </div>
                    </div>
                </div>
                {*<pre>{$page|print_r}</pre>*}
            </div>
        </section>
    </div>
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/vendor/typeahead.bundle.js,
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