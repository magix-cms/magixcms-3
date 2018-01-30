{extends file="layout.tpl"}
{*{block name="title"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'1','default'=>#seo_t_static_login#]}{/block}*}
{*{block name="description"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'2','default'=>#seo_d_static_login#]}{/block}*}
{block name='body:id'}login{/block}

{block name="main"}
    <main id="content">
        {block name="article:before"}{/block}

        {block name='article'}
            <article id="article" class="container">
                <h1 class="h3">{#login_root_h1#|ucfirst}</h1>
                {block name="article:content"}
                    <div class="row row-center">
                        <div id="login-box" class="col-ph-12 col-sm-6">
                            <div class="content-box">
                                <form id="login-form" method="post" action="{geturl}/{getlang}/account/login/" class="nice-form">
                                    <div class="clearfix mc-message">{$message}</div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{#ph_mail#|ucfirst}" id="email_ac" name="account[email_ac]" />
                                        <label for="email_ac" class="is_empty">{#account_mail#|ucfirst}</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="{#ph_password#|ucfirst}" id="passwd_ac" name="account[passwd_ac]" />
                                        <label for="passwd_ac" class="is_empty">{#account_password#|ucfirst}</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="account[hashtoken]" value="{$hashpass}" />
                                        <button type="submit" class="btn btn-box btn-block btn-main-theme" value="">{#login_btn#|ucfirst}</button>
                                    </div>
                                </form>
                                <div class="row">
                                    <p class="col-ph-12 col-sm-6"><a href="{geturl}/{getlang}/account/signup/" title="{#create_btn#|ucfirst}">{#create_btn#|ucfirst}</a></p>
                                    <p class="col-ph-12 col-sm-6"><a data-target="#password-renew" data-toggle="modal" href="#">{#forget_password#|ucfirst}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                {/block}
            </article>
        {/block}

        {block name="article:after"}
            {include file="account/brick/password.tpl"}
        {/block}
    </main>
{/block}


{block name="foot" append}
{script src="/min/?g=form" concat=$concat type="javascript"}
{capture name="formjs"}{strip}
    /min/?f=skin/{template}/js/form.min.js
{/strip}{/capture}
{script src=$smarty.capture.formjs concat=$concat type="javascript" load='async'}
{*{script src="/min/?f=libjs/vendor/localization/messages_{getlang}.js,plugins/account/js/public.js" concat=$concat type="javascript"}
<script type="text/javascript">
    $.nicenotify.notifier = {
        box:"",
        elemclass : '.ajax-message'
    };
    var iso = '{getlang}';
    var hashurl = '/'+iso+'/account/';
    $(function(){
        if (typeof MC_account == "undefined")
        {
            console.log("MC_account is not defined");
        }else{
            MC_account.runNewPassword(iso,hashurl);
        }
    });
</script>*}
{/block}