<p class="text-center">{#configuration_txt#}</p>
{include file="brick/spinner.tpl" label="{#saving#|ucfirst}..."}
<form id="config-form" class="config_form form-horizontal" method="post" action="{$smarty.server.REQUEST_URI}index.php?action=save&tab=configuration">
    <div class="form-group">
        <label for="config_host" class="col-5 col-xs-4 col-sm-3 control-label">{#config_host#}*&nbsp;:</label>
        <div class="col-7 col-xs-6 col-sm-4">
            <input id="config_host" type="text" name="config[host]" placeholder="{#config_host_ph#}" class="form-control required" value="localhost" required/>
        </div>
    </div>
    <div class="form-group">
        <label for="config_driver" class="col-5 col-xs-4 col-sm-3 control-label">{#config_driver#}*&nbsp;:</label>
        <div class="col-7 col-xs-6 col-sm-4">
            <select id="config_driver" name="config[driver]" class="form-control required">
                <option value="mysql">Mysql</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="config_user" class="col-5 col-xs-4 col-sm-3 control-label">{#config_user#}*&nbsp;:</label>
        <div class="col-7 col-xs-6 col-sm-4">
            <input id="config_user" type="text" name="config[user]" placeholder="{#config_user_ph#}" class="form-control required" value="" required/>
        </div>
    </div>
    <div class="form-group">
        <label for="config_pwd" class="col-5 col-xs-4 col-sm-3 control-label">{#config_pwd#}*&nbsp;:</label>
        <div class="col-7 col-xs-6 col-sm-4">
            <input id="config_pwd" type="password" name="config[pwd]" placeholder="{#config_pwd_ph#}" class="form-control required" value="" required/>
        </div>
    </div>
    <div class="form-group">
        <label for="config_dbname" class="col-5 col-xs-4 col-sm-3 control-label">{#config_dbname#}*&nbsp;:</label>
        <div class="col-7 col-xs-6 col-sm-4">
            <input id="config_dbname" type="text" name="config[dbname]" placeholder="{#config_dbname_ph#}" class="form-control required" value="" required/>
        </div>
    </div>
    <div class="form-group">
        <label for="config_log" class="col-5 col-xs-4 col-sm-3 control-label">{#config_log#}*&nbsp;:</label>
        <div class="col-7 col-xs-6 col-sm-4">
            <select id="config_log" name="config[log]" class="form-control required">
                <option value="log" selected="selected">LOG</option>
                <option value="debug">DEBUG</option>
                <option value="false">OFF</option>
            </select>
        </div>
    </div>
    <p class="text-center">
        <a href="#analysis" class="btn btn-box btn-invert btn-main-theme" data-toggle="tab">{#previous#}</a>
        <button type="submit" class="btn btn-box btn-invert btn-main-theme">{#save#|ucfirst}</button>
        <a id="goto_install" href="#installation" class="btn btn-box btn-invert btn-success-theme disabled hide">{#goto_installation#}</a>
        <a href="#installation" class="btn btn-box btn-invert btn-main-theme hide" data-toggle="tab">{#next#}</a>
    </p>
</form>