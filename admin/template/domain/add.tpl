{extends file="layout.tpl"}
{block name='head:title'}{#add_domain#|ucfirst}{/block}
{block name='body:id'}domain{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des domaines">{#domain_sitemap#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
{if {employee_access type="append" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12 col-md-8">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header">
            <h2 class="panel-heading h5">{#add_domain#|ucfirst}</h2>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <form id="add_domain" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
                <div class="row">
                    <div class="col-ph-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="url_domain">{#url_domain#|ucfirst}</label>
                                    <input type="text" class="form-control required" name="url_domain" id="url_domain" placeholder="{#ph_url_domain#|ucfirst}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>{#default_domain#|ucfirst}&nbsp;*</label>
                            <div class="radio">
                                <label for="default_1">
                                    <input type="radio" name="default_domain" id="default_1" value="1" required>
                                    {#bin_1#}
                                </label>
                                <label for="default_0">
                                    <input type="radio" name="default_domain" id="default_0" value="0" checked required>
                                    {#bin_0#}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>{#canonical_domain#|ucfirst}&nbsp;*</label>
                            <div class="radio">
                                <label for="canonical_1">
                                    <input type="radio" name="canonical_domain" id="canonical_1" value="1" required>
                                    {#bin_1#}
                                </label>
                                <label for="canonical_0">
                                    <input type="radio" name="canonical_domain" id="canonical_0" value="0" checked required>
                                    {#bin_0#}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="submit" class="col-ph-12 col-md-6">
                        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
{/if}
{/block}