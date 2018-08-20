<header id="top" class="header" role="banner">
    <nav class="header-inner">
        <div role="button" on="tap:sidebar1.toggle" tabindex="0" class="hamburger"><i class="material-icons">menu</i></div>
        <div class="site-name">
            <a href="{$url}/{$lang}/amp/" title="{#logo_link_title#|ucfirst}">
                <amp-img src="/skin/{$theme}/img/logo/{#logo_amp#}" alt="Logo de Magix CMS" height="44" width="200"></amp-img>
            </a>
        </div>
        {if $dataLang != null && count($dataLang) > 1}
            <div class="select-lang">
                {include file="amp/section/brick/lang.tpl" display='menu'}
            </div>
        {/if}
    </nav>
</header>
{include file="amp/section/menu/sidebar.tpl"}