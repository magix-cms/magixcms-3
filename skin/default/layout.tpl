{autoload_i18n}
<!DOCTYPE html>
<!--[if lt IE 7]><html lang="{getlang}" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="{getlang}" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="{getlang}" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html lang="{getlang}"><!--<![endif]-->
<head id="meta" {block name="ogp"}{include file="section/brick/ogp-protocol.tpl"}{/block}>{* Document meta *}
    <meta charset="utf-8">
    <title itemprop="headline">{capture name="title"}{block name="title"}{/block}{/capture}{$smarty.capture.title}</title>
    <meta name="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    <meta itemprop="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    <meta name="robots" content="{google_tools tools='robots'}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {block name="socials"}{include file="section/brick/socials.tpl" title=$smarty.capture.title description=$smarty.capture.description}{/block}
    {if $googleTools_webmaster != ''}<meta name="google-site-verification" content="{$googleTools_webmaster}">{/if}
    <link rel="icon" type="image/png" href="{geturl}/skin/{template}/img/favicon.png" />
    <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="{geturl}/skin/{template}/img/favicon.ico" /><![endif]-->
    {capture name="stylesheet"}{strip}
        /min/?f=skin/{template}/css/mobile.min.css
    {/strip}{/capture}
{strip}{*{headlink rel="stylesheet" href=$smarty.capture.stylesheet concat=$concat media="screen"}*}
    <link rel="stylesheet" href="{$smarty.capture.stylesheet}">
{/strip}{if {module type="news"} eq true}
    <link rel="alternate" type="application/rss+xml" href="{geturl}/news_{getlang}_rss.xml" title="RSS">
{/if}
{capture name="scriptHtml5"}{strip}
    /min/?f=
    skin/{template}/js/vendor/html5shiv.min.js,
    skin/{template}/js/vendor/respond.min.js
{/strip}{/capture}
    {strip}<!--[if lt IE 9]>{script src=$smarty.capture.scriptHtml5 concat=$concat type="javascript"}<![endif]-->{/strip}
    {capture name="picturefill"}{strip}
        /min/?f=skin/{template}/js/vendor/picturefill.min.js
    {/strip}{/capture}
    {strip}{script src=$smarty.capture.picturefill concat=$concat type="javascript" load="async"}{/strip}
    {strip}{* Language link hreflang *}{widget_lang_data assign="dataLangHead"}{include file="section/loop/lang.tpl" data=$dataLangHead type="head"}{google_tools tools='analytics'}
{/strip}</head>
<body id="{block name='body:id'}layout{/block}" itemscope itemtype="http://schema.org/{block name="webType"}WebPage{/block}" itemref="meta">
    {include file="section/brick/cookie-consent.tpl"}{* Pour menu="cat-dropdown" ou menu="mega-dropdown" il faut au minimum menuclass='mega-dropdown' *}
    {include file="section/header.tpl"}
    {block name="breadcrumb"}{/block}
    {block name="main:before"}{/block}
    {block name="main"}
    <main id="content">
        {block name="article:before"}{/block}
        {block name='article'}
        <article itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
            {block name='article:content'}{/block}
        </article>
        {/block}
        {block name="aside"}{/block}
        {block name="article:after"}{/block}
    </main>
    {/block}
    {block name="main:after"}{/block}
    {if isset($mobileBrowser) && $mobileBrowser}
        {include file="section/footer-mobile.tpl" adjust="clip" blocks=['contact','sitemap','about']}
    {else}
        {include file="section/footer.tpl" adjust="clip" blocks=['contact','sitemap','facebook','about']}
    {/if}
    {if !(isset($mobileBrowser) && $mobileBrowser)}{include file="section/nav/btt.tpl"}{/if}
    {strip}{capture name="vendors"}
        /min/?f=skin/{template}/js/vendor/jquery.min.js,
        {if isset($mobileBrowser) && $mobileBrowser}
            skin/{template}/js/vendor/bootstrap-mobile.min.js,
            skin/{template}/js/vendor/jquery.detect_swipe.min.js,
        {else}
            skin/{template}/js/vendor/bootstrap.min.js,
        {/if}
        skin/{template}/js/vendor/featherlight.min.js,
        skin/{template}/js/vendor/featherlight.gallery.min.js
    {/capture}
    {script src=$smarty.capture.vendors concat=$concat type="javascript"}{/strip}
    {strip}{capture name="scriptSkin"}
        /min/?f=
        {if isset($mobileBrowser) && $mobileBrowser}
            skin/{template}/js/global-mobile.min.js
        {else}
            skin/{template}/js/global.min.js
        {/if}
    {/capture}
    {script src=$smarty.capture.scriptSkin concat=$concat type="javascript" load='async'}{/strip}
    {block name="foot"}{/block}
    {block name="fonts"}{include file="section/brick/google-font.tpl" fonts=['Roboto'=>'300,400,600,400italic','Raleway'=>'300,500']}{/block}
    {block name="styleSheet"}{/block}
</body>
</html>