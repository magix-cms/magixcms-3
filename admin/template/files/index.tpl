{extends file="layout.tpl"}
{block name='head:title'}{#files_configuration#|ucfirst}{/block}
{block name='body:id'}files{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des tailles d'image">{#files#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
{if {employee_access type="edit" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header panel-nav">
            <h2 class="panel-heading h5">{#files_params#|ucfirst}</h2>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#imagesize" aria-controls="imagesize" role="tab" data-toggle="tab">Tailles des images</a></li>
                <li role="presentation"><a href="#thumbnail_manager" aria-controls="thumbnail_manager" role="tab" data-toggle="tab">Gestionnaire de miniatures</a></li>
            </ul>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane tab-table active" id="imagesize">
                    <p class="text-right">
                        <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_imagesize#}" class="btn btn-link">
                            <span class="fa fa-plus"></span> {#add_imagesize#|ucfirst}
                        </a>
                    </p>
                    {include file="section/form/table-form-2.tpl" idcolumn='id_config_img' data=$sizes activation=false sortable=false controller="files"}
                </div>
                <div role="tabpanel" class="tab-pane" id="thumbnail_manager">
                    {include file="files/form/thumbnail.tpl"}
                </div>
            </div>
        </div>
    </section>
</div>
    {include file="modal/delete.tpl" data_type='files' title={#delete_imagesize#|ucfirst} info_text=true}
    {include file="modal/error.tpl"}
{else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}
{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/progressBar.min.js,
        {baseadmin}/template/js/files.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof files == "undefined")
            {
                console.log("files is not defined");
            }else{
                files.run();
            }
        });
    </script>
{/block}