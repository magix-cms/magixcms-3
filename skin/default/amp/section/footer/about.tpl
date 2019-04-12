<div class="footer-colophon text-center" itemprop="isPartOf" itemscope itemtype="http://schema.org/WebSite">
    <div itemprop="copyrightHolder" itemscope itemtype="http://schema.org/{$companyData.type}"{if $smarty.get.magixmod == 'contact' && $companyData.openinghours} itemref="schedule"{/if}>
        <meta itemprop="name" content="{$companyData.name}"/>
        <meta itemprop="url" content="{$url}"/>
        <meta itemprop="brand" content="{$companyData.name}"/>
        {if $companyData.tva}<meta itemprop="vatID" content="{$companyData.tva}">{/if}
        {if $about != null}
            <meta itemprop="sameAs" content="{$url}/{$lang}/about/"/>
        {/if}
        {if $gmap}
            <meta itemprop="hasMap" content="{$url}/{$lang}/gmap/"/>
        {/if}
        <div itemprop="logo image" itemscope itemtype="https://schema.org/ImageObject">
            {if $logo && $logo.img.active eq 1}
                <meta itemprop="url" content="{$url}{$logo.img.medium.src}">
                <meta itemprop="width" content="{$url}{$logo.img.medium.w}">
                <meta itemprop="height" content="{$url}{$logo.img.medium.h}">
            {else}
                <meta itemprop="url" content="{$url}/skin/{$theme}/img/logo/{#logo_img_mail#}">
                <meta itemprop="width" content="229">
                <meta itemprop="height" content="50">
            {/if}
        </div>
        {if $companyData.contact.phone}
            <meta itemprop="telephone" content="{$companyData.contact.phone}"/>
        {/if}
        {if $companyData.socials != null}
            {if $companyData.socials.facebook != null || $companyData.socials.google != null || $companyData.socials.linkedin != null}
                <div id="socials-links">
                    {if $companyData.socials.facebook != null}
                        <meta itemprop="sameAs" content="{$companyData.socials.facebook}"/>
                    {/if}
                    {if $companyData.socials.twitter != null}
                        <meta itemprop="sameAs" content="{$companyData.socials.twitter}"/>
                    {/if}
                    {if $companyData.socials.google != null}
                        <meta itemprop="sameAs" content="{$companyData.socials.google}"/>
                    {/if}
                    {if $companyData.socials.linkedin != null}
                        <meta itemprop="sameAs" content="{$companyData.socials.linkedin}"/>
                    {/if}
                </div>
            {/if}
        {/if}
        {if $companyData.contact.adress.street}
            <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                <meta itemprop="streetAddress" content="{$companyData.contact.adress.street}"/>
                <meta itemprop="postalCode" content="{$companyData.contact.adress.postcode}"/>
                <meta itemprop="addressLocality" content="{$companyData.contact.adress.city}"/>
            </div>
        {/if}
        {if $companyData.openinghours && $companyData.type === "LocalBusiness"}
            {$open_days = array()}
            {$open = ''}
            {$close = ''}
            {foreach $companyData.specifications as $day => $specific}
                {if $specific.open_day}
                    {$open_days[] = $day}

                    {if $open == '' || $specific.open_time < $open}
                        {$open = $specific.open_time}
                    {/if}

                    {if $close == '' || $specific.close_time > $close}
                        {$close = $specific.close_time}
                    {/if}
                {/if}
            {/foreach}
            {$open_days = ','|implode:$open_days}
            <meta itemprop="openingHours" content="{$open_days} {$open}-{$close}">
            {foreach $companyData.specifications as $day => $specific}
                {if $specific.open_day}
                    <div itemprop="openingHoursSpecification" itemscope itemtype="http://schema.org/OpeningHoursSpecification">
                        <meta itemprop="dayOfWeek" content="{$day}" />
                        {if $specific.noon_time}
                            <meta itemprop="opens" content="{$specific.open_time}" />
                            <meta itemprop="closes" content="{$specific.noon_start}" />
                            <meta itemprop="opens" content="{$specific.noon_end}" />
                            <meta itemprop="closes" content="{$specific.close_time}" />
                        {else}
                            <meta itemprop="opens" content="{$specific.open_time}" />
                            <meta itemprop="closes" content="{$specific.close_time}" />
                        {/if}
                    </div>
                {/if}
            {/foreach}
        {/if}
        <div id="contactPoint" itemprop="contactPoint" itemscope itemtype="http://schema.org/ContactPoint">
            {if $companyData.contact.mail}
                <meta itemprop="email" content="{$companyData.contact.mail}"/>
            {/if}
            {if $companyData.contact.phone}
                <meta itemprop="telephone" content="{$companyData.contact.phone}"/>
            {else}
                <meta itemprop="url" content="{$url}/{$lang}/contact/"/>
            {/if}
            {if $companyData.contact.fax}
                <meta itemprop="faxNumber" content="{$companyData.contact.fax}"/>
            {/if}
            <meta itemprop="contactType" content="customer support"/>
            {$av_langs = ','|explode:$companyData.contact.languages}
            {foreach $av_langs as $lang}
                <meta itemprop="availableLanguage" content="{$lang}"/>
            {/foreach}
        </div>
        {if $companyData.contact.mobile}
            <div id="contactPointMobile" itemprop="contactPoint" itemscope itemtype="http://schema.org/ContactPoint">
                <meta itemprop="telephone" content="{$companyData.contact.mobile}"/>
                <meta itemprop="contactType" content="customer support"/>
                {$av_langs = ','|explode:$companyData.contact.languages}
                {foreach $av_langs as $lang}
                    <meta itemprop="availableLanguage" content="{$lang}"/>
                {/foreach}
            </div>
        {/if}
    </div>
    <div>
        <p><i class="material-icons">copyright</i> <span itemprop="copyrightYear">2017{if 'Y'|date != '2017'} - {'Y'|date}{/if}</span>
            | {$companyData.name}, {#footer_all_rights_reserved#|ucfirst}</p>
    </div>
    {if $companyData.tva}
        <div>
            <p>{#footer_tva#} {$companyData.tva}</p>
        </div>
    {/if}
    <div>
        {include file="amp/section/footer/powered.tpl"}
    </div>
</div>