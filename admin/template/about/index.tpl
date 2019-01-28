{extends file="layout.tpl"}
{block name='head:title'}{#about#|ucfirst}{/block}
{block name='body:id'}about{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="{#root_about#|ucfirst}">{#about#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
{if {employee_access type="edit" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header panel-nav">
            <h2 class="panel-heading h5">{#root_about#|ucfirst}</h2>
            {$tab = $smarty.get.tab}
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"{if $tab == 'company' || !$tab} class="active"{/if}><a href="#info_company" aria-controls="info_company" role="tab" data-toggle="tab">{#info_company#}</a></li>
                <li role="presentation"{if $tab == 'contact'} class="active"{/if}><a href="#info_contact" aria-controls="info_contact" role="tab" data-toggle="tab">{#info_contact#}</a></li>
                <li role="presentation"{if $tab == 'socials'} class="active"{/if}><a href="#info_socials" aria-controls="info_socials" role="tab" data-toggle="tab">{#info_socials#}</a></li>
                <li role="presentation"{if $tab == 'opening'} class="active"{/if}><a href="#info_opening" aria-controls="info_opening" role="tab" data-toggle="tab">{#info_opening#}</a></li>
                <li role="presentation"{if $tab == 'text'} class="active"{/if}><a href="#info_text" aria-controls="info_text" role="tab" data-toggle="tab">{#text#}</a></li>
                <li role="presentation"{if $tab == 'page'} class="active"{/if}><a href="#info_page" aria-controls="info_page" role="tab" data-toggle="tab">{#info_page#}</a></li>
            </ul>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            {*<pre>{$companyData|print_r}</pre>*}
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane{if $tab == 'company' || !$tab} active{/if}" id="info_company">
                    {include file="about/form/company.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane{if $tab == 'contact'} active{/if}" id="info_contact">
                    {include file="about/form/contact.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane{if $tab == 'socials'} active{/if}" id="info_socials">
                    {include file="about/form/socials.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane{if $tab == 'opening'} active{/if}" id="info_opening">
                    {include file="about/form/openinghours.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane{if $tab == 'text'} active{/if}" id="info_text">
                    {include file="about/form/text.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane{if $tab == 'page'} active{/if}" id="info_page">
                    <p class="text-right">
                        {#nbr_pages#|ucfirst}: {$pages|count} <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=addpage" title="{#add_pages#}" class="btn btn-link">
                            <span class="fa fa-plus"></span> {#add_pages#|ucfirst}
                        </a>
                    </p>
                    {if $smarty.get.search}{$sortable = false}{else}{$sortable = true}{/if}
                    {*{include file="section/form/table-form-2.tpl" data=$pages idcolumn='id_pages' activation=true sortable=$sortable controller="pages"}*}
                    {include file="section/form/table-form-3.tpl" data=$pages idcolumn='id_pages' ajax_form=true activation=true sortable=$sortable controller="about" subcontroller="pages" change_offset=true}
                </div>
            </div>
        </div>
    </section>
</div>
{include file="modal/delete.tpl" data_type='pages' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_pages_message#}}
{include file="modal/error.tpl"}
{else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/table-form.min.js,
        {baseadmin}/template/js/about.min.js
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
            if (typeof about == "undefined")
            {
                console.log("about is not defined");
            }else{
                about.run();
            }
        });
    </script>
{/block}