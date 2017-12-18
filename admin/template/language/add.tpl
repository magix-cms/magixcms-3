{extends file="layout.tpl"}
{block name='head:title'}{#add_language#|ucfirst}{/block}
{block name='body:id'}language{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des langues">{#language#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
{if {employee_access type="append" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12 col-md-8">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header">
            <h2 class="panel-heading h5">{#add_language#|ucfirst}</h2>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <form id="add_employee" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
                <div class="row">
                    <div class="col-ph-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label for="name_lang">{#language#|ucfirst}</label>
                                    <select name="name_lang" id="name_lang" class="form-control required">
                                        <option value="">{#ph_language#|ucfirst}</option>
                                        {foreach $getLanguageCollection as $key => $val}
                                            <option value="{$val}" data-iso="{$key}">{$val|ucfirst}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>{#default_lang#|ucfirst}&nbsp;*</label>
                                    <div class="radio">
                                        <label for="default_1">
                                            <input type="radio" name="default_lang" id="default_1" value="1" required>
                                            {#bin_1#}
                                        </label>
                                        <label for="default_0">
                                            <input type="radio" name="default_lang" id="default_0" value="0" checked required>
                                            {#bin_0#}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label for="iso_lang">{#iso_lang#|ucfirst}</label>
                                    <input type="text" class="form-control" name="iso_lang" id="iso_lang" readonly placeholder="{#ph_iso_lang#|ucfirst}">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>{#active#|ucfirst}&nbsp;*</label>
                                    <div class="radio">
                                        <label for="active_1">
                                            <input type="radio" name="active_lang" id="active_1" value="1" checked required>
                                            {#bin_1#}
                                        </label>
                                        <label for="active_0">
                                            <input type="radio" name="active_lang" id="active_0" value="0" required>
                                            {#bin_0#}
                                        </label>
                                    </div>
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
        {baseadmin}/template/js/language.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof language == "undefined")
            {
                console.log("language is not defined");
            }else{
                language.runEdit();
            }
        });
    </script>
{/block}