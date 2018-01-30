{extends file="layout.tpl"}
{*{block name="title"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'1','default'=>{$smarty.config.seo_t_static_signup|sprintf:$companyData.name}]}{/block}*}
{*{block name="description"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'2','default'=>{$smarty.config.seo_d_static_signup|sprintf:$companyData.name}]}{/block}*}
{block name="recaptcha"}<script src='https://www.google.com/recaptcha/api.js'></script>{/block}
{block name='body:id'}signup{/block}
{block name="main"}
    <main id="content">
        {block name="article:before"}{/block}
        {block name='article'}
            <article id="article" class="container">
                {block name='article:content'}
                    <div class="row row-center">
                        <div class="col-ph-12 col-sm-6">
                            <div class="content-box">
                                <h1 class="h3 text-center">{#signup_root_h1#|ucfirst}</h1>
                                <div class="clearfix mc-message"></div>
                                <form id="signup-form" method="post" action="{geturl}/{getlang}/account/signup/" class="validate_form nice-form static_feedback">
                                    <div class="form-group">
                                        <input type="text" class="form-control required" id="firstname_ac" name="account[firstname_ac]" placeholder="{#ph_firstname#|ucfirst}" required>
                                        <label for="firstname_ac" class="is_empty">{#account_firstname#|ucfirst}&nbsp;*</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control required" id="lastname_ac" name="account[lastname_ac]" placeholder="{#ph_lastname#|ucfirst}" required>
                                        <label for="lastname_ac" class="is_empty">{#account_lastname#|ucfirst}&nbsp;*</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control required" id="email_ac" name="account[email_ac]" placeholder="{#ph_mail#|ucfirst}" required>
                                        <label for="email_ac" class="is_empty">{#account_mail#|ucfirst}&nbsp;*</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control required" id="passwd" name="account[passwd]" placeholder="{#ph_password#|ucfirst}" required>
                                        <label for="passwd" class="is_empty">{#account_password#|ucfirst}&nbsp;*</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control required" id="repeat_passwd" name="account[repeat_passwd]" placeholder="{#ph_psw_conf#|ucfirst}" equalTo="#passwd" required>
                                        <label for="repeat_passwd" class="is_empty">{#account_password_confirm#|ucfirst}&nbsp;*</label>
                                    </div>
                                    {if isset($newsletter) && $newsletter}
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label for="signup_newsletter">
                                                    <input type="checkbox" name="accout[newsletter]" id="signup_newsletter"> {$smarty.config.account_signup_news|sprintf:$companyData.name|ucfirst}
                                                </label>
                                            </div>
                                        </div>
                                    {/if}
                                    {strip}
                                    {capture name="cond_gen"}
                                        <a class="targetblank" href="{geturl}{#cond_gen_uri#}" title="{#cond_gen#}">{#cond_gen#}</a>
                                    {/capture}
                                    {capture name="private_laws"}
                                        <a class="targetblank" href="{geturl}{#private_laws_uri#}" title="{#private_laws#}">{#private_laws#}</a>
                                    {/capture}
                                    {/strip}
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label for="cond_gen">
                                                <input type="checkbox" name="cond_gen" id="cond_gen" class="required" required><small>&nbsp;{#account_cond_gen#|ucfirst|sprintf:$smarty.capture.cond_gen:$smarty.capture.private_laws}&nbsp;*</small>
                                            </label>
                                        </div>
                                    </div>
                                    {if $googleRecaptcha.google_recaptcha eq '1'}
                                        <div class="g-recaptcha" data-sitekey="{$googleRecaptcha.recaptchaApiKey}"></div>
                                        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                                        <script type="text/javascript"
                                                src="https://www.google.com/recaptcha/api.js?hl={getlang}">
                                        </script>
                                    {/if}
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-box btn-block btn-main-theme">{#account_signup#|ucfirst}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                {/block}
            </article>
        {/block}

        {block name="article:after"}{/block}
    </main>
{/block}

{block name="foot"}
    {script src="/min/?g=form" concat=$concat type="javascript"}
    {script src="/min/?f=skin/{template}/js/form.min.js" concat=$concat type="javascript"}
    {script src="/min/?f={if {getlang} !== "en"}libjs/vendor/localization/messages_{getlang}.js{/if},skin/{template}/js/vendor/localization/messages_{getlang}.js" concat=$concat type="javascript"}
    {script src="/min/?f=plugins/account/js/public.min.js" concat=$concat type="javascript"}
    <script type="text/javascript">
        var url = '{geturl}';
        var iso = '{getlang}';
        $(function(){
            if (typeof globalForm == "undefined")
            {
                console.log("globalForm is not defined");
            }else{
                globalForm.run();
            }
            if (typeof account == "undefined")
            {
                console.log("account is not defined");
            }else{
                account.signup(url,iso);
            }
        });
    </script>
{/block}