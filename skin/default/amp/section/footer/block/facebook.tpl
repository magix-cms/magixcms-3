<div id="block-facebook" class="col block">
{if $companyData.socials.facebook != ''}
    <amp-facebook-page width="340" height="130"
                       layout="responsive"
                       data-hide-cover="true"
                       data-href="{$companyData.socials.facebook}">
    </amp-facebook-page>
{/if}
</div>