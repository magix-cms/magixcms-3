<div id="map">
    <div class="contact-infos">
        <p class="lead">{#info_contact#|ucfirst}</p>
        {if $companyData.contact.mail}
            <p class="email">
                <i class="material-icons ico ico-email"></i>
                {if $companyData.contact.click_to_mail}
                    {mailto address={$companyData.contact.mail} encode="hex"}
                {else}
                    {if $companyData.contact.crypt_mail}
                        {*{$companyData.contact.mail|replace:'@':'[at]'}*}
                        {$companyData.contact.mail|replace:'@':'<span class="fa ico ico-at"></span>'}
                    {else}
                        {$companyData.contact.mail}
                    {/if}
                {/if}
            </p>
        {/if}
        {if $companyData.contact.phone}
            <p><i class="material-icons ico ico-phone"></i>{if $companyData.contact.click_to_call}<a href="tel:{$companyData.contact.phone|replace:'(0)':''|replace:' ':''|replace:'.':''}">{/if}{$companyData.contact.phone}{if $companyData.contact.click_to_call}</a>{/if}</p>
        {/if}
        {if $companyData.contact.mobile}
            <p><i class="material-icons ico ico-smartphone"></i>{if $companyData.contact.click_to_call}<a href="tel:{$companyData.contact.mobile|replace:'(0)':''|replace:' ':''|replace:'.':''}">{/if}{$companyData.contact.mobile}{if $companyData.contact.click_to_call}</a>{/if}</p>
        {/if}
        {if $companyData.contact.fax}
            <p><i class="material-icons ico ico-print"></i>{$companyData.contact.fax}</p>
        {/if}
        {if $companyData.contact.adress.street}
            <p itemscope itemtype="http://schema.org/PostalAddress" class="address">
                <i class="material-icons ico ico-place"></i> {$companyData.contact.adress.street}, {$companyData.contact.adress.postcode} {$companyData.contact.adress.city}
            </p>
        {/if}
    </div>
    {if $companyData.openinghours}
        <div class="schedule">
            {include file="about/brick/openinghours.tpl"}
        </div>
    {/if}
    {*<h3 class="title-block">{#plan_acces#|ucfirst}</h3>
    <a href="{$url}/{$lang}/gmap/" title="{#plan_acces#|ucfirst}">
        <img src="/skin/{$theme}/img/map.jpg" title="{#plan_acces#|ucfirst}" alt="{#plan_acces#|ucfirst}" class="img-responsive" width="480" height="360"/>
    </a>*}
</div>