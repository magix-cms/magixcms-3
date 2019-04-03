{extends file="layout.tpl"}
{block name="stylesheets" append}
    {capture name="cssColorpicker"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/css/bootstrap-colorpicker.min.css
    {/strip}{/capture}
    {headlink rel="stylesheet" href=$smarty.capture.cssColorpicker media="screen"}
{/block}
{block name='head:title'}{#logo#|ucfirst}{/block}
{block name='body:id'}logo{/block}

{block name='article:header'}
    <h1 class="h2">{#logo#|ucfirst}</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_logo#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#image" data-toggle="tab">{#logo#}</a></li>
                    <li role="presentation"><a data-toggle="tab" href="#image_default">{#image_placeholder#}</a></li>
                    <li role="presentation"><a data-toggle="tab" href="#favicon">favicon</a></li>
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="image">
                        {include file="logo/form/img.tpl" controller="logo"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="image_default">
                        {include file="logo/form/holder.tpl" controller="logo"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="favicon">
                        {include file="logo/form/favicon.tpl" controller="logo"}
                    </div>
                </div>
                {*<pre>{$page|print_r}</pre>*}
            </div>
        </section>
    </div>
    {include file="modal/delete.tpl" data_type='logo' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_pages_message#}}
    {include file="modal/error.tpl"}
    {/if}
{/block}
{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        libjs/vendor/tabcomplete.min.js,
        libjs/vendor/livefilter.min.js,
        libjs/vendor/src/bootstrap-select.js,
        libjs/vendor/filterlist.min.js,
        {baseadmin}/template/js/table-form.min.js,
        {baseadmin}/template/js/img-drop.min.js,
        libjs/vendor/bootstrap-colorpicker.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function(){
            var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run(controller);
            }
            $('.csspicker').colorpicker();
        });
    </script>
{/block}