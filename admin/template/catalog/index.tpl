{extends file="layout.tpl"}
{block name='head:title'}{#catalog#|ucfirst}{/block}
{block name='body:id'}catalog{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="{#catalog#|ucfirst}">{#catalog#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-ph-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">{#catalog#|ucfirst}</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {include file="language/brick/dropdown-lang.tpl"}
                    <div class="row">
                        <form id="edit_company_text" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-md-10">
                            <div class="tab-content">
                                {foreach $langs as $id => $iso}
                                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                                        <div class="row">
                                            <div class="col-ph-12 col-sm-8">
                                                <div class="form-group">
                                                    <label for="content[{$id}][catalog_name]">{#catalog_name#|ucfirst} :</label>
                                                    <input type="text" class="form-control" id="content[{$id}][catalog_name]" name="content[{$id}][catalog_name]" value="{$contentData.{$id}.name}" size="50" placeholder="{#catalog_name_ph#|ucfirst}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="content[{$id}][catalog_content]">{#content#|ucfirst} :</label>
                                            <textarea name="content[{$id}][catalog_content]" id="content[{$id}][catalog_content]" class="form-control mceEditor">{call name=cleantextarea field=$contentData.{$id}.content}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn collapsed btn-collapse" role="button" data-toggle="collapse" data-parent="#accordion" href="#metas-{$id}" aria-expanded="true" aria-controls="metas-{$id}">
                                                <span class="fa"></span> {#display_metas#|ucfirst}
                                            </button>
                                        </div>

                                        <div id="metas-{$id}" class="collapse" role="tabpanel" aria-labelledby="heading{$id}">
                                            <div class="row">
                                                <div class="col-ph-12 col-sm-8">
                                                    <div class="form-group">
                                                        <label for="content[{$id}][seo_title]">{#title#|ucfirst} :</label>
                                                        <textarea class="form-control" id="content[{$id}][seo_title]" name="content[{$id}][seo_title]" cols="70" rows="3">{$contentData[{$id}].seo_title}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-ph-12 col-sm-8">
                                                    <div class="form-group">
                                                        <label for="content[{$id}][seo_desc]">Description :</label>
                                                        <textarea class="form-control" id="content[{$id}][seo_desc]" name="content[{$id}][seo_desc]" cols="70" rows="3">{$contentData[{$id}].seo_desc}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                {/foreach}
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
    {include file="section/footer/editor.tpl"}
{/block}