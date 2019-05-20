<!DOCTYPE html>
<!--[if lt IE 7]><html lang="{$lang}" class="lt-ie9 lt-ie8 lt-ie7" dir="ltr"><![endif]-->
<!--[if IE 7]><html lang="{$lang}" class="lt-ie9 lt-ie8" dir="ltr"><![endif]-->
<!--[if IE 8]><html lang="{$lang}" class="lt-ie9" dir="ltr"><![endif]-->
<!--[if gt IE 8]><!--><html lang="{$lang}" dir="ltr"><!--<![endif]-->
<head id="meta" {block name="ogp"}{include file="section/brick/ogp-protocol.tpl"}{/block}>
    <meta charset="utf-8">
    <title itemprop="headline">{capture name="title"}{block name="title"}{/block}{/capture}{$smarty.capture.title}</title>
    <meta name="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    <meta itemprop="description" content="{$smarty.capture.description}">
    <meta name="robots" content="{$setting['robots']['value']}">
    {strip}{include file="section/loop/lang.tpl" amp=false amp_active=true iso={$lang}}{/strip}
    {strip}{include file="section/brick/canonical.tpl" amp=false amp_active=true}{/strip}
    {*{if {module type="news"} eq true}<link rel="alternate" type="application/rss+xml" href="{$url}/news_{$lang}_rss.xml" title="RSS">{/if}*}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <meta name="theme-color" content="#7083db" />
    {include file="section/brick/google-font.tpl" fonts=['Material Icons'=>'0','Heebo'=>'300,500,700']}
    {* Please try to use icomoon to create a small iconfont and reduce load work *}
    {strip}{capture name="stylesheet"}/skin/{$theme}/css/{$viewport}{if $setting.mode.value !== 'dev'}.min{/if}.css{/capture}
    {$csspath = $smarty.capture.stylesheet}
    {if $setting.mode.value !== 'dev'}{$csspath = {'/min/?f='|cat:$csspath}}{else}{$csspath = {$url|cat:$csspath}}{/if}
    {if $setting.concat.value}{$csspath = {$csspath|concat_url:'css'}}{/if}
    {if $browser !== 'IE'}<link rel="preload" href="{$csspath}" as="style">{/if}
    <link rel="stylesheet" href="{$csspath}">{/strip}
    {capture name="scriptHtml5"}{strip}
        /min/?f=
        skin/{$theme}/js/vendor/html5shiv.min.js,
        skin/{$theme}/js/vendor/respond.min.js
    {/strip}{/capture}
    <!--[if lt IE 9]><script src="{if $setting.concat.value}{$smarty.capture.scriptHtml5|concat_url:'js'}{else}{$smarty.capture.scriptHtml5}{/if}"></script><![endif]-->
    {capture name="picturefill"}/min/?f=skin/{$theme}/js/vendor/modernizr.min.js,skin/{$theme}/js/vendor/picturefill.min.js,skin/{$theme}/js/vendor/intersection-observer.min.js{/capture}
    <script src="{if $setting.concat.value}{$smarty.capture.picturefill|concat_url:'js'}{else}{$smarty.capture.picturefill}{/if}" async></script>
    {block name="styleSheet"}{/block}
    {if $setting['analytics']['value']}{include file="section/brick/analytics.tpl" aid=$setting['analytics']['value']}{/if}
</head>
<body id="{block name='body:id'}layout{/block}" class="{$bodyClass}{if $touch} touchscreen{/if} {block name='body:class'}{/block}" itemscope itemtype="http://schema.org/{block name="webType"}WebPage{/block}" itemref="meta">
{include file="section/brick/cookie-consent.tpl"}
{include file="section/header.tpl"}
{block name="breadcrumb"}
    {if isset($smarty.get.controller) && $smarty.get.controller !== 'home'}
        {include file="section/brick/breadcrumb.tpl" icon='home' amp=false}
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
{include file="section/footer.tpl" adjust="clip" blocks=['sitemap','about','news','contact']}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>
    window.lazyLoadOptions = {
        elements_selector: "[loading=lazy]",
        use_native: true
    };
</script>
<script async src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@12.0.0/dist/lazyload.min.js"></script>
{include file="section/brick/scripts.tpl"}
{block name="foot"}{/block}
{include file="section/brick/service_worker.tpl"}
</body>
</html>