<header id="top" class="header" role="banner">
    <nav class="header-inner">
        <div role="button" on="tap:sidebar1.toggle" tabindex="0" class="hamburger"><i class="material-icons">menu</i></div>
        <div class="site-name">
            <a href="{geturl}/{getlang}/amp/" title="{#logo_link_title#|ucfirst}">
                <amp-img src="/skin/{template}/img/logo/{#logo_amp#}" alt="Logo de Magix CMS" height="50" width="229"></amp-img>
            </a>
        </div>
        {widget_lang_data assign="dataLangNav"}{* Language Nav *}
        {if $dataLangNav != null && count($dataLangNav) > 1}
            <div class="select-lang">
                {include file="amp/section/loop/lang.tpl" data=$dataLangNav type="nav" display='menu'}
            </div>
        {/if}
    </nav>
</header>
{include file="amp/section/menu/sidebar.tpl"}