{include file="brick/spinner.tpl" label="{#saving#|ucfirst}..."}
<form id="setting-form" class="setting_form form-horizontal" method="post" action="{$smarty.server.REQUEST_URI}index.php?action=save&tab=setting">
    <p class="alert alert-info text-center"><small><i class="fas fa-x2 fa-info-circle"></i>&nbsp;{#setting_txt#}</small></p>
    <fieldset>
        <legend class="text-center">{#about_website#}</legend>
        <div class="form-group">
            <label for="setting_website" class="col-5 col-xs-4 col-sm-3 control-label">{#setting_website#}*&nbsp;:</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <input id="setting_website" type="text" name="setting[website]" placeholder="{#setting_website_ph#}" class="form-control required" value="" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="setting_type" class="col-5 col-xs-4 col-sm-3 control-label">{#setting_type#}*&nbsp;:</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <select id="setting_type" name="setting[type]" class="form-control required">
                    <option value="person">{#type_person#}</option>
                    <option value="corp">{#type_corp#}</option>
                    <option value="locb">{#type_locb#}</option>
                    <option value="store">{#type_store#}</option>
                    <option value="food">{#type_food#}</option>
                    <option value="org">{#type_org#}</option>
                    <option value="place">{#type_place#}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="setting_domain" class="col-5 col-xs-4 col-sm-3 control-label">{#setting_domain#}*&nbsp;:</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <input id="setting_domain" type="text" name="setting[domain]" placeholder="{#setting_domain_ph#}" class="form-control required" value="" required/>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="text-center">{#your_admin_account#}</legend>
        <div class="form-group radio-group">
            <label class="col-5 col-xs-4 col-sm-3 control-label">{#setting_title#|ucfirst}&nbsp;*</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <div class="radio">
                    <label for="title_m">
                        <input type="radio" name="setting[title]" id="title_m" value="m" required>
                        {#title_m#}
                    </label>
                    <label for="title_w">
                        <input type="radio" name="setting[title]" id="title_w" value="w" required>
                        {#title_w#}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="setting_firstname" class="col-5 col-xs-4 col-sm-3 control-label">{#setting_firstname#}*&nbsp;:</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <input id="setting_firstname" type="text" name="setting[firstname]" placeholder="{#setting_firstname_ph#}" class="form-control required" value="" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="setting_lastname" class="col-5 col-xs-4 col-sm-3 control-label">{#setting_lastname#}*&nbsp;:</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <input id="setting_lastname" type="text" name="setting[lastname]" placeholder="{#setting_lastname_ph#}" class="form-control required" value="" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="setting_email" class="col-5 col-xs-4 col-sm-3 control-label">{#setting_email#}*&nbsp;:</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <input id="setting_email" type="email" name="setting[email]" placeholder="{#setting_email_ph#}" class="form-control required" value="" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="setting_pwd" class="col-5 col-xs-4 col-sm-3 control-label">{#setting_pwd#}*&nbsp;:</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <input id="setting_pwd" type="password" name="setting[pwd]" placeholder="{#setting_pwd_ph#}" class="form-control required" value="" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="setting_rppwd" class="col-5 col-xs-4 col-sm-3 control-label">{#setting_rppwd#}*&nbsp;:</label>
            <div class="col-7 col-xs-6 col-sm-4">
                <input id="setting_rppwd" type="password" name="setting[rppwd]" placeholder="{#setting_rppwd_ph#}" class="form-control required" equalTo="#setting_pwd" value="" required/>
            </div>
        </div>
    </fieldset>
    <p class="text-center">
        <a href="#installation" class="btn btn-box btn-invert btn-main-theme" data-toggle="tab">{#previous#}</a>
        <button type="submit" class="btn btn-box btn-invert btn-main-theme">{#save#|ucfirst}</button>
        <a id="goto_confirm" href="#confirmation" class="btn btn-box btn-invert btn-success-theme disabled hide">{#goto_confirmation#}</a>
        <a href="#confirmation" class="btn btn-box btn-invert btn-main-theme hide" data-toggle="tab">{#next#}</a>
    </p>
</form>