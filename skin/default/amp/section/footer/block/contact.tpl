<div id="block-contact" class="col-ph-12 col-sm-5 col-md-4 block">
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
</div>