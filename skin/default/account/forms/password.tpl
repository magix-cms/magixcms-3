<form id="newpassword-form" method="post" action="{$smarty.server.REQUEST_URI}">
    <div class="form-group">
        <label for="cryptkeypass_ac">
            {#pn_account_password#|ucfirst}* :
        </label>
        <input type="password" id="cryptkeypass_ac" name="cryptkeypass_ac" value="" class="form-control" placeholder="{#ph_account_psw#|ucfirst}"/>
    </div>
    <div class="form-group">
        <label for="new_cryptkeypass_ac">
            {#pn_account_new_password#|ucfirst}* :
        </label>
        <input type="password" id="new_cryptkeypass_ac" name="new_cryptkeypass_ac" value="" class="form-control" placeholder="{#ph_account_new_password#|ucfirst}"/>
    </div>
    <div class="form-group">
        <label for="cryptkeypass_confirm">
            {#pn_account_password_confirm#|ucfirst}* :
        </label>
        <input type="password" id="cryptkeypass_confirm" name="cryptkeypass_confirm" value="" class="form-control" placeholder="{#ph_account_password_confirm#|ucfirst}"/>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-box btn-flat btn-main-theme" value="{#pn_account_save#|ucfirst}" />
    </div>
    <div class="clearfix mc-message-pwd"></div>
</form>