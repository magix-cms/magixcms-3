{autoload_i18n}{$cClass = "frontend_controller_"|cat:{$smarty.server.SCRIPT_NAME|substr:1:-4}}<!DOCTYPE html>
<!--[if lt IE 7]><html lang="fr" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="fr" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="fr" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html lang="fr"><!--<![endif]-->
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
    <title>{block name='head:title'}layout{/block} | Magix CMS | Admin</title>
    <meta name="description" content="">
    <meta name="robots" content="no-index">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/skin/img/favicon.png" />
    <!--[if IE]>
    <link rel="shortcut icon" type="image/x-icon" href="/skin/img/favicon.ico" />
    <![endif]-->
    {headlink rel="stylesheet" href="/{baseadmin}/min/?g=publiccss" media="screen"}
    {block name="stylesheets"}{/block}
    {capture name="scriptHtml5"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/html5shiv.js,
        libjs/vendor/respond.min.js
    {/strip}{/capture}
    {strip}<!--[if lt IE 9]>{script src=$smarty.capture.scriptHtml5 type="javascript"}<![endif]-->{/strip}
    </head>
<body id="{block name='body:id'}layout{/block}">
{block name="header"}{include file="section/header.tpl"}{/block}
{block name="main"}
    <main id="page" class="container-fluid">
        <div class="row">
            {block name='article'}
                <div id="content" class="col-xs-12 col-sm-9 col-md-10 pull-right">
                    <header>
                        <button id="toggle-menu" type="button" class="open-menu navbar-toggle" data-target="#mobile-menu1">
                            <span class="sr-only">{#toggleNavigation#|ucfirst}</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <button id="toggle-menu2" type="button" class="open-menu navbar-toggle pull-right" data-target="#mobile-menu2">
                            <span class="sr-only">{#toggleNavigation#|ucfirst}</span>
                            <span class="fa fa-cog"></span>
                        </button>
                        {block name='article:header'}{/block}
                    </header>
                    {block name='article:content'}{/block}
                </div>
            {/block}

            {block name="aside"}
                <nav id="aside" class="hidden-xs">
                    {block name='aside:content'}
                        {include file="section/menu/section.tpl"}
                    {/block}
                </nav>
            {/block}
            {block name="article:after"}{/block}
        </div>
    </main>
{/block}
{block name="foot"}
   {* {script src="/min/?g=publicjs,globalize,jimagine" type="javascript"}
    {script src="/min/?f=/skin/js/global.min.js,/skin/js/vendor/jquery.formatter.min.js" type="javascript"}
    {script src="/skin/js/form.min.js" type="javascript"}
    <script type="text/javascript">
        $.nicenotify.notifier = {
            box:"",
            elemclass : '.mc-message'
        };
        $(function(){
            if (typeof globalForm == "undefined")
            {
                console.log("globalForm is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}";
                globalForm.run(controller);
            }
        });
    </script>*}
{/block}
</body>
</html>