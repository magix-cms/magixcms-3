{if !isset($icon)}{$icon = true}{/if}
<div id="block-contact" class="col-12 col-xs-6 col-sm-4 col-xl-3 block">
    <p class="h4">{#contact_label_title#|ucfirst}</p>
    <ul class="list-unstyled">
        {if $companyData.contact.adress.street !== null}
        <li class="contact-address">
            {if $icon}<i class="material-icons ico ico-location"></i>{/if}
            {$companyData.contact.adress.street}, {$companyData.contact.adress.postcode} {$companyData.contact.adress.city}
        </li>
        {/if}
        {if $companyData.contact.phone}
        <li class="contact-number">
            {if $icon}<i class="material-icons ico ico-phone"></i>{/if}
            {if $companyData.contact.click_to_call}<a href="tel:{$companyData.contact.phone|replace:'(0)':''|replace:' ':''|replace:'.':''}">{/if}
                {$companyData.contact.phone}
            {if $companyData.contact.click_to_call}</a>{/if}
        </li>
        {/if}
        {if $companyData.contact.mobile}
        <li class="contact-number">
            {if $icon}<i class="material-icons ico ico-smartphone"></i>{/if}
            {if $companyData.contact.click_to_call}<a href="tel:{$companyData.contact.mobile|replace:'(0)':''|replace:' ':''|replace:'.':''}">{/if}
                {$companyData.contact.mobile}
            {if $companyData.contact.click_to_call}</a>{/if}
        </li>
        {/if}
        {if $companyData.contact.fax}
        <li class="contact-number">
            {if $icon}<i class="material-icons ico ico-print"></i>{/if}
            {$companyData.contact.fax}
        </li>
        {/if}
        {if $companyData.contact.mail}
        <li class="mailto">
            {if $icon}<i class="material-icons ico ico-email"></i>{/if}
            {if $companyData.contact.click_to_mail}
                {mailto address={$companyData.contact.mail} encode="hex"}
            {else}
                {if $companyData.contact.crypt_mail}
                    {$companyData.contact.mail|replace:'@':'<span class="fas ico ico-at"></span>'}
                {else}
                    {$companyData.contact.mail}
                {/if}
            {/if}
        </li>
        {/if}
    </ul>
</div>