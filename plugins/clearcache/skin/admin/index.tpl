{extends file="layout.tpl"}
{block name='head:title'}{$smarty.get.controller}{/block}
{block name='body:id'}{$smarty.get.controller}{/block}
{block name='article:header'}
    <h1 class="h2">{$smarty.get.controller|ucfirst}</h1>
{/block}
{block name='article:content'}
    {assign var="collectionformCache" value=[
        "public"=>"Les fichiers caches public",
        "admin"=>"Les fichiers caches admin",
        "log"=>"Les fichiers de log"
    ]}
    <div class="panels row">
    <section class="panel col-xs-12 col-md-8">
    {if $debug}
        {$debug}
    {/if}
    <header class="panel-header">
        <h2 class="panel-heading h5">Effacer les fichiers</h2>
    </header>
    <div class="panel-body panel-body-form">
        <div class="mc-message-container clearfix">
            <div class="mc-message"></div>
        </div>
        <div class="row">
            <form id="remove_caches" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=delete" method="post" class="validate_form edit_form col-xs-12 col-md-6">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="cache">{#cache#|ucfirst}</label>
                            <select name="clear" id="clear" class="form-control required" required>
                                {foreach $collectionformCache as $key => $val}
                                    <option value="{$key}">{$val|ucfirst}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-main-theme pull-right" type="submit" name="action" value="delete">{#save#|ucfirst}</button>
            </form>
        </div>
    </div>
    </section>
    </div>
{/block}