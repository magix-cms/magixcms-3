{autoload_i18n}{*{$cClass = "backend_controller_"|cat:$smarty.get.controller}*}<!DOCTYPE html>
<!--[if lt IE 7]><html lang="fr" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="fr" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="fr" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html lang="fr"><!--<![endif]-->
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
    <title>{block name='head:title'}{/block} | Magix CMS</title>
    <meta name="description" content="{#magix_installation#}">
    <meta name="robots" content="no-index">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{geturl}/install/template/img/favicon.png" />
    <!--[if IE]>
    <link rel="shortcut icon" type="image/x-icon" href="{geturl}/install/template/img/favicon.ico" />
    <![endif]-->
    {include file="brick/google-font.tpl" fonts=['Heebo'=>'300,500,700']}
    {headlink rel="stylesheet" href="/install/min/?f={$install_folder}/template/css/install.min.css" media="screen"}
    {block name="stylesheets"}{/block}
    {strip}<!--[if lt IE 9]>{script src="/install/min/?f=libjs/vendor/html5shiv.js,libjs/vendor/respond.min.js" type="javascript"}<![endif]-->{/strip}
    </head>
<body id="{block name='body:id'}layout{/block}">
{block name="header"}{/block}
{block name="main"}
    <main id="page" class="container-fluid">
        <div class="login-panel">
            {if $error}
                <div class="error">
                    {$error}
                </div>
            {/if}
            {if $debug}
                {$debug}
            {/if}
            <div id="logo">
                {capture name="sizes"}(min-width: 960px) 500px, (min-width: 480px) 50vw, 229px{/capture}
                <picture>
                    <!--[if IE 9]><video style="display: none;"><![endif]-->
                    <source type="image/webp" sizes="{$smarty.capture.sizes}" srcset="{$url}/{$install_folder}/template/img/logo/webp/logo-magix_cms@229.webp 229w, {$url}/{$install_folder}/template/img/logo/webp/logo-magix_cms@500.webp 500w">
                    <source type="image/png" sizes="{$smarty.capture.sizes}" srcset="{$url}/{$install_folder}/template/img/logo/png/logo-magix_cms@229.png 229w, {$url}/{$install_folder}/template/img/logo/png/logo-magix_cms@500.png 500w">
                    <!--[if IE 9]></video><![endif]-->
                    <img src="{$url}/{$install_folder}/template/img/logo/png/logo-magix_cms@229.png" sizes="{$smarty.capture.sizes}" srcset="{$url}/{$install_folder}/template/img/logo/png/logo-magix_cms@229.png 229w,{$url}/{$install_folder}/template/img/logo/png/logo-magix_cms@500.png 500w" alt="Magix CMS" />
                </picture>
            </div>
            <div class="flip-container">
                <div class="flipper">
                    <div class="login-box front panel">{* {$smarty.server.PHP_SELF} *}
                        <div class="mc-message-container clearfix">
                            <div class="mc-message"></div>
                        </div>
                        {block name="content"}{/block}
                    </div>
                </div>
            </div>
            <p><i class="far fa-copyright"></i> 2008{if 'Y'|date !== '2008'} - {'Y'|date}{/if} <a href="http://www.magix-cms.com/" class="targetblank">Magix CMS</a> &mdash; <small>v</small>&thinsp;{$releaseData.version}&thinsp;<sup>({$releaseData.phase})</sup> &mdash;{#all_right_reserved#}</p>
        </div>
    </main>
{/block}
{strip}{capture name="vendors"}
    /install/min/?f=libjs/vendor/jquery-3.0.0.min.js,
    install/template/js/vendor/bootstrap.min.js,
    libjs/vendor/jquery.form.4.2.1.min.js,
    libjs/vendor/jquery.validate.1.17.0.min.js,
    libjs/vendor/jimagine/plugins/jquery.jmRequest.js,
    {$install_folder}/template/js/form.min.js,
    {$install_folder}/template/js/install.min.js
{/capture}{/strip}
<script src="{if $setting.concat.value}{$smarty.capture.vendors|concat_url:'js'}{else}{$smarty.capture.vendors}{/if}" defer></script>
{block name="foot"}{/block}
</body>
</html>