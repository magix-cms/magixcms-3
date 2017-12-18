<form id="edit_socials" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <div class="row">
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_facebook">{#socials_facebook#|ucfirst}</label>
                <input type="text" class="form-control" id="social_facebook" name="company_socials[facebook]" {if $companyData.socials.facebook}value="{$companyData.socials.facebook}" {/if}placeholder="{#socials_facebook_ph#|ucfirst}">
                <small class="help-block">https://www.facebook.com/<strong>facebook-ID</strong>/</small>
            </div>
        </div>
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_twitter">{#socials_twitter#|ucfirst}</label>
                <input type="text" class="form-control" id="social_twitter" name="company_socials[twitter]" {if $companyData.socials.twitter}value="{$companyData.socials.twitter}" {/if}placeholder="{#socials_twitter_ph#|ucfirst}">
                <small class="help-block">https://www.twitter.com/<strong>twitter-ID</strong></small>
            </div>
        </div>
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_google">{#socials_google#|ucfirst}</label>
                <input type="text" class="form-control" id="social_google" name="company_socials[google]" {if $companyData.socials.google}value="{$companyData.socials.google}" {/if}placeholder="{#socials_google_ph#|ucfirst}">
                <small class="help-block">https://plus.google.com/<strong>google+ID</strong>/posts</small>
            </div>
        </div>
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_linkedin">{#socials_linkedin#|ucfirst}</label>
                <input type="text" class="form-control" id="social_linkedin" name="company_socials[linkedin]" {if $companyData.socials.linkedin}value="{$companyData.socials.linkedin}" {/if}placeholder="{#socials_linkedin_ph#|ucfirst}">
                <small class="help-block">https://www.linkedin.com/in/<strong>linkedin-ID</strong></small>
            </div>
        </div>
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_viadeo">{#socials_viadeo#|ucfirst}</label>
                <input type="text" class="form-control" id="social_viadeo" name="company_socials[viadeo]" {if $companyData.socials.viadeo}value="{$companyData.socials.viadeo}" {/if}placeholder="{#socials_viadeo_ph#|ucfirst}">
                <small class="help-block">http://(www|be|fr).viadeo.com/fr/profile/<strong>viadeo-ID</strong></small>
            </div>
        </div>
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_pinterest">{#socials_pinterest#|ucfirst}</label>
                <input type="text" class="form-control" id="social_pinterest" name="company_socials[pinterest]" {if $companyData.socials.pinterest}value="{$companyData.socials.pinterest}" {/if}placeholder="{#socials_pinterest_ph#|ucfirst}">
                <small class="help-block">https://www.pinterest.fr/<strong>pinterest-ID</strong></small>
            </div>
        </div>
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_instagram">{#socials_insta#|ucfirst}</label>
                <input type="text" class="form-control" id="social_instagram" name="company_socials[instagram]" {if $companyData.socials.instagram}value="{$companyData.socials.instagram}" {/if}placeholder="{#socials_insta_ph#|ucfirst}">
                <small class="help-block">https://www.instagram.com/<strong>instagram-ID</strong>/</small>
            </div>
        </div>
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_github">{#socials_github#|ucfirst}</label>
                <input type="text" class="form-control" id="social_github" name="company_socials[github]" {if $companyData.socials.github}value="{$companyData.socials.github}" {/if}placeholder="{#socials_github_ph#|ucfirst}">
                <small class="help-block">https://github.com/<strong>github-ID</strong></small>
            </div>
        </div>
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="form-group">
                <label for="social_soundcloud">{#socials_soundcloud#|ucfirst}</label>
                <input type="text" class="form-control" id="social_soundcloud" name="company_socials[soundcloud]" {if $companyData.socials.soundcloud}value="{$companyData.socials.soundcloud}" {/if}placeholder="{#socials_soundcloud_ph#|ucfirst}">
                <small class="help-block">https://soundcloud.com/<strong>soundcloud-ID</strong></small>
            </div>
        </div>
    </div>
    <input type="hidden" id="data_type" name="data_type" value="socials">
    <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
</form>