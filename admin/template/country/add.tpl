{extends file="layout.tpl"}
{block name='head:title'}{#add_country#|ucfirst}{/block}
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
            <h2 class="panel-heading h5">{#add_country#|ucfirst}</h2>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <form id="add_employee" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
                <div class="row">
                    <div class="col-ph-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="name_country">{#country#|ucfirst}</label>
                                    <select name="name_country" id="name_country" class="form-control required" required>
                                        <option value="">{#ph_country#|ucfirst}</option>
                                        {foreach $getCountryCollection as $key => $val}
                                            <option value="{$val}" data-iso="{$key}">{$val|ucfirst}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="iso_country">{#iso_country#|ucfirst}</label>
                                    <input type="text" class="form-control" name="iso_country" id="iso_country" readonly placeholder="{#ph_iso_country#|ucfirst}">
                                </div>
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