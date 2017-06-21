{extends file="layout.tpl"}
{block name='head:title'}{#edit_pages#|ucfirst}{/block}
{block name='body:id'}pages{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des pages">{#pages#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-xs-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_pages#|ucfirst}</h2>
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
                    <div role="tabpanel" class="tab-pane active" id="general">
                        {include file="pages/form/content.tpl" controller="pages"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="image"></div>
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
        {baseadmin}/template/js/pages.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof pages == "undefined")
            {
                console.log("pages is not defined");
            }else{
                pages.run();
            }
        });
    </script>

{/block}