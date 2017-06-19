{extends file="layout.tpl"}
{block name='head:title'}{#edit_home#|ucfirst}{/block}
{block name='body:id'}home{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="{#edit_home#|ucfirst}">{#root_home#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-xs-12 col-md-8">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#edit_home#|ucfirst}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                    <label for="id_lang">{#language#|ucfirst} *</label>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {foreach $langs as $id => $iso}
                                {if $iso@first}{$default = $id}{break}{/if}
                            {/foreach}
                            <span class="lang">{$langs[$default]}</span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            {foreach $langs as $id => $iso}
                                <li role="presentation"{if $iso@first} class="active"{/if}>
                                    <a href="#lang-{$id}" aria-controls="lang-{$id}" role="tab" data-toggle="tab">{$iso}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <form id="edit_home" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-xs-12 col-md-10">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                            <div class="tab-content">
                                {foreach $langs as $id => $iso}
                                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-8">
                                                <div class="form-group">
                                                    <label for="content[{$id}][title_page]">{#title#|ucfirst} *:</label>
                                                    <input type="text" class="form-control" id="content[{$id}][title_page]" name="content[{$id}][title_page]" value="{$page.content[{$id}].title_page}" size="50" />
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group">
                                                    <label for="content[{$id}][published]">Statut</label>
                                                    <input id="content[{$id}][published]" data-toggle="toggle" type="checkbox" name="content[{$id}][published]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($page) && $iso@first) || $page.content[{$id}].published} checked{/if}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="content[{$id}][content_page]">{#content#|ucfirst} :</label>
                                                    <textarea name="content[{$id}][content_page]" id="content[{$id}][content_page]" class="form-control mceEditor">{cleanTextarea field=$page.content[{$id}].content_page}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        {*<p>
                                            <a href="#metas-{$id}" class="btn btn-default view-metas">
                                                <span class="fa fa-plus"></span> {#display_metas#|ucfirst}
                                            </a>
                                        </p>*}
                                        <div class="form-group">
                                            <button class="btn collapsed btn-collapse" role="button" data-toggle="collapse" data-parent="#accordion" href="#metas-{$id}" aria-expanded="true" aria-controls="metas-{$id}">
                                                <span class="fa"></span> {#display_metas#|ucfirst}
                                            </button>
                                        </div>
                                        {*<div class="collapse-metas row" id="metas-{$id}">*}
                                        <div id="metas-{$id}" class="collapse" role="tabpanel" aria-labelledby="heading{$id}">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-8">
                                                    <div class="form-group">
                                                        <label for="content[{$id}][seo_title_page]">{#title#|ucfirst} :</label>
                                                        <textarea class="form-control" id="content[{$id}][seo_title_page]" name="content[{$id}][seo_title_page]" cols="70" rows="3">{$page.content[{$id}].seo_title_page}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            <div class="col-xs-12 col-sm-8">
                                                <div class="form-group">
                                                        <label for="content[{$id}][seo_desc_page]">Description :</label>
                                                        <textarea class="form-control" id="content[{$id}][seo_desc_page]" name="content[{$id}][seo_desc_page]" cols="70" rows="3">{$page.content[{$id}].seo_desc_page}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                {/foreach}
                            </div>
                            </div>
                        </div>
                        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    {/if}
{/block}
{block name="foot" append}
    {capture name="scriptTinyMCE"}{strip}
        /{baseadmin}/min/?g=tinymce
    {/strip}{/capture}

    {script src=$smarty.capture.scriptTinyMCE type="javascript"}
    <script type="text/javascript">
        {capture name="tinyMCEstyleSheet"}/{baseadmin}/template/css/tinymce-content.css,{/capture}
        content_css = "{$smarty.capture.tinyMCEstyleSheet}";
    </script>
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/tinymce-config.min.js,
        {baseadmin}/template/js/home.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof home == "undefined")
            {
                console.log("home is not defined");
            }else{
                home.run();
            }
        });
    </script>
{/block}