{strip}
{autoload_i18n}
{widget_about_data}
{widget_lang_data assign="dataLang"}
{widget_share_data assign="shareData"}
{/strip}<!DOCTYPE html>
<!--[if lt IE 7]><html lang="{getlang}" class="lt-ie9 lt-ie8 lt-ie7" dir="ltr"><![endif]-->
<!--[if IE 7]><html lang="{getlang}" class="lt-ie9 lt-ie8" dir="ltr"><![endif]-->
<!--[if IE 8]><html lang="{getlang}" class="lt-ie9" dir="ltr"><![endif]-->
<!--[if gt IE 8]><!--><html lang="{getlang}" dir="ltr"><!--<![endif]-->
<head id="meta" {block name="ogp"}{include file="section/brick/ogp-protocol.tpl"}{/block}>
    <meta charset="utf-8">
    <title itemprop="headline">{capture name="title"}{block name="title"}{/block}{/capture}{$smarty.capture.title}</title>
    <meta name="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    <meta itemprop="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    <meta name="robots" content="{google_tools tools='robots'}">
    {strip}{include file="section/loop/lang.tpl" amp=true iso={getlang}}{/strip}
    {if {module type="news"} eq true}<link rel="alternate" type="application/rss+xml" href="{geturl}/news_{getlang}_rss.xml" title="RSS">{/if}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {include file="section/brick/socials.tpl" title=$smarty.capture.title description=$smarty.capture.description}
    {if $googleTools_webmaster != ''}<meta name="google-site-verification" content="{$googleTools_webmaster}">{/if}
    <link rel="icon" type="image/png" href="{geturl}/skin/{template}/img/favicon.png" />
    <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="{geturl}/skin/{template}/img/favicon.ico" /><![endif]-->
    {include file="section/brick/google-font.tpl" fonts=['Roboto'=>'300,400,600,400italic','Raleway'=>'300,500']}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    {capture name="stylesheet"}/min/?f=skin/{template}/css/{$viewport}.min.css{/capture}
    {strip}<link rel="stylesheet" href="{$smarty.capture.stylesheet}">{/strip}
    {capture name="scriptHtml5"}{strip}
        /min/?f=
        skin/{template}/js/vendor/html5shiv.min.js,
        skin/{template}/js/vendor/respond.min.js
    {/strip}{/capture}
    {strip}<!--[if lt IE 9]>{script src=$smarty.capture.scriptHtml5 concat=$concat type="javascript"}<![endif]-->{/strip}
    {capture name="picturefill"}/min/?f=skin/{template}/js/vendor/picturefill.min.js{/capture}
    {strip}{script src=$smarty.capture.picturefill concat=$concat type="javascript" load="async"}{/strip}
    {block name="styleSheet"}{/block}
</head>
<body id="{block name='body:id'}layout{/block}" itemscope itemtype="http://schema.org/{block name="webType"}WebPage{/block}" itemref="meta">
    {include file="section/brick/cookie-consent.tpl"}
    {include file="section/header.tpl"}
    {block name="breadcrumb"}{/block}
    {block name="main:before"}
        {if isset($smarty.get.controller) && $smarty.get.controller !== 'home'}
            {include file="section/brick/breadcrumb.tpl" icon='home' amp=false}
        {/if}
    {/block}
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
    {include file="section/footer.tpl" adjust="clip" blocks=['contact']}
    {strip}{capture name="vendors"}
        /min/?f=skin/{template}/js/vendor/jquery.min.js,
        skin/{template}/js/vendor/bootstrap-{$viewport}.min.js,
        {if $touch}skin/{template}/js/vendor/jquery.detect_swipe.min.js,{/if}
        skin/{template}/js/vendor/featherlight.min.js,
        skin/{template}/js/vendor/featherlight.gallery.min.js
    {/capture}
        {script src=$smarty.capture.vendors concat=$concat type="javascript"}{/strip}
    {strip}{capture name="scriptSkin"}
        /min/?f=skin/{template}/js/global-{$viewport}.min.js
    {/capture}
        {script src=$smarty.capture.scriptSkin concat=$concat type="javascript" load='async'}{/strip}
    {block name="foot"}{/block}
</body>
</html>