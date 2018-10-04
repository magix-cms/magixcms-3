{extends file="layout.tpl"}
{block name='head:title'}{#login_root#|ucfirst}{/block}
{block name='body:id'}login{/block}
{block name="header"}{/block}

{block name="main"}
    <main id="page" class="container-fluid">
        <div class="login-panel">
            {if $error}
                <div class="error">
                    {$error}
                </div>
            {/if}
            {if $debug}
                {$debug}
            {/if}
            <div id="logo">
                <img src="/{baseadmin}/template/img/logo/logo-magix_cms@229.png" alt="Magix CMS" width="229" height="50">
            </div>
            <div class="flip-container">
                <div class="flipper">
                    <div class="login-box front panel">{* {$smarty.server.PHP_SELF} *}
                        <form id="login_form" method="post" action="{$url}/admin/index.php?controller=login">
                            <div class="form-group">
                                <label class="control-label" for="email_admin">{#email#}</label>
                                <input type="text" class="form-control" placeholder="{#placeholder_login#}" id="email_admin" name="employee[email_admin]" value="" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="passwd_admin">{#passwd#}</label>
                                <input type="password" class="form-control" placeholder="{#placeholder_password#}" id="passwd_admin" name="employee[passwd_admin]" value="" />
                            </div>

                            <div class="form-group submit-group">
                                <input type="hidden" id="hashtoken" name="employee[hashtoken]" value="{$hashpass}" />
                                <input type="submit" class="btn btn-block btn-main-theme" value="{#login#|upper}" />
                            </div>

                            <div class="form-group">
                                <div class="checkbox pull-left">
                                    <label for="stay_logged">
                                        <input type="checkbox" id="stay_logged" name="stay_logged" value="1" />
                                        {#stay_logged#}
                                    </label>
                                </div>
                                <a class="forgot-password pull-right" href="#"> {#passwd_forgot#} </a>
                            </div>
                        </form>
                    </div>
                    <div class="pwd-box back panel">
                        <form id="forgot_password_form" method="post" action="#">
                            <div class="mc-message alert alert-info">
                                <h4>{#passwd_forgot#} ?</h4>
                                <p>{#passwd_forgot_txt#}</p>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="email_forgot"> E-mail </label>
                                <input id="email_forgot" class="form-control" type="text" placeholder="{#placeholder_login#}" name="email_forgot">
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-default login-form" href="#" type="button">
                                    <i class="icon-caret-left"></i>
                                    {#back_to_login#}
                                </button>
                                <button class="btn btn-default pull-right" type="submit" name="submitLogin">
                                    <i class="icon-ok text-success"></i>
                                    {#send#}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <p><i class="far fa-copyright"></i> 2008{if 'Y'|date !== '2008'} - {'Y'|date}{/if} <a href="http://www.magix-cms.com/" class="targetblank">Magix CMS</a> &mdash; {#all_right_reserved#}</p>
        </div>
    </main>
{/block}
{block name="footer"}{/block}
{block name="foot" append}
    {script src="/{baseadmin}/min/?f={baseadmin}/template/js/login.min.js" type="javascript"}
{/block}