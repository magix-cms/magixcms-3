<div class="clearfix mc-message"></div>
<div class="row">
    <form id="config_form" method="post" action="{$smarty.server.REQUEST_URI}?tab=accountConfig" class="validate_form refresh_form col-ph-12 col-sm-6 col-lg-4">
        <fieldset>
            <legend>{#account_info#|ucfirst}</legend>
            <div class="form-group">
                <label for="email_ac">{#email_ac#|ucfirst}&nbsp;*</label>
                <input id="email_ac" type="email" name="account[email_ac]" value="{$account.email_ac}" placeholder="{#ph_email#}" class="form-control required" required/>
            </div>
            <div class="form-group">
                <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#account_save#|ucfirst}</button>
            </div>
        </fieldset>
    </form>
    <form id="pwd_form" method="post" action="{$smarty.server.REQUEST_URI}?tab=pwd" class="validate_form pwd_form col-ph-12 col-sm-6 col-lg-4">
        <fieldset>
            <legend>{#account_password#|ucfirst}</legend>
            <div class="form-group">
                <label for="old_passwd">{#old_passwd#|ucfirst}&nbsp;*</label>
                <input type="password" class="form-control required" name="account[old_passwd]" id="old_passwd" placeholder="{#ph_old_passwd#|ucfirst}" required>
            </div>
            <div class="form-group">
                <label for="passwd">{#new_passwd#|ucfirst}&nbsp;*</label>
                <input type="password" class="form-control required" name="account[new_passwd]" id="passwd" placeholder="{#ph_new_passwd#|ucfirst}" required>
            </div>
            <div class="form-group">
                <label for="repeat_passwd">{#repeat_passwd#|ucfirst}&nbsp;*</label>
                <input type="password" class="form-control required" name="account[repeat_passwd]" id="repeat_passwd" placeholder="{#repeat_passwd#|ucfirst}" equalTo="#passwd" required>
            </div>
            <div class="form-group">
                <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#account_save#|ucfirst}</button>
            </div>
        </fieldset>
    </form>
</div>