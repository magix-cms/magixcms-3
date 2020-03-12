{extends file="layout.tpl"}
{block name='body:id'}about{/block}
{block name="title"}{$pages.seo.title}{/block}
{block name="description"}{$pages.seo.description}{/block}
{block name="webType"}{if isset($parent)}WebPage{else}AboutPage{/if}{/block}
{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <header>
                {widget_cms_data conf = ['context' => 'all'] assign="pagesTree"}
                {widget_about_data conf = ['context' => 'all'] assign="aboutPages"}
                <ul class="menu-cms">
                    {if $aboutPages}
                        <li class="dropdown-header{if $smarty.get.controller === 'about'} active{/if}">
                            <a href="{$url}/{$lang}/about/" title="{#about_footer#}">{#about_footer#}</a>
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#aboutPages">
                                <span class="show-more"><i class="material-icons ico ico-keyboard_arrow_right">{*keyboard_arrow_right*}</i></span>
                                <span class="show-less"><i class="material-icons ico ico-keyboard_arrow_up">{*keyboard_arrow_up*}</i></span>
                            </button>
                        </li>
                        <li class="submenu">
                            <ul id="aboutPages" class="collapse">
                                {include file="section/loop/toc.tpl" pages=$aboutPages s=0 controller='about'}
                            </ul>
                        </li>
                    {else}
                        <li{if $smarty.get.controller === 'about' && !$smarty.get.id} class="active"{/if}>
                            <a href="{$url}/{$lang}/about/" title="{#about_footer#}">{#about_footer#}</a>
                        </li>
                    {/if}
                    {include file="section/loop/toc.tpl" pages=$pagesTree}
                </ul>
                <h1 itemprop="name">{$pages.name}</h1>
            </header>
            {if $pages.date.register}<time datetime="{$pages.date.register}" itemprop="datePublished"></time>{/if}
            {if $pages.date.update}<time datetime="{$pages.date.update}" itemprop="dateModified"></time>{/if}
            <div class="content">
                <div itemprop="text">
                    {$pages.content}
                </div>
            </div>
        {/block}
    </article>
{/block}