<div id="block-contact" class="col-ph-12 col-sm-5 col-md-4 block">
    <h4>{#contact_label_title#|ucfirst}</h4>
    <ul class="list-unstyled">
        {if !empty($companyData.contact.adress)}
        <li>
            <span class="fa fa-map-marker"></span>
            <span>{$companyData.contact.adress.street}</span>,
            <span>{$companyData.contact.adress.postcode}</span>
            <span>{$companyData.contact.adress.city}</span>
        </li>
        {/if}
        {if $companyData.contact.phone}
            <li class="phone-number">
                <span class="fa fa-phone"></span> {$companyData.contact.phone}
            </li>
        {/if}
        {if $companyData.contact.mobile}
            <li class="phone-number">
                <span class="fa fa-mobile"></span> {$companyData.contact.mobile}
            </li>
        {/if}
        {if $companyData.contact.fax}
            <li class="phone-number">
                <span class="fa fa-fax"></span> {$companyData.contact.fax}
            </li>
        {/if}
        {if $companyData.contact.mail}
            <li class="mailto">
                <span class="fa fa-envelope"></span> {mailto address={$companyData.contact.mail} encode="hex"}
            </li>
        {/if}
    </ul>
</div>