{extends file="layout.tpl"}
{*{block name="title"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'1','default'=>#my_account#]}{/block}*}
{*{block name="description"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'2','default'=>#my_account#]}{/block}*}
{block name='body:id'}account-config{/block}

{block name="article:content"}
    <header>
        <h1 class="text-center">{#account_coonfig#|ucfirst}</h1>
    </header>

    {include file="account/forms/config.tpl"}
{/block}

{block name="foot"}
    {script src="/min/?g=form" concat=$concat type="javascript"}
    {script src="/min/?f=skin/{template}/js/form.min.js" concat=$concat type="javascript"}
    {script src="/min/?f={if {getlang} !== "en"}libjs/vendor/localization/messages_{getlang}.js{/if},skin/{template}/js/vendor/localization/messages_{getlang}.js" concat=$concat type="javascript"}
    {script src="/min/?f=plugins/account/js/public.min.js" concat=$concat type="javascript"}
    <script type="text/javascript">
        var url = '{geturl}';
        var iso = '{getlang}';
        var hash = '{$smarty.get.hash}';
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
                account.config(url,iso,hash);
            }
        });
    </script>
{/block}