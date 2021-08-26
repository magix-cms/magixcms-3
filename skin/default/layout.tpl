{if $setting.amp.value}{$amp_setting = true}{else}{$amp_setting = false}{/if}
<!DOCTYPE html>
<html lang="{$lang}" dir="ltr">
<head id="meta" {block name="ogp"}{include file="section/brick/ogp-protocol.tpl"}{/block}>
    <meta charset="utf-8">
    <title itemprop="headline">{capture name="title"}{block name="title"}{/block}{/capture}{$smarty.capture.title}</title>
    <meta name="description" content="{capture name="description" nocache}{block name="description" nocache}{/block}{/capture}{$smarty.capture.description}">
    <meta itemprop="description" content="{$smarty.capture.description}">
    <meta name="robots" content="{$setting['robots']['value']}">
    {strip}{include file="section/loop/lang.tpl" amp=false amp_active=$amp_setting iso={$lang}}{/strip}
    {strip}{include file="section/brick/canonical.tpl" amp=false amp_active=$amp_setting}{/strip}
    {*{if {module type="news"} eq true}<link rel="alternate" type="application/rss+xml" href="{$url}/news_{$lang}_rss.xml" title="RSS">{/if}*}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5.0">
    {include file="section/brick/socials.tpl" title=$smarty.capture.title description=$smarty.capture.description}
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
    {*{strip}{capture name="stylesheet"}/skin/{$theme}/css/{$viewport}{if $setting.mode.value !== 'dev'}.min{/if}.css{/capture}
        {$csspath = $smarty.capture.stylesheet}
        {if $setting.mode.value !== 'dev'}{$csspath = {'/min/?f='|cat:$csspath|cat:'&amp;'|cat:$smarty.now}}{else}{$csspath = {$url|cat:$csspath}}{/if}
        {if $setting.concat.value}{$csspath = {$csspath|concat_url:'css'}}{/if}
        {if $browser !== 'IE'}<link rel="preload" href="{$csspath}" as="style">{/if}
        <link rel="stylesheet" href="{$csspath}">{/strip}*}
    <meta name="theme-color" content="#3C62AA" />
    {*{capture name="scriptHtml5"}{strip}
        /min/?f=
        skin/{$theme}/js/vendor/html5shiv.min.js,
        skin/{$theme}/js/vendor/respond.min.js
    {/strip}{/capture}
    <!--[if lt IE 9]><script src="{if $setting.concat.value}{$smarty.capture.scriptHtml5|concat_url:'js'}{else}{$smarty.capture.scriptHtml5}{/if}"></script><![endif]-->
    {capture name="picturefill"}/min/?f=skin/{$theme}/js/vendor/modernizr.min.js,skin/{$theme}/js/vendor/picturefill.min.js,skin/{$theme}/js/vendor/intersection-observer.min.js&amp;{$smarty.now}{/capture}
    <script src="{if $setting.concat.value}{$smarty.capture.picturefill|concat_url:'js'}{else}{$smarty.capture.picturefill}{/if}" async></script>*}
    {$basecss = [
        "/skin/{$theme}/css/properties{if $setting.mode.value !== 'dev'}.min{/if}.css",
        "/skin/{$theme}/css/{$viewport}{if $setting.mode.value !== 'dev'}.min{/if}.css",
        "/skin/{$theme}/css/content{if $setting.mode.value !== 'dev'}.min{/if}.css"
    ]}
    {if $setting['maintenance']['value'] === '1'}{$basecss[] = "/skin/{$theme}/css/maintenance{if $setting.mode.value !== 'dev'}.min{/if}.css"}{/if}
    {$css_files = []}
    {block name="styleSheet"}{/block}
    {include file="section/brick/css.tpl" css_files=array_merge($basecss,$css_files)}
    {if $setting['analytics']['value']}{include file="section/brick/analytics.tpl" aid=$setting['analytics']['value']}{/if}
    <link rel="preconnect" href="https://cdn.jsdelivr.net"/>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net"/>
</head>
<body id="{block name='body:id'}layout{/block}" class="{$bodyClass}{if $touch} touchscreen{/if} {block name='body:class'}{/block}" itemscope itemtype="http://schema.org/{block name="webType"}WebPage{/block}" itemref="meta">
{if $setting['maintenance']['value'] === '1'}{include file="section/brick/maintenance.tpl"}{/if}
{include file="section/brick/cookie-consent.tpl"}
{include file="section/header.tpl" nocache}
{block name="breadcrumb" nocache}
    {if isset($smarty.get.controller) && $smarty.get.controller !== 'home'}
        {include file="section/brick/breadcrumb.tpl" icon='home' amp=$amp scope="global"}
    {/if}
{/block}
{block name="main:before"}{/block}
{block name="main"}
    <main id="content">
        {block name="article:before"}{/block}
        {block name='article'}
        <article class="container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
            {block name='article:content'}{/block}
        </article>
        {/block}
        {block name="aside"}{/block}
        {block name="article:after"}{/block}
    </main>
{/block}
{block name="main:after"}{/block}
{include file="section/footer.tpl" adjust="clip" blocks=['socials','sitemap','news','contact','legal_notice']}
<script>
    window.lazyLoadOptions = {
        elements_selector: "[loading=lazy]",
        use_native: true
    };
</script>
<script async src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@12.0.0/dist/lazyload.min.js"></script>
{$jquery = false}
{$basejs = [
'normal' => [],
'async' => [],
'defer' => [
"/skin/{$theme}/js/vendor/bootstrap-native.min.js",
"/skin/{$theme}/js/vendor/{if $setting.mode.value === 'dev'}src/{/if}simpleLightbox{if !$setting.mode.value === 'dev'}.min{/if}.js",
"/skin/{$theme}/js/vendor/{if $setting.mode.value === 'dev'}src/{/if}tiny-slider{if !$setting.mode.value === 'dev'}.min{/if}.js",
"/skin/{$theme}/js/{if $setting.mode.value === 'dev'}src/{/if}polyfill{if !$setting.mode.value === 'dev'}.min{/if}.js",
"/skin/{$theme}/js/{if $setting.mode.value === 'dev'}src/{/if}affixhead{if !$setting.mode.value === 'dev'}.min{/if}.js",
"/skin/{$theme}/js/{if $setting.mode.value === 'dev'}src/{/if}global{if !$setting.mode.value === 'dev'}.min{/if}.js"
]
]}
{if $touch}{$basejs['defer'][] = "/skin/{$theme}/js/{if $dev}src/{/if}viewport{if !$dev}.min{/if}.js"}{/if}
{$js_files = []}
{block name="scripts"}{/block}
{include file="section/brick/scripts.tpl" js_files=array_merge($basejs,$js_files) jquery=$jquery}
{block name="foot"}{/block}
{include file="section/brick/service_worker.tpl"}
</body>
</html>