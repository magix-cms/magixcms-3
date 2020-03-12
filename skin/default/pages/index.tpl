{extends file="layout.tpl"}
{block name='body:id'}pages{/block}
{block name="title"}{$pages.seo.title}{/block}
{block name="description"}{$pages.seo.description}{/block}
{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <header>
                {widget_about_data conf=['context' => 'all'] assign="aboutPages"}
                <ul class="menu-cms">
                    {if $aboutPages}
                        <li class="dropdown-header{if $controller === 'about'} active{/if}">
                            <a href="{$url}/{$lang}/about/" title="{#about_footer#}">{#about_footer#}</a>
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#aboutPages">
                                <span class="show-more"><i class="material-icons ico ico-keyboard_arrow_right">{*keyboard_arrow_right*}</i></span>
                                <span class="show-less"><i class="material-icons ico ico-keyboard_arrow_up">{*keyboard_arrow_up*}</i></span>
                            </button>
                        </li>
                        <li class="submenu">
                            <ul id="aboutPages" class="collapse">
                                {include file="section/loop/toc.tpl" pages=$aboutPages s=0 controller="about"}
                            </ul>
                        </li>
                    {else}
                        <li{if $controller === 'about' && !$smarty.get.id} class="active"{/if}>
                            <a href="{$url}/{$lang}/about/" title="{#about_footer#}">{#about_footer#}</a>
                        </li>
                    {/if}
                    {include file="section/loop/toc.tpl" pages=$pagesTree}
                </ul>
                <h1 itemprop="name">{$pages.name}</h1>
            </header>
            <div class="text clearfix" itemprop="text">
                {if isset($pages.img.name)}
                    <a href="{$pages.img.large.src}" class="img-zoom img-float pull-right" title="{$pages.img.title}" data-caption="{$pages.img.caption}">
                        <figure>
                            {*{strip}<picture>
                                <!--[if IE 9]><video style="display: none;"><![endif]-->
                                <source type="image/webp" sizes="{$pages.img.medium['w']}px" srcset="{$pages.img.medium['src_webp']} {$pages.img.medium['w']}w">
                                <source type="{$pages.img.medium.ext}" sizes="{$pages.img.medium['w']}px" srcset="{$pages.img.medium['src']} {$pages.img.medium['w']}w">
                                <!--[if IE 9]></video><![endif]-->
                                <img data-src="{$pages.img.medium['src']}" width="{$pages.img.medium['w']}" height="{$pages.img.medium['h']}" alt="{$pages.img.alt}" title="{$pages.img.title}" class="img-responsive lazyload" />
                                </picture>{/strip}*}
                            {include file="img/img.tpl" img=$pages.img lazy=true}
                            {if $pages.img.caption}
                                <figcaption>{$pages.img.caption}</figcaption>
                            {/if}
                        </figure>
                    </a>
                {/if}
                {$pages.content}
            </div>
            {if $childs}
                {*<h3>{#subcategories#|ucfirst}</h3>*}
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="pages/loop/pages.tpl" data=$childs classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4 col-xl-3'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}