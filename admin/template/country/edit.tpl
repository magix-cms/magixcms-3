{extends file="layout.tpl"}
{block name='head:title'}{#edit_country#|ucfirst}{/block}
{block name='body:id'}country{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des pays">{#country#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-8">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#edit_country#|ucfirst}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="row">
                    <form id="edit_country" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$country.id_country}" method="post" class="validate_form edit_form col-ph-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="name_country">{#country#|ucfirst}</label>
                                    <select name="name_country" id="name_country" class="form-control required" required>
                                        <option value="">{#ph_country#|ucfirst}</option>
                                        {foreach $getCountryCollection as $key => $val}
                                            <option value="{$val}" data-iso="{$key}" {if $country.iso_country == $key} selected{/if}>{$val|ucfirst}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="iso_country">{#iso_country#|ucfirst}</label>
                                    <input type="text" class="form-control" name="iso_country" id="iso_country" readonly placeholder="{#ph_iso_country#|ucfirst}" {if $country.iso_country != null} value="{$country.iso_country}"{/if}>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="id_country" name="id" value="{$country.id_country}">
                        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    {/if}
{/block}
{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/country.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof country == "undefined")
            {
                console.log("country is not defined");
            }else{
                country.runEdit();
            }
        });
    </script>
{/block}