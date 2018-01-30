{*{if !$smarty.get.pstring2}
<ul class="nav nav-tabs record-menu" role="tablist">
    <li role="presentation"{if isset($smarty.get.tab)}{if $smarty.get.tab == 'account'} class="active"{/if}{else} class="active"{/if}>
        <a href="#account" aria-controls="account" role="tab" data-toggle="tab">{#pn_account#|ucfirst}</a>
    </li>
    {if $data.links eq '1'}
    <li role="presentation"{if isset($smarty.get.tab) && $smarty.get.tab == 'links'} class="active"{/if}>
        <a href="#links" aria-controls="links" role="tab" data-toggle="tab">{#pn_links#|ucfirst}</a>
    </li>
    {/if}
    {if $data.cartpay eq '1'}
    <li role="presentation">
        <a href="{$hashurl}order/">{#pn_order#|ucfirst}</a>
    </li>
    {/if}
</ul>
    {else}
    <ul class="nav nav-tabs record-menu" role="tablist">
        <li role="presentation">
            <a href="{$hashurl}?tab=account">{#pn_account#|ucfirst}</a>
        </li>
        {if $data.links eq '1'}
        <li role="presentation">
            <a href="{$hashurl}?tab=links">{#pn_links#|ucfirst}</a>
        </li>
        {/if}
        {if $data.cartpay eq '1'}
        <li role="presentation" {if $smarty.get.pstring2 eq 'order'}class="active"{/if}>
            <a href="{$hashurl}order/">{#pn_order#|ucfirst}</a>
        </li>
        {/if}
    </ul>
{/if}*}
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#global#}</a></li>
    <li role="presentation"><a href="#config" aria-controls="socials" role="tab" data-toggle="tab">{#account_config#}</a></li>
</ul>