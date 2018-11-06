{*<div id="block-contact" class="col-12 col-sm-5 col-md-4 block">
    <h4>{#contact_label_title#|ucfirst}</h4>
    <ul class="list-unstyled">
        {if !empty($companyData.contact.adress)}
        <li>
            <i class="material-icons">place</i>
            <span>{$companyData.contact.adress.street}</span>,
            <span>{$companyData.contact.adress.postcode}</span>
            <span>{$companyData.contact.adress.city}</span>
        </li>
        {/if}
        {if $companyData.contact.phone}
            <li class="phone-number">
                <i class="material-icons">local_phone</i> {$companyData.contact.phone}
            </li>
        {/if}
        {if $companyData.contact.mobile}
            <li class="phone-number">
                <i class="material-icons">smartphone</i> {$companyData.contact.mobile}
            </li>
        {/if}
        {if $companyData.contact.fax}
            <li class="phone-number">
                <span class="fa fa-fax"></span> {$companyData.contact.fax}
            </li>
        {/if}
        {if $companyData.contact.mail}
            <li class="mailto">
                <i class="material-icons">local_post_office</i> {mailto address={$companyData.contact.mail} encode="hex"}
            </li>
        {/if}
    </ul>
</div>*}
<div id="block-contact" class="col-12 block">
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