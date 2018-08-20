{extends file="layout.tpl"}
{block name='head:title'}{#edit_access#|ucfirst}{/block}
{block name='body:id'}access{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$url}/{baseadmin}/index.php?controller=access" title="Afficher la liste des rôles">{#access#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_access#|ucfirst}</h2><!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#setup" aria-controls="setup" role="tab" data-toggle="tab">Setup</a></li>
                    <li role="presentation"><a href="#perms" aria-controls="perms" role="tab" data-toggle="tab">Permissions</a></li>
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message mc-message-setup mc-message-access"></div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="setup">
                        {include file="access/form/info.tpl"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="perms">
                        {include file="access/form/list-form.tpl" controller="access" sub="perms" customClass="table-striped" data=$access id=$role.id_role}
                    </div>
                </div>
            </div>
        </section>
    </div>
    {include file="modal/delete.tpl" data_type='access'}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}

{block name="foot" append}
    {script src="/{baseadmin}/min/?f=libjs/vendor/tabcomplete.min.js,libjs/vendor/livefilter.min.js,libjs/vendor/bootstrap-select.min.js,libjs/vendor/filterlist.min.js" type="javascript"}
    {script src="/{baseadmin}/min/?f={baseadmin}/template/js/access.min.js" type="javascript"}
    <script type="text/javascript">
        $(function(){
            if (typeof access == "undefined")
            {
                console.log("access is not defined");
            }else{
                access.runEdit();
            }
        });
    </script>
{/block}