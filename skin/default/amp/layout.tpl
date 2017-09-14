{autoload_i18n}{widget_about_data}<!doctype html>
<html amp lang="{getlang}">
<head {block name="ogp"}{include file="amp/section/brick/ogp-protocol.tpl"}{/block}>
    <meta charset="utf-8">
    <title itemprop="headline">{capture name="title"}{block name="title"}{/block}{/capture}{$smarty.capture.title}</title>
    <meta name="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    <meta itemprop="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    {strip}
        {* Language link hreflang *}
        {widget_lang_data assign="dataLangHead"}
        {include file="amp/section/loop/lang.tpl" data=$dataLangHead type="cannonical"}
    {/strip}
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    {include file="amp/section/brick/google-font.tpl" fonts=['Roboto'=>'300,400,600,400italic','Raleway'=>'300,500']}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style amp-boilerplate>{literal}body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}{/literal}</style></noscript>
    <style amp-custom>
        {block name="stylesheet"}{/block}
    </style>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
</head>
<body id="{block name='body:id'}layout{/block}" itemscope itemtype="http://schema.org/{block name="webType"}WebPage{/block}" itemref="meta">
{*{include file="amp/section/brick/cookie-consent.tpl"}*}
{include file="amp/section/header.tpl"}
{block name="breadcrumb"}{/block}
{block name="main:before"}{/block}
{block name="main"}
    <main>
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
{include file="amp/section/footer.tpl" adjust="clip" blocks=['contact']}
</body>
</html>