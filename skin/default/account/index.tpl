{extends file="layout.tpl"}
{*{block name="title"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'1','default'=>#my_account#]}{/block}*}
{*{block name="description"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'2','default'=>#my_account#]}{/block}*}
{block name='body:id'}account-private{/block}

{block name="article:content"}
    <header>
        <h1>{#my_account#|ucfirst}</h1>
        {include file="account/brick/nav.tpl" data=$getConfigData}
    </header>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade{if isset($smarty.get.tab)}{if $smarty.get.tab == 'general'} in active{/if}{else} in active{/if}" id="general">
            {include file="account/forms/account.tpl" data=$dataAccount}
        </div>
        <div role="tabpanel" class="tab-pane fade{if isset($smarty.get.tab) && $smarty.get.tab == 'config'} in active{/if}" id="config">
            {*{include file="account/forms/links.tpl" data=$dataAccount}*}
        </div>
    </div>
{/block}

{*{block name="foot" append}
    {script src="/min/?g=form" concat=$concat type="javascript"}
    {capture name="formjs"}{strip}
        /min/?f=skin/{template}/js/form.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.formjs concat=$concat type="javascript" load='async'}
    {script src="/min/?f=plugins/account/js/public.js" concat=$concat type="javascript"}
    <script type="text/javascript">
        var hashurl = "{$hashurl}",
            iso = "{getlang}";
        $(function(){
            if (typeof MC_account == "undefined")
            {
                console.log("MC_account is not defined");
            }else{
                MC_account.runPrivate(iso,hashurl);
            }
        });
    </script>
{/block}*}
