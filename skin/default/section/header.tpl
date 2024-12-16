<header id="header"{if !$touch} class="at-top"{/if}>
    <div class="container">
        {* Show Nav Button (xs ad sm only) *}
        <button type="button" class="toggle-menu navbar-toggle hidden-xs-down" data-toggle="collapse" data-target="#menu">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="sr-only">{#openNavigation#|ucfirst}</span>
        </button>
        {* Brand && Headline *}
        <div class="site-name">
            {if $logo && $logo.img.active eq 1}
                {capture name="sizes"}{$logo.img.small.w}px{/capture}
                <a href="{if $dataLang != null && count($dataLang) > 1}{$url}/{$lang}/{else}{$url}/{/if}" title="{if !empty($logo.img.title)}{$logo.img.title|ucfirst}{else}{#logo_link_title#|ucfirst}{/if}">
                    {strip}
                        <picture>
                        <!--[if IE 9]><video style="display: none;"><![endif]-->
                        {if isset({$logo.img.small.src_webp})}<source type="image/webp" sizes="{$smarty.capture.sizes}" srcset="{$url}{$logo.img.small.src_webp} {$logo.img.small.w}w, {$url}{$logo.img.medium.src_webp} {$logo.img.medium.w}w, {$url}{$logo.img.large.src_webp} {$logo.img.large.w}w">{/if}
                        <source type="{$logo.img.small.ext}" sizes="{$smarty.capture.sizes}" srcset="{$url}{$logo.img.small.src} {$logo.img.small.w}w, {$url}{$logo.img.medium.src} {$logo.img.medium.w}w, {$url}{$logo.img.large.src} {$logo.img.large.w}w">
                        <!--[if IE 9]></video><![endif]-->
                        <img src="{$url}{$logo.img.small.src}" sizes="{$smarty.capture.sizes}" srcset="{$url}{$logo.img.small.src} {$logo.img.small.w}w, {$url}{$logo.img.medium.src} {$logo.img.medium.w}w, {$url}{$logo.img.large.src} {$logo.img.large.w}w" alt="{if !empty($logo.img.alt)}{$logo.img.alt}{else}{#logo_img_alt#|ucfirst} {$companyData.name}{/if}" />
                        </picture>{/strip}
                </a>
                {else}
                {capture name="sizes"}229px{/capture}
                <a href="{if $dataLang != null && count($dataLang) > 1}{$url}/{$lang}/{else}{$url}/{/if}" title="{#logo_link_title#|ucfirst}">
                    {strip}
                        <picture>
                        <!--[if IE 9]><video style="display: none;"><![endif]-->
                        <source type="image/webp" sizes="{$smarty.capture.sizes}" srcset="{$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@229.webp 229w, {$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@480.webp 480w">
                        <source type="image/png" sizes="{$smarty.capture.sizes}" srcset="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png 229w, {$url}/skin/{$theme}/img/logo/png/{#logo_img#}@480.png 480w">
                        <!--[if IE 9]></video><![endif]-->
                        <img src="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png" sizes="{$smarty.capture.sizes}" srcset="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png 229w,{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@480.png 480w" alt="{#logo_img_alt#|ucfirst} {$companyData.name}" width="229" height="50"/>
                        </picture>{/strip}
                </a>
            {/if}
        </div>
        {if $dataLang != null && count($dataLang) > 1}
            <div class="select-lang hidden-xs-down">
                {include file="section/brick/lang.tpl" display='menu'}
            </div>
        {/if}
        {include file="section/menu/primary.tpl" menu="main" main=true mobile=false deepness=2}
    </div>
</header>