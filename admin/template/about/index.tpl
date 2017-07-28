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
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#info_company" aria-controls="info_company" role="tab" data-toggle="tab">{#info_company#}</a></li>
                <li role="presentation"><a href="#info_contact" aria-controls="info_contact" role="tab" data-toggle="tab">{#info_contact#}</a></li>
                <li role="presentation"><a href="#info_socials" aria-controls="info_socials" role="tab" data-toggle="tab">{#info_socials#}</a></li>
                <li role="presentation"><a href="#info_opening" aria-controls="info_opening" role="tab" data-toggle="tab">{#info_opening#}</a></li>
                <li role="presentation"><a href="#info_text" aria-controls="info_text" role="tab" data-toggle="tab">{#text#}</a></li>
                <li role="presentation"><a href="#info_page" aria-controls="info_page" role="tab" data-toggle="tab">{#info_page#}</a></li>
            </ul>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            {*<pre>{$companyData|print_r}</pre>*}
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="info_company">
                    {include file="about/form/company.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane" id="info_contact">
                    {include file="about/form/contact.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane" id="info_socials">
                    {include file="about/form/socials.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane" id="info_opening">
                    {include file="about/form/openinghours.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane" id="info_text">
                    {include file="about/form/text.tpl"}
                </div>
                <div role="tabpanel" class="tab-pane" id="info_page"></div>
            </div>
        </div>
    </section>
</div>
{else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/about.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof about == "undefined")
            {
                console.log("about is not defined");
            }else{
                about.run({baseadmin});
            }
        });
    </script>
{/block}