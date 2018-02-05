<div class="modal" id="password-renew" tabindex="-1" role="dialog" aria-labelledby="passwordRenew" aria-hidden="true">
    <div class="modal-content">
        <form action="{geturl}/{getlang}/account/rstpwd/" id="form-password-renew" method="post" class="validate_form nice-form">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">close</i></button>
                <h4 class="modal-title" id="passwordRenew">{#send_renew_password#|ucfirst}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input id="pw_email_ac" type="text" name="account[email_ac]" value="" class="form-control" placeholder="{#ph_mail#|ucfirst}" />
                    <label for="pw_email_ac">{#account_mail#|ucfirst}*&nbsp;:</label>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="hashtoken" value="{$hashpass}" required/>
                <button type="button" class="btn btn-box btn-default" data-dismiss="modal">{#modal_cancel#|ucfirst}</button>
                <button type="submit" class="btn btn-box btn-invert btn-dark-theme">{#account_send#|ucfirst}</button>
            </div>
        </form>
    </div>
</div>