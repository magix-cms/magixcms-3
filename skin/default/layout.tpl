{strip}{widget_share_data assign="shareData"}{/strip}<!DOCTYPE html>
<!--[if lt IE 7]><html lang="{$lang}" class="lt-ie9 lt-ie8 lt-ie7" dir="ltr"><![endif]-->
<!--[if IE 7]><html lang="{$lang}" class="lt-ie9 lt-ie8" dir="ltr"><![endif]-->
<!--[if IE 8]><html lang="{$lang}" class="lt-ie9" dir="ltr"><![endif]-->
<!--[if gt IE 8]><!--><html lang="{$lang}" dir="ltr"><!--<![endif]-->
<head id="meta" {block name="ogp"}{include file="section/brick/ogp-protocol.tpl"}{/block}>
    <meta charset="utf-8">
    <title itemprop="headline">{block name="title"}{/block}</title>
    <meta name="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    <meta itemprop="description" content="{$smarty.capture.description}">
    <meta name="robots" content="{$setting['robots']['value']}">
    {strip}{include file="section/loop/lang.tpl" amp=true iso={$lang}}{/strip}
    {*{if {module type="news"} eq true}<link rel="alternate" type="application/rss+xml" href="{$url}/news_{$lang}_rss.xml" title="RSS">{/if}*}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {include file="section/brick/socials.tpl" title=$smarty.capture.title description=$smarty.capture.description}
    {if $domainData != null && $domainData.tracking_domain != ''}{$domainData.tracking_domain}{else}{if $googleTools_webmaster != ''}<meta name="google-site-verification" content="{$googleTools_webmaster}">{/if}{/if}
    <link rel="icon" type="image/png" href="{$url}/skin/{$theme}/img/favicon.png" />
    <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="{$url}/skin/{$theme}/img/favicon.ico" /><![endif]-->
    {include file="section/brick/google-font.tpl" fonts=['Roboto'=>'300,400,600,400italic','Raleway'=>'300,500']}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    {capture name="stylesheet"}/min/?f=skin/{$theme}/css/{$viewport}.min.css{/capture}
    {strip}<link rel="preload" href="{$smarty.capture.stylesheet}" as="style">{/strip}
    {strip}<link rel="stylesheet" href="{$smarty.capture.stylesheet}">{/strip}
    {capture name="scriptHtml5"}{strip}
        /min/?f=
        skin/{$theme}/js/vendor/html5shiv.min.js,
        skin/{$theme}/js/vendor/respond.min.js
    {/strip}{/capture}
    {strip}<!--[if lt IE 9]>{script src=$smarty.capture.scriptHtml5 concat=$setting.concat.value type="javascript"}<![endif]-->{/strip}
    {capture name="picturefill"}/min/?f=skin/{$theme}/js/vendor/picturefill.min.js,skin/{$theme}/js/vendor/intersection-obeserver.min.js{/capture}
    {strip}{script src=$smarty.capture.picturefill concat=$setting.concat.value type="javascript" load="async"}{/strip}
    {block name="styleSheet"}{/block}
    {if $setting['analytics']['value']}<script type="text/javascript">
        //<![CDATA[
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', "{$setting['analytics']['value']}"]);
        _gaq.push(['_trackPageview']);
        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
        //]]>
    </script>{/if}
</head>
<body id="{block name='body:id'}layout{/block}" class="{$viewport}{if $touch} touchscreen{/if}" itemscope itemtype="http://schema.org/{block name="webType"}WebPage{/block}" itemref="meta">
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
    {include file="section/footer.tpl" adjust="clip" blocks=['sitemap','about','contact']}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    {strip}{capture name="vendors"}
        /min/?f=skin/{$theme}/js/vendor/bootstrap-{$viewport}.min.js,
        {if $touch}skin/{$theme}/js/vendor/jquery.detect_swipe.min.js,{/if}
        skin/{$theme}/js/vendor/featherlight.min.js,
        skin/{$theme}/js/vendor/featherlight.gallery.min.js,
        skin/{$theme}/js/global-{$viewport}.min.js,
        skin/{$theme}/js/lazyload.min.js
    {/capture}
        {script src=$smarty.capture.vendors concat=$setting.concat.value type="javascript" load='async'}{/strip}
    {block name="foot"}{/block}
</body>
</html>