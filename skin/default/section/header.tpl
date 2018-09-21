<header id="header" class="header" role="navigation"{if !$touch}class="at-top"{/if}>
    <div class="container">
    {* Show Nav Button (xs ad sm only) *}
    <button type="button" class="toggle-menu navbar-toggle" data-toggle="collapse" data-target="#menu">
        <i class="material-icons">menu</i>
        <span class="sr-only">{#openNavigation#|ucfirst}</span>
    </button>
    {* Brand && Headline *}
    <div class="site-name">
        <a href="{$url}/{$lang}/" title="{#logo_link_title#|ucfirst}">
            {strip}{capture name="sizes"}
                (min-width: 1200px) 10vw,
                (min-width: 992px) 15vw,
                (orientation: landscape) and (min-width: 768px) 20vw,
                (orientation: portrait) and (min-width: 480px) 25vw,
                200px
            {/capture}
            <picture>
            <!--[if IE 9]><video style="display: none;"><![endif]-->
            <source type="image/webp"
                    sizes="{$smarty.capture.sizes}"
                    srcset="{$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@229.webp 229w,
                            {$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@480.webp 480w">
            <source sizes="{$smarty.capture.sizes}"
                    srcset="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png 229w,
                            {$url}/skin/{$theme}/img/logo/png/{#logo_img#}@480.png 480w">
            <!--[if IE 9]></video><![endif]-->
            <img src="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png"
                 sizes="{$smarty.capture.sizes}"
                 srcset="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png 229w,
                        {$url}/skin/{$theme}/img/logo/png/{#logo_img#}@480.png 480w"
                 alt="{#logo_img_alt#|ucfirst} {$companyData.name}" />
            </picture>{/strip}
        </a>
    </div>
    {include file="section/menu/primary.tpl" menu="main" main=true mobile=false deepness=2}
    {if $dataLang != null && count($dataLang) > 1}
        <div class="select-lang">
            {include file="section/brick/lang.tpl" display='menu'}
        </div>
    {/if}
    </div>
</header>
{include file="section/menu/primary.tpl" menu="side" main=false mobile=true deepness=2}