<div class="modal" id="password-renew" tabindex="-1" role="dialog" aria-labelledby="passwordRenew" aria-hidden="true">
    <div class="modal-content">
        <form action="{$smarty.server.REQUEST_URI}" id="form-password-renew" method="post" class="nice-form">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="passwordRenew">{#send_renew_password#|ucfirst}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="lo_email_ac">{#pn_account_mail#|ucfirst}*&nbsp;:</label>
                    <input id="lo_email_ac" type="text" name="lo_email_ac" value="" class="form-control" placeholder="{#ph_email#|ucfirst}" />
                    <input type="hidden" name="hashtoken" value="{$hashpass}" />
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-sd-theme btn-block" value="{#pn_account_send#|ucfirst}" />
                <div class="ajax-message"></div>
            </div>
        </form>
    </div>
</div>