{autoload_i18n}{*{$cClass = "backend_controller_"|cat:$smarty.get.controller}*}<!DOCTYPE html>
<!--[if lt IE 7]><html lang="fr" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="fr" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="fr" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html lang="fr"><!--<![endif]-->
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
    <title>{block name='head:title'}layout{/block} | Magix CMS | Install</title>
    <meta name="description" content="">
    <meta name="robots" content="no-index">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{geturl}/install/template/img/favicon.png" />
    <!--[if IE]>
    <link rel="shortcut icon" type="image/x-icon" href="{geturl}/install/template/img/favicon.ico" />
    <![endif]-->
    {headlink rel="stylesheet" href="/install/min/?f=install/template/css/src/style.min.css" media="screen"}
    {block name="stylesheets"}{/block}
    {capture name="scriptHtml5"}{strip}
        /install/min/?f=
        libjs/vendor/html5shiv.js,
        libjs/vendor/respond.min.js
    {/strip}{/capture}
    {strip}<!--[if lt IE 9]>{script src=$smarty.capture.scriptHtml5 type="javascript"}<![endif]-->{/strip}
    </head>
<body id="{block name='body:id'}layout{/block}">
{block name="header"}{*{include file="section/header.tpl"}*}{/block}
{block name="main"}
    <main id="{block name='main:id'}page{/block}">
        {block name='article'}
            <div id="content" class="container">
                <header>
                    <button id="toggle-menu" type="button" class="open-menu navbar-toggle" data-target="#mobile-menu1">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <button id="toggle-menu2" type="button" class="open-menu navbar-toggle pull-right" data-target="#mobile-menu2">
                        <span class="sr-only"></span>
                        <span class="fa fa-cog"></span>
                    </button>
                    {block name='article:header'}{/block}
                </header>
                {block name='article:content'}
                {/block}
            </div>
        {/block}
        {block name="aside"}
        {/block}
        {block name="article:after"}{/block}
    </main>
{/block}
{block name="footer"}{include file="section/footer.tpl"}{/block}
{block name="foot"}
    {strip}{capture name="vendors"}
        /install/min/?f=libjs/vendor/jquery-3.0.0.min.js,
        install/template/js/vendor/bootstrap.min.js,
        libjs/vendor/jquery.form.4.2.1.min.js,
        libjs/vendor/jquery.validate.1.17.0.min.js,
        libjs/vendor/jimagine/plugins/jquery.jmRequest.js,
        install/template/js/form.min.js
    {/capture}
        {script src=$smarty.capture.vendors concat=$concat type="javascript"}{/strip}
    <script type="text/javascript">
        $.jmRequest.notifier = {
            cssClass : '.mc-message'
        };
    </script>
{/block}
</body>
</html>