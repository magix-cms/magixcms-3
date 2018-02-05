<form id="{if $getConfigData.cartpay eq '1'}private-form-cartpay{else}private-form{/if}" method="post" action="{$smarty.server.REQUEST_URI}" class="validate_form edit_form">
    <div class="row">
        <fieldset class="col-ph-12 col-md-6">
            <legend>{#particulars#|ucfirst}</legend>
            <div class="row">
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="firstname_ac">{#firstname_ac#|ucfirst} :</label>
                        <input id="firstname_ac" type="text" name="account[firstname_ac]" value="{$account.firstname_ac}" placeholder="{#ph_firstname#|ucfirst}" class="form-control" />
                    </div>
                </div>
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="lastname_ac">{#lastname_ac#|ucfirst} :</label>
                        <input id="lastname_ac" type="text" name="account[lastname_ac]" value="{$account.lastname_ac}" placeholder="{#ph_lastname#|ucfirst}" class="form-control"  />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="phone_ac">{#phone_ac#|ucfirst} :</label>
                <input id="phone_ac" type="text" name="account[phone_ac]" value="{$account.phone_ac}" placeholder="{#ph_phone#}" class="form-control"  />
            </div>
            <div class="form-group">
                <label for="street_ac">{#street_ac#|ucfirst} :</label>
                <input id="street_ac" type="text" name="address[street_address]" value="{$account.street_address}" placeholder="{#ph_street#}" class="form-control"  />
            </div>
            <div class="row">
                <div class="col-ph-12 col-xs-6">
                    <div class="form-group">
                        <label for="postcode_ac">{#postcode_ac#|ucfirst} :</label>
                        <input id="postcode_ac" type="text" name="address[postcode_address]" value="{$account.postcode_address}" placeholder="{#ph_postcode#}" class="form-control"  />
                    </div>
                </div>
                <div class="col-ph-12 col-xs-6">
                    <div class="form-group">
                        <label for="city_ac">{#city_ac#|ucfirst} :</label>
                        <input id="city_ac" type="text" name="address[city_address]" value="{$account.city_address}" placeholder="{#ph_city#}" class="form-control"  />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="country_ac">{#country_ac#|ucfirst}</label>
                <select name="address[country_address]" id="country_ac" class="form-control">
                    <option value="">{#ph_country#|ucfirst}</option>
                    {foreach $countries as $iso => $name}
                        <option value="{$iso}"{if $account.country_address == $iso} selected{/if}>{$name|ucfirst}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label for="company_ac">{#company_ac#|ucfirst} :</label>
                <input id="company_ac" type="text" name="account[company_ac]" value="{$account.company_ac}" placeholder="{#ph_company#}" class="form-control"  />
            </div>
            <div class="form-group">
                <label for="vat_ac">{#tva_ac#|ucfirst} :</label>
                <input id="vat_ac" type="text" name="account[vat_ac]" value="{$account.vat_ac}" placeholder="{#ph_vat#}" class="form-control"  />
            </div>
        </fieldset>
        {if $config.links}
        <fieldset class="col-ph-12 col-md-6">
            <legend>{#socials#|ucfirst}</legend>
            <div class="form-group">
                <label for="social_website" class="sr-only">{#website_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="website-icon" title="{#website_ac#|ucfirst}"><span class="fa fa-globe"></span></span>
                    <input type="text" class="form-control" id="social_website" aria-describedby="website-icon" name="socials[website]" {if $account.website}value="{$account.website}" {/if}placeholder="{#ph_website#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_facebook" class="sr-only">{#facebook_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="facebook-icon" title="{#facebook_ac#|ucfirst}"><span class="fa fa-facebook"></span></span>
                    <input type="text" class="form-control" id="social_facebook" aria-describedby="facebook-icon" name="socials[facebook]" {if $account.facebook}value="{$account.facebook}" {/if}placeholder="{#ph_facebook#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_instagram" class="sr-only">{#insta_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="instagram-icon" title="{#insta_ac#|ucfirst}"><span class="fa fa-instagram"></span></span>
                    <input type="text" class="form-control" id="social_instagram" aria-describedby="instagram-icon" name="socials[instagram]" {if $account.instagram}value="{$account.instagram}" {/if}placeholder="{#ph_insta#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_twitter" class="sr-only">{#twitter_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="twitter-icon" title="{#twitter_ac#|ucfirst}"><span class="fa fa-twitter"></span></span>
                    <input type="text" class="form-control" id="social_twitter" aria-describedby="twitter-icon" name="socials[twitter]" {if $account.twitter}value="{$account.twitter}" {/if}placeholder="{#ph_twitter#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_google" class="sr-only">{#google_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="google-icon" title="{#google_ac#|ucfirst}"><span class="fa fa-google-plus"></span></span>
                    <input type="text" class="form-control" id="social_google" aria-describedby="google-icon" name="socials[google]" {if $account.google}value="{$account.google}" {/if}placeholder="{#ph_google#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_linkedin" class="sr-only">{#linkedin_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="linkedin-icon" title="{#linkedin_ac#|ucfirst}"><span class="fa fa-linkedin"></span></span>
                    <input type="text" class="form-control" id="social_linkedin" aria-describedby="linkedin-icon" name="socials[linkedin]" {if $account.linkedin}value="{$account.linkedin}" {/if}placeholder="{#ph_linkedin#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_viadeo" class="sr-only">{#viadeo_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="viadeo-icon" title="{#viadeo_ac#|ucfirst}"><span class="fa fa-viadeo"></span></span>
                    <input type="text" class="form-control" id="social_viadeo" aria-describedby="viadeo-icon" name="socials[viadeo]" {if $account.viadeo}value="{$account.viadeo}" {/if}placeholder="{#ph_viadeo#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_pinterest" class="sr-only">{#pinterest_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="pinterest-icon" title="{#pinterest_ac#|ucfirst}"><span class="fa fa-pinterest"></span></span>
                    <input type="text" class="form-control" id="social_pinterest" aria-describedby="pinterest-icon" name="socials[pinterest]" {if $account.pinterest}value="{$account.pinterest}" {/if}placeholder="{#ph_pinterest#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_github" class="sr-only">{#github_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="github-icon" title="{#github_ac#|ucfirst}"><span class="fa fa-github"></span></span>
                    <input type="text" class="form-control" id="social_github" aria-describedby="github-icon" name="socials[github]" {if $account.github}value="{$account.github}" {/if}placeholder="{#ph_github#|ucfirst}">
                </div>
            </div>
            <div class="form-group">
                <label for="social_soundcloud" class="sr-only">{#soundcloud_ac#|ucfirst}</label>
                <div class="input-group">
                    <span class="input-group-addon" id="soundcloud-icon" title="{#soundcloud_ac#|ucfirst}"><span class="fa fa-soundcloud"></span></span>
                    <input type="text" class="form-control" id="social_soundcloud" aria-describedby="soundcloud-icon" name="socials[soundcloud]" {if $account.soundcloud}value="{$account.soundcloud}" {/if}placeholder="{#ph_soundcloud#|ucfirst}">
                </div>
            </div>
        </fieldset>
        {/if}
    </div>
    <fieldset>
        <legend>{#account_save#}</legend>
        <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#account_save#|ucfirst}</button>
    </fieldset>
    <div class="clearfix mc-message"></div>
</form>