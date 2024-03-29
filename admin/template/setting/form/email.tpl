<form id="edit_setting_mail" action="{$smarty.server.SCRIPT_NAME}?controller={$controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <fieldset>
        <legend>{#configuration_email#}</legend>
        <div class="row">
            <div class="col-ph-12 col-sm-10 col-md-8 col-lg-6">
                <div class="form-group">
                    <label for="mail_sender">{#mail_sender#|ucfirst}&nbsp;*</label>
                    <input type="text" name="setting[mail_sender]" id="mail_sender" class="form-control required" placeholder="{#ph_mail_sender#}" value="{$settings.mail_sender}" required />
                </div>
                <div class="form-group">
                    <div class="switch">
                        <input type="checkbox" data-target="#smtp_config" id="smtp_enabled" name="setting[smtp_enabled]" class="switch-native-control optional-fields"{if isset($settings.smtp_enabled) && $settings.smtp_enabled eq '1'} checked{/if} />
                        <div class="switch-bg">
                            <div class="switch-knob"></div>
                        </div>
                    </div>
                    <label for="smtp_enabled">
                        {#smtp_enabled#}&nbsp;?
                        <a href="#" class="text-warning" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="{#smtp_warning#}">
                            <span class="fa fa-question-circle"></span>
                        </a>
                    </label>
                </div>
                <div id="smtp_config" class="collapse">
                    <div class="form-group">
                        <label for="set_host">{#set_host#}&nbsp;</label>
                        <input type="text" name="setting[set_host]" id="set_host" class="form-control" placeholder="{#ph_set_host#}" value="{$settings.set_host}" />
                    </div>
                    <div class="form-group">
                        <label for="set_port">{#set_port#}&nbsp;</label>
                        <input type="text" name="setting[set_port]" id="set_port" class="form-control" placeholder="{#ph_set_port#}" value="{$settings.set_port}" />
                    </div>
                    <div class="form-group">
                        <label for="set_encryption">{#set_encryption#}&nbsp;</label>
                        <input type="text" name="setting[set_encryption]" id="set_port" class="form-control" placeholder="{#ph_set_encryption#}" value="{$settings.set_encryption}" />
                    </div>
                    <div class="form-group">
                        <label for="set_username">{#set_username#}&nbsp;</label>
                        <input type="text" name="setting[set_username]" id="set_username" class="form-control" placeholder="{#ph_set_username#}" value="{$settings.set_username}" />
                    </div>
                    <div class="form-group">
                        <label for="set_password">{#set_password#}&nbsp;</label>
                        <input type="password" name="setting[set_password]" id="set_password" class="form-control" placeholder="{#ph_set_password#}" value="{$settings.set_password}" />
                    </div>
                </div>
                <input type="hidden" id="type" name="type" value="mail">
                <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
            </div>
        </div>
    </fieldset>
</form>