{extends file="layout.tpl"}
{block name='body:id'}about{/block}
{block name="title"}{$pages.seo.title}{/block}
{block name="description"}{$pages.seo.description}{/block}
{block name="webType"}{if isset($parent)}WebPage{else}AboutPage{/if}{/block}
{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
        <div class="row">
            <div class="col-4 col-xs-6 col-sm-8 col-md-7 col-lg-9 push-md-3 content">
                <h1 itemprop="name">{$pages.name}</h1>
                {if $pages.date.register}<time datetime="{$pages.date.register}" itemprop="datePublished"></time>{/if}
                {if $pages.date.update}<time datetime="{$pages.date.update}" itemprop="dateModified"></time>{/if}
                <div itemprop="text">
                    {$pages.content}
                </div>
            </div>
            <div class="col-4 col-xs-6 col-sm-8 col-md-3 pull-md-7 pull-lg-9 menu-cms">
                {widget_cms_data conf = ['context' => 'all'] assign="pagesTree"}
                {widget_about_data conf = ['context' => 'all'] assign="aboutPages"}
                <ul>
                    {if $aboutPages}
                        <li class="dropdown-header active">
                            <a href="{$url}/{$lang}/about/" title="{#about_footer#}">{#about_footer#}</a>
                            <button class="btn btn-link open" type="button" data-toggle="collapse" data-target="#aboutPages">
                                <span class="show-more"><i class="material-icons ico ico-add">{*keyboard_arrow_right*}</i></span>
                                <span class="show-less"><i class="material-icons ico ico-remove">{*keyboard_arrow_up*}</i></span>
                            </button>
                        </li>
                        <li class="submenu">
                            <ul id="aboutPages" class="collapse in">
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
            </div>
        </div>
        {/block}
    </article>
{/block}