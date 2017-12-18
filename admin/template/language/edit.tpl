{extends file="layout.tpl"}
{block name='head:title'}{#edit_language#|ucfirst}{/block}
{block name='body:id'}language{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des languages">{#language#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-8">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#edit_language#|ucfirst}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="row">
                    <form id="edit_language" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$lang.id_lang}" method="post" class="validate_form edit_form col-ph-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label for="name_lang">{#language#|ucfirst}</label>
                                    <select name="name_lang" id="name_lang" class="form-control required" required>
                                        <option value="">{#ph_language#|ucfirst}</option>
                                        {foreach $getLanguageCollection as $key => $val}
                                            <option value="{$val}" data-iso="{$key}" {if $lang.iso_lang == $key} selected{/if}>{$val|ucfirst}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>{#default_lang#|ucfirst}&nbsp;*</label>
                                    <div class="radio">
                                        <label for="default_1">
                                            <input type="radio" name="default_lang" id="default_1" value="1"{if $lang.default_lang == '1'} checked{/if} required>
                                            {#bin_1#}
                                        </label>
                                        <label for="default_0">
                                            <input type="radio" name="default_lang" id="default_0" value="0"{if $lang.default_lang == '0'} checked{/if} required>
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
                                    <input type="text" class="form-control" name="iso_lang" id="iso_lang" readonly placeholder="{#ph_iso_lang#|ucfirst}"{if $lang.iso_lang != null} value="{$lang.iso_lang}"{/if}>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>{#active#|ucfirst}&nbsp;*</label>
                                    <div class="radio">
                                        <label for="active_1">
                                            <input type="radio" name="active_lang" id="active_1" value="1"{if $lang.active_lang == 1} checked{/if} required>
                                            {#bin_1#}
                                        </label>
                                        <label for="active_0">
                                            <input type="radio" name="active_lang" id="active_0" value="0"{if $lang.active_lang == 0} checked{/if} required>
                                            {#bin_0#}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="id_lang" name="id" value="{$lang.id_lang}">
                        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    {/if}
{/block}