{strip}
    {* facebook, news, cms, contact, newsletter*}
    {if !isset($blocks)}
        {assign var="blocks" value=['news','contact']}
    {/if}
    {widget_share_data assign="shareData"}
{/strip}
<footer id="footer"{if $touch} class="mobile-footer"{/if}>
    <div id="block-contact" class="block">
        <div class="container-fluid">
            <h4 class="sr-only">{#contact_label_title#|ucfirst}</h4>
            <div class="text-center">
                {*<i class="material-icons">place</i>*}
                {if !empty($companyData.contact.adress)}
                <p><span>{$companyData.contact.adress.street}, {$companyData.contact.adress.postcode} {$companyData.contact.adress.city}</span>
                </p>{/if}
                {strip}{if $companyData.contact.phone || $companyData.contact.mobile}<p>
                    {if $companyData.contact.phone}{$companyData.contact.phone}{/if}
                    {if $companyData.contact.phone && $companyData.contact.mobile} &mdash; {/if}
                    {if $companyData.contact.mobile}{$companyData.contact.mobile}{/if}
                    </p>{/if}{/strip}
            </div>
        </div>
    </div>
    <div id="colophon">
        <div class="container-fluid">
            {include file="section/footer/about.tpl"}
        </div>
    </div>
</footer>
{if $touch}{include file="section/footer/footbar.tpl"}{else}{include file="section/nav/btt.tpl"}{/if}