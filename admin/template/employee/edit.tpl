{extends file="layout.tpl"}
{block name='head:title'}{#edit_employee#|ucfirst}{/block}
{block name='body:id'}employee{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des employés">{#employees#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-8">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#edit_employee#|ucfirst}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="row">
                    <form id="edit_employee" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$employee.id_admin}" method="post" class="validate_form edit_form col-ph-12 col-md-6">
                        <div class="form-group radio-group">
                            <label>{#title#|ucfirst}&nbsp;*</label>
                            <div class="radio">
                                <label for="m_admin">
                                    <input type="radio" name="title_admin" id="m_admin" value="m"{if $employee.title_admin == 'm'} checked{/if} required>
                                    {#title_m#}
                                </label>
                                <label for="w_admin">
                                    <input type="radio" name="title_admin" id="w_admin" value="w"{if $employee.title_admin == 'w'} checked{/if} required>
                                    {#title_w#}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-ph-12 col-md-6">
                                <div class="form-group">
                                    <label for="firstname_admin">{#firstname#|ucfirst}&nbsp;*</label>
                                    <input type="text" class="form-control required" name="firstname_admin" id="firstname_admin" placeholder="{#ph_firstname#|ucfirst}" value="{$employee.firstname_admin}" required>
                                </div>
                            </div>
                            <div class="col-ph-12 col-md-6">
                                <div class="form-group">
                                    <label for="lastname_admin">{#lastname#|ucfirst}&nbsp;*</label>
                                    <input type="text" class="form-control required" name="lastname_admin" id="lastname_admin" placeholder="{#ph_lastname#|ucfirst}" value="{$employee.lastname_admin}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email_admin">{#email#|ucfirst}&nbsp;*</label>
                            <input type="email" class="form-control required" name="email_admin" id="email_admin" placeholder="{#ph_email#|ucfirst}" value="{$employee.email_admin}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone_admin">{#phone#|ucfirst}</label>
                            <input type="text" class="form-control" name="phone_admin" id="phone_admin" placeholder="{#ph_phone#|ucfirst}"{if $employee.phone_admin != null} value="{$employee.phone_admin}"{/if}>
                        </div>
                        <div class="form-group">
                            <label for="address_admin">{#address#|ucfirst}</label>
                            <input type="text" class="form-control" name="address_admin" id="address_admin" placeholder="{#ph_address#|ucfirst}"{if $employee.address_admin != null} value="{$employee.address_admin}"{/if}>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="postcode_admin">{#postcode#|ucfirst}</label>
                                    <input type="text" class="form-control" name="postcode_admin" id="postcode_admin" placeholder="{#ph_postcode#|ucfirst}"{if $employee.postcode_admin != null} value="{$employee.postcode_admin}"{/if}>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="city_admin">{#city#|ucfirst}</label>
                                    <input type="text" class="form-control" name="city_admin" id="city_admin" placeholder="{#ph_city#|ucfirst}"{if $employee.city_admin != null} value="{$employee.city_admin}"{/if}>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country_admin">{#country#|ucfirst}</label>
                            <select name="country_admin" id="country_admin" class="form-control">
                                <option value="">{#ph_country#|ucfirst}</option>
                                {foreach $countries as $iso => $name}
                                    <option value="{$iso}"{if $employee.country_admin == $iso} selected{/if}>{$name|ucfirst}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label for="id_role">{#role#|ucfirst}&nbsp;*</label>
                                    <select name="id_role" id="id_role" class="form-control required" required>
                                        <option value="">{#ph_role#|ucfirst}</option>
                                        {foreach $roles as $role}
                                            <option value="{$role.id_role}"{if $employee.id_role == $role.id_role} selected{/if}>{$role.role_name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>{#active#|ucfirst}&nbsp;*</label>
                                    <div class="radio">
                                        <label for="active_1">
                                            <input type="radio" name="active_admin" id="active_1" value="1"{if $employee.active_admin == 1} checked{/if} required>
                                            {#bin_1#}
                                        </label>
                                        <label for="active_0">
                                            <input type="radio" name="active_admin" id="active_0" value="0"{if $employee.active_admin == 0} checked{/if} required>
                                            {#bin_0#}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="id_admin" name="id" value="{$employee.id_admin}">
                        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                    </form>
                    <form id="change_pwd" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$employee.id_admin}" method="post" class="validate_form col-ph-12 col-md-6">
                        <div class="form-group">
                            <label for="passwd_admin">{#passwd#|ucfirst}&nbsp;*</label>
                            <input type="password" class="form-control required" name="passwd_admin" id="passwd_admin" placeholder=" {#ph_passwd#|ucfirst}" required>
                        </div>
                        <div class="form-group">
                            <label for="repeat_passwd">{#repeat_passwd#|ucfirst}&nbsp;*</label>
                            <input type="password" class="form-control required" name="repeat_passwd" id="repeat_passwd" placeholder=" {#repeat_passwd#|ucfirst}" equalTo="#passwd_admin" required>
                        </div>
                        <input type="hidden" id="id_admin_pwd" name="id" value="{$employee.id_admin}" required>
                        <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}