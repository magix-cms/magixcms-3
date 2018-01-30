{widget_account_data}
<div id="form-cart">
    <fieldset>
        <legend class="h3">{#coordonnees_cart#|ucfirst}</legend>
        <div class="panel panel-default">
            <div class="panel-heading">
                {#pn_info_account#}
            </div>
            <div class="panel-body">
                {capture name="missing"}<div class="alert alert-warning">{#please_update#|ucfirst}</div>{/capture}
                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label" for="lastname_cart">{#pn_cartpay_lastname#|ucfirst}&nbsp;*&nbsp;:</label>
                        {$dataAccount.lastname}
                        <input class="form-control" type="hidden" id="lastname_cart" name="lastname_cart" value="{$dataAccount.lastname}" />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label" for="firstname_cart">{#pn_cartpay_firstname#|ucfirst}&nbsp;*&nbsp;:</label>
                        {$dataAccount.firstname}
                        <input class="form-control" type="hidden" id="firstname_cart" name="firstname_cart" value="{$dataAccount.firstname}" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label" for="email_cart">{#pn_cartpay_mail#|ucfirst}&nbsp;*&nbsp;:</label>
                        {$dataAccount.email}
                        <input class="form-control" type="hidden" id="email_cart" name="email_cart" value="{$dataAccount.email}" />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label" for="phone_cart">{#pn_cartpay_phone#|ucfirst}&nbsp;:</label>
                        {$dataAccount.phone}
                        <input class="form-control" type="hidden" id="phone_cart" name="phone_cart" value="{$dataAccount.phone}" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label" for="street_cart">{#pn_cartpay_street#|ucfirst}&nbsp;*&nbsp;:</label>
                        {if !empty($dataAccount.street)}{$dataAccount.street}{else}{$smarty.capture.missing}{/if}
                        <input class="form-control" type="hidden" id="street_cart" name="street_cart" value="{$dataAccount.street}" />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label" for="postal_cart">{#pn_cartpay_postal#|ucfirst}&nbsp;*&nbsp;:</label>
                        {if !empty($dataAccount.postcode)}{$dataAccount.postcode}{else}{$smarty.capture.missing}{/if}
                        <input class="form-control" type="hidden" id="postal_cart" name="postal_cart" value="{$dataAccount.postcode}" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label" for="city_cart">{#pn_cartpay_locality#|ucfirst}&nbsp;*&nbsp;:</label>
                        {if !empty($dataAccount.city)}{$dataAccount.city}{else}{$smarty.capture.missing}{/if}
                        <input class="form-control" type="hidden" id="city_cart" name="city_cart" value="{$dataAccount.city}" />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label" for="country_cart">{#pn_cartpay_country#|ucfirst}&nbsp;*&nbsp;:</label>
                        {if !empty($dataAccount.country)}{$dataAccount.country}{else}{$smarty.capture.missing}{/if}
                        <input class="form-control" type="hidden" id="country_cart" name="country_cart" value="{$dataAccount.country}" />
                    </div>
                </div>
            </div>
            {widget_account_url}
            <div class="panel-footer">
                {if $smarty.session.idaccount && $smarty.session.keyuniqid_ac}
                    {capture name="loginRedirect"}<a href="{$hashurl}" title="{#pn_upgrade_account#}">{#pn_upgrade_account#}</a>{/capture}
                    <small>{#pn_update_account#} {$smarty.capture.loginRedirect}</small>
                {/if}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="h3">{#more_information#}</legend>
        <div class="form-group">
            <label class="name block" for="message_cart">{#pn_cartpay_more_explain#|ucfirst} :</label>
            <textarea id="message_cart" name="message_cart" class="form-control" rows="6" cols="36"></textarea>
        </div>
    </fieldset>
</div>