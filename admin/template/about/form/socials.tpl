<form id="edit_socials" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-xs-12 col-md-10">
    <div class="row">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="social_facebook">{#socials_facebook#|ucfirst}</label>
                    <input type="text" class="form-control" id="social_facebook" name="company_socials[facebook]" {if $companyData.socials.facebook}value="{$companyData.socials.facebook}" {/if}placeholder="{#socials_facebook_ph#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="social_facebook">{#socials_twitter#|ucfirst}</label>
                    <input type="text" class="form-control" id="social_twitter" name="company_socials[twitter]" {if $companyData.socials.twitter}value="{$companyData.socials.twitter}" {/if}placeholder="{#socials_twitter_ph#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="social_facebook">{#socials_google#|ucfirst}</label>
                    <input type="text" class="form-control" id="social_google" name="company_socials[google]" {if $companyData.socials.google}value="{$companyData.socials.google}" {/if}placeholder="{#socials_google_ph#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="social_facebook">{#socials_linkedin#|ucfirst}</label>
                    <input type="text" class="form-control" id="social_linkedin" name="company_socials[linkedin]" {if $companyData.socials.linkedin}value="{$companyData.socials.linkedin}" {/if}placeholder="{#socials_linkedin_ph#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="social_facebook">{#socials_viadeo#|ucfirst}</label>
                    <input type="text" class="form-control" id="social_viadeo" name="company_socials[viadeo]" {if $companyData.socials.viadeo}value="{$companyData.socials.viadeo}" {/if}placeholder="{#socials_viadeo_ph#|ucfirst}">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="submit" class="col-xs-12 col-md-4">
            <input type="hidden" id="data_type" name="data_type" value="socials">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </div>
    </div>
</form>