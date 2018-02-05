{extends file="layout.tpl"}
{*{block name="title"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'1','default'=>#my_account#]}{/block}*}
{*{block name="description"}{seo_rewrite config_param=['level'=>'0','idmetas'=>'2','default'=>#my_account#]}{/block}*}
{block name='body:id'}account-edit{/block}

{block name="article:content"}
    <header>
        <h1 class="text-center">{#global#|ucfirst}</h1>
    </header>

    {include file="account/forms/account.tpl"}
{/block}

{block name="foot"}
    {script src="/min/?g=form" concat=$concat type="javascript"}
    {script src="/min/?f=skin/{template}/js/form.min.js" concat=$concat type="javascript"}
    {if {getlang} !== "en"}
        {script src="/min/?f=libjs/vendor/localization/messages_{getlang}.js" concat=$concat type="javascript"}
    {/if}
    <script type="text/javascript">
        $(function(){
            if (typeof globalForm === "undefined")
            {
                console.log("globalForm is not defined");
            }else{
                globalForm.run();
            }
        });
    </script>
{/block}