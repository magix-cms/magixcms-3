<header id="top" class="header" role="banner">
    <nav class="header-inner">
        <div id="toggleMenu" role="button" on="tap:sidebar1.toggle,toggleMenu.toggleClass(class='open')" tabindex="0" class="hamburger">
            {*<i class="material-icons">menu</i>*}
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="sr-only">{#openNavigation#|ucfirst}</span>
        </div>
        <div class="site-name">
            {capture name="sizes"}(min-width: 1200px) 10vw, (min-width: 992px) 15vw, (orientation: landscape) and (min-width: 768px) 20vw, (orientation: portrait) and (min-width: 480px) 25vw, 200px{/capture}
            {*<a href="{$url}/{$lang}/amp/" title="{#logo_link_title#|ucfirst}">
            <amp-img src="{$url}/skin/{$theme}/img/logo/{#logo_amp#}"
                     alt="Logo de Magix CMS"
                     height="50"
                     width="229">
            </amp-img>
            </a>*}
            {if $logo && $logo.img.active eq 1}
                <a href="{$url}/{$lang}/amp/" title="{if !empty($logo.img.title)}{$logo.img.title|ucfirst}{else}{#logo_link_title#|ucfirst}{/if}">
                    <amp-img alt="{#logo_img_alt#|ucfirst} {$companyData.name}"
                             sizes="{$smarty.capture.sizes}"
                             src="{$url}{$logo.img.small.src_webp}"
                             srcset="{$url}{$logo.img.small.src_webp} {$logo.img.small.w}w, {$url}{$logo.img.medium.src_webp} {$logo.img.medium.w}w, {$url}{$logo.img.large.src_webp} {$logo.img.large.w}w"
                             width="{$logo.img.small.w}"
                             height="{$logo.img.small.h}"
                             layout="responsive">
                        <amp-img alt="{#logo_img_alt#|ucfirst} {$companyData.name}"
                                 fallback
                                 sizes="{$smarty.capture.sizes}"
                                 src="{$url}{$logo.img.small.src}"
                                 srcset="{$url}{$logo.img.small.src} {$logo.img.small.w}w, {$url}{$logo.img.medium.src} {$logo.img.medium.w}w, {$url}{$logo.img.large.src} {$logo.img.large.w}w"
                                 width="{$logo.img.small.w}"
                                 height="{$logo.img.small.h}"
                                 layout="responsive">
                        </amp-img>
                    </amp-img>
                </a>
            {else}
                <a href="{$url}/{$lang}/amp/" title="{#logo_link_title#|ucfirst}">
                    <amp-img alt="{#logo_img_alt#|ucfirst} {$companyData.name}"
                             sizes="{$smarty.capture.sizes}"
                             src="{$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@229.webp"
                             srcset="{$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@229.webp 229w,{$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@480.webp 480w"
                             width="229"
                             height="50"
                             layout="responsive">
                        <amp-img alt="{#logo_img_alt#|ucfirst} {$companyData.name}"
                                 fallback
                                 sizes="{$smarty.capture.sizes}"
                                 src="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png"
                                 srcset="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png 229w,{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@480.png 480w"
                                 width="229"
                                 height="50"
                                 layout="responsive">
                        </amp-img>
                    </amp-img>
                </a>
            {/if}
        </div>
        {if $dataLang != null && count($dataLang) > 1}
            <div class="select-lang">
                {include file="amp/section/brick/lang.tpl" display='menu'}
            </div>
        {/if}
    </nav>
</header>
{include file="amp/section/menu/sidebar.tpl" menu="side" main=true mobile=true deepness=2}