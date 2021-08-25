{if $setting.amp.value}{$amp_setting = true}{else}{$amp_setting = false}{/if}
<!DOCTYPE html>
<html lang="{$lang}" dir="ltr">
<head id="meta" {block name="ogp"}{include file="section/brick/ogp-protocol.tpl"}{/block}>
    <meta charset="utf-8">
    <title itemprop="headline">{capture name="title"}{block name="title"}{/block}{/capture}{$smarty.capture.title}</title>
    <meta name="description" content="Site en maintenance">
    <meta itemprop="description" content="Site en maintenance">
    <meta name="robots" content="{$setting['robots']['value']}">
    {strip}{include file="section/loop/lang.tpl" amp=false amp_active=$amp_setting iso={$lang}}{/strip}
    {strip}{include file="section/brick/canonical.tpl" amp=false amp_active=$amp_setting}{/strip}
    {*{if {module type="news"} eq true}<link rel="alternate" type="application/rss+xml" href="{$url}/news_{$lang}_rss.xml" title="RSS">{/if}*}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5.0">
    {if $googleTools_webmaster !== ''}<meta name="google-site-verification" content="{$googleTools_webmaster}">{/if}
    {if $domain != null && $domain.tracking_domain != ''}{$domain.tracking_domain}{/if}
    <link rel="icon" type="image/png" href="{if $favicon != null}{$url}{$favicon.img.png.src}{else}{$url}/skin/{$theme}/img/favicon.png{/if}" />
    <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="{if $favicon != null}{$url}{$favicon.img.ico.src}{else}{$url}/skin/{$theme}/img/favicon.ico{/if}" /><![endif]-->
    <link rel="manifest" href="{$url}/skin/{$theme}/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{$smarty.capture.title}">
    <link rel="apple-touch-icon" href="{if $homescreen != null}{if $homescreen.img[168]}{''|cat:{$url}|cat:$homescreen.img[168].src}{/if}{else}{''|cat:{$url}|cat:'/skin/'|cat:{$theme}|cat:'/img/touch/homescreen168.png'}{/if}">
    {if $variant !== null}{include file="{$variant}.tpl"}{/if}
    {* use css2 only if you use font with variable weight or if the old api doesn't work *}
    {* valid syntax exemple
        old api
        ['Raleway' => '300,500,700']            Raleway font family with 300, 500 and 700 weight
        ['Raleway' => '300,500,500italic,700']  Raleway font family with 300, 500 normal and italic and 700 weight
        new additionnal css2 api, css2=true needed
        ['Raleway' => '300..700']               Raleway font family with 300 to 700 weight
        ['Raleway' => '300..700italic']         Raleway font family with 300 to 700 weight italic
        ['Raleway' => '300,400..700,400..700italic,900']    Raleway font family with 300 weight, 400 to 700 weight normal and italic, 900 weight
    *}
    {include file="section/brick/google-font.tpl" fonts=['Roboto'=>'300,400,400italic,700'] css2=false}
    {* Please try to use icomoon to create a small iconfont and reduce load work, uncomment the next lines if you use icofont *}
    <link rel="preload" href="{$url}/skin/{$theme}/fonts/Icofont.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="{$url}/skin/{$theme}/fonts/Icofont.woff" as="font" type="font/woff" crossorigin="anonymous">
    <meta name="theme-color" content="#3C62AA" />
    {$basecss = [
        "/skin/{$theme}/css/properties{if $setting.mode.value !== 'dev'}.min{/if}.css",
        "/skin/{$theme}/css/{$viewport}{if $setting.mode.value !== 'dev'}.min{/if}.css",
        "/skin/{$theme}/css/maintenance{if $setting.mode.value !== 'dev'}.min{/if}.css"
    ]}
    {$css_files = []}
    {block name="styleSheet"}{/block}
    {include file="section/brick/css.tpl" css_files=array_merge($basecss,$css_files)}
    {if $setting['analytics']['value']}{include file="section/brick/analytics.tpl" aid=$setting['analytics']['value']}{/if}
    <link rel="preconnect" href="https://cdn.jsdelivr.net"/>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net"/>
</head>
<body id="maintenance" class="{$bodyClass}{if $touch} touchscreen{/if} {block name='body:class'}{/block}" itemscope itemtype="http://schema.org/{block name="webType"}WebPage{/block}" itemref="meta">
<main id="content">
    <article class="container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        <div class="row row-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 text-center">
                {if $logo && $logo.img.active eq 1}
                    {capture name="sizes"}{$logo.img.large.w}px{/capture}
                    {strip}
                        <picture>
                        <!--[if IE 9]><video style="display: none;"><![endif]-->
                        {if isset({$logo.img.small.src_webp})}<source type="image/webp" sizes="{$smarty.capture.sizes}" srcset="{$url}{$logo.img.small.src_webp} {$logo.img.small.w}w, {$url}{$logo.img.medium.src_webp} {$logo.img.medium.w}w, {$url}{$logo.img.large.src_webp} {$logo.img.large.w}w">{/if}
                        <source type="{$logo.img.small.ext}" sizes="{$smarty.capture.sizes}" srcset="{$url}{$logo.img.small.src} {$logo.img.small.w}w, {$url}{$logo.img.medium.src} {$logo.img.medium.w}w, {$url}{$logo.img.large.src} {$logo.img.large.w}w">
                        <!--[if IE 9]></video><![endif]-->
                        <img src="{$url}{$logo.img.small.src}" sizes="{$smarty.capture.sizes}" srcset="{$url}{$logo.img.small.src} {$logo.img.small.w}w, {$url}{$logo.img.medium.src} {$logo.img.medium.w}w, {$url}{$logo.img.large.src} {$logo.img.large.w}w" alt="{if !empty($logo.img.alt)}{$logo.img.alt}{else}{#logo_img_alt#|ucfirst} {$companyData.name}{/if}" />
                        </picture>{/strip}
                {else}
                    {capture name="sizes"}480px{/capture}
                    {strip}
                        <picture>
                        <!--[if IE 9]><video style="display: none;"><![endif]-->
                        <source type="image/webp" sizes="{$smarty.capture.sizes}" srcset="{$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@229.webp 229w, {$url}/skin/{$theme}/img/logo/webp/{#logo_img#}@480.webp 480w">
                        <source type="image/png" sizes="{$smarty.capture.sizes}" srcset="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png 229w, {$url}/skin/{$theme}/img/logo/png/{#logo_img#}@480.png 480w">
                        <!--[if IE 9]></video><![endif]-->
                        <img src="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png" sizes="{$smarty.capture.sizes}" srcset="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@229.png 229w,{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@480.png 480w" alt="{#logo_img_alt#|ucfirst} {$companyData.name}" width="480" height="100"/>
                        </picture>{/strip}
                {/if}
                <h1>Le site est actuellement en maintenance. Il sera de nouveau accessible bientôt. Merci de votre compréhension.</h1>
            </div>
        </div>
    </article>
</main>
</body>
</html>