{extends file="layout.tpl"}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>#seo_login_title#]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>#seo_login_desc#]}{/block}
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
            {include file="account/modal/password.tpl"}
        {/block}
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
                account.login(url,iso);
            }
        });
    </script>
{/block}