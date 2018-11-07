{extends file="layout.tpl"}
{block name='head:title'}{#edit_domain#|ucfirst}{/block}
{block name='body:id'}domain{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des domaines">{#domain_sitemap#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
{if {employee_access type="append" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header panel-nav">
            <h2 class="panel-heading h5">{#edit_domain#|ucfirst}</h2>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#general" aria-controls="info" role="tab" data-toggle="tab">{#domain#|ucfirst}</a></li>
                <li role="presentation"><a href="#sitemap" aria-controls="sitemap" role="tab" data-toggle="tab">Sitemap</a></li>
                <li role="presentation"><a href="#langs" aria-controls="langs" role="tab" data-toggle="tab">Langues</a></li>
            </ul>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="general">
                    {include file="domain/form/domain.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane" id="sitemap">
                    {include file="domain/form/sitemap.tpl"}
                    <div id="sitemap-url">{include file="domain/loop/sitemap.tpl" data=$xmlItems}</div>
                </div>
                <div role="tabpanel" class="tab-pane" id="langs">
                    {include file="section/form/list-form.tpl" controller="domain" sub="langs" dir_controller="domain" data=$langs id=$domain.id_domain class_form="col-ph-12 col-lg-5" class_table="col-ph-12 col-lg-7"}
                </div>
            </div>
        </div>
    </section>
</div>
    {include file="modal/delete.tpl" title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_img_message#}}
{/if}
{/block}
{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/progressBar.min.js,
        {baseadmin}/template/js/domain.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof domain == "undefined")
            {
                console.log("domain is not defined");
            }else{
                domain.run();
            }
        });
    </script>
{/block}