<div id="block-contact" class="col-4 col-xs-6 col-sm col-lg-3 block">
    <p class="h4">{#contact_label_title#|ucfirst}</p>
    <ul class="list-unstyled">
        {if $companyData.contact.adress.street !== null}
        <li class="contact-address">
            <i class="icon material-icons icon icon-place"></i>{$companyData.contact.adress.street}, {$companyData.contact.adress.postcode} {$companyData.contact.adress.city}
        </li>
        {/if}
        {if $companyData.contact.phone}
        <li class="contact-number">
            <i class="icon material-icons icon icon-local_phone"></i>{$companyData.contact.phone}
        </li>
        {/if}
        {if $companyData.contact.mobile}
        <li class="contact-number">
            <i class="icon material-icons icon icon-smartphone"></i>{$companyData.contact.mobile}
        </li>
        {/if}
        {if $companyData.contact.fax}
        <li class="contact-number">
            <i class="icon material-icons icon icon-print"></i>{$companyData.contact.fax}
        </li>
        {/if}
        {if $companyData.contact.mail}
        <li class="mailto">
            <i class="icon material-icons icon icon-email"></i>{mailto address={$companyData.contact.mail} encode="hex"}
        </li>
        {/if}
    </ul>
</div>