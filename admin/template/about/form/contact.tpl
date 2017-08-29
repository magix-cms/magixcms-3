<form id="edit_contact" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <div class="row">
        <div class="col-ph-12 col-sm-6 col-md-4">
            <div class="form-group">
                <label for="company_mail">{#contact_mail#|ucfirst}</label>
                <input type="text" class="form-control" id="company_mail" name="company_mail" {if $companyData.contact.mail}value="{$companyData.contact.mail}" {/if}placeholder="{#contact_mail_ph#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="company_phone">{#contact_phone#|ucfirst}</label>
                <input type="text" class="form-control" id="company_phone" name="company_phone" {if $companyData.contact.phone}value="{$companyData.contact.phone}" {/if}placeholder="{#contact_phone_ph#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="company_mobile">{#contact_mobile#|ucfirst}</label>
                <input type="text" class="form-control" id="company_mobile" name="company_mobile" {if $companyData.contact.mobile}value="{$companyData.contact.mobile}" {/if}placeholder="{#contact_mobile_ph#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="company_fax">{#contact_fax#|ucfirst}</label>
                <input type="text" class="form-control" id="company_fax" name="company_fax" {if $companyData.contact.fax}value="{$companyData.contact.fax}" {/if}placeholder="{#contact_fax_ph#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="company_adress">{#contact_adress#|ucfirst}</label>
                <input type="text" class="form-control" id="company_adress" name="company_adress[street]" {if $companyData.contact.adress.street}value="{$companyData.contact.adress.street}" {/if}placeholder="{#contact_street_ph#|ucfirst}">
            </div>
            <div class="row">
                <div class="form-group col-ph-12 col-md-6">
                    <label for="company_postcode">{#contact_postcode#|ucfirst}</label>
                    <input type="text" class="form-control" id="company_postcode" name="company_adress[postcode]" {if $companyData.contact.adress.postcode}value="{$companyData.contact.adress.postcode}" {/if}placeholder="{#contact_postcode_ph#|ucfirst}">
                </div>
                <div class="form-group col-ph-12 col-md-6">
                    <label for="company_city">{#contact_city#|ucfirst}</label>
                    <input type="text" class="form-control" id="company_city" name="company_adress[city]" {if $companyData.contact.adress.city}value="{$companyData.contact.adress.city}" {/if}placeholder="{#contact_city_ph#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="col-ph-12 col-sm-6 col-md-4">
            <div class="row">
                <div class="form-group col-ph-12 col-md-6">
                    <label for="click_to_mail">
                        {#contact_click#|ucfirst}
                        <a href="#" class="text-info" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{#contact_click_ph#|ucfirst}">
                            <span class="fa fa-question-circle"></span>
                        </a>
                    </label>
                    <input id="click_to_mail" data-toggle="toggle" type="checkbox" name="click_to_mail" data-toggle="toggle" type="checkbox" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if $companyData.contact.click_to_mail} checked{/if}>
                </div>
                <div class="form-group col-ph-12 col-md-6">
                    <label for="crypt_mail">
                        {#contact_crypt#|ucfirst} {#contact_recommended#|ucfirst}
                        <a href="#" class="text-info" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{#contact_crypt_ph#|ucfirst}">
                            <span class="fa fa-question-circle"></span>
                        </a>
                    </label>
                    <input id="crypt_mail" data-toggle="toggle" type="checkbox" name="crypt_mail" data-toggle="toggle" type="checkbox" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if $companyData.contact.crypt_mail} checked{/if}>
                </div>
            </div>
            <div class="form-group">
                <label for="click_to_call">
                    {#contact_call#|ucfirst}
                    <a href="#" class="text-info" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{#contact_call_ph#|ucfirst}">
                        <span class="fa fa-question-circle"></span>
                    </a>
                </label>
                <input id="click_to_call" data-toggle="toggle" type="checkbox" name="click_to_call" data-toggle="toggle" type="checkbox" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if $companyData.contact.click_to_call} checked{/if}>
            </div>
        </div>
    </div>
    <div id="submit" class="form-group">
        <input type="hidden" id="data_type" name="data_type" value="contact">
        <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </div>
</form>
<form action="{$smarty.server.REQUEST_URI}&action=edit" id="info_language_form" method="post" class="validate_form">
    <fieldset>
        <legend>{#lang_legend#|ucfirst}</legend>
        <p>{$companyData.contact.languages}</p>
        <div class="form-group">
            <input type="hidden" id="data_type" name="data_type" value="refesh_lang">
            <button type="submit" class="btn btn-main-theme">{#refresh#|ucfirst}</button>
        </div>
    </fieldset>
</form>