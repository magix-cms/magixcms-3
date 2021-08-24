{extends file="layout.tpl"}
{block name='body:id'}pages{/block}
{block name="title"}{$pages.seo.title}{/block}
{block name="description"}{$pages.seo.description}{/block}
{block name="styleSheet"}
    {$css_files = [
    "/skin/{$theme}/css/cms{if $setting.mode.value !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/lightbox{if $setting.mode.value !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/slider{if $setting.mode.value !== 'dev'}.min{/if}.css"
    ]}
{/block}

{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <div class="row">
                <div class="col-4 col-xs-6 col-sm-8 col-md-7 col-lg-9 push-md-3 content">
                    <h1 itemprop="name">{$pages.name}</h1>
                    {if $pages.date.register}<time datetime="{$pages.date.register}" itemprop="datePublished"></time>{/if}
                    {if $pages.date.update}<time datetime="{$pages.date.update}" itemprop="dateModified"></time>{/if}
                    <div itemprop="text clearfix">
                        {*if isset($pages.img.name)}
                            <a href="{$pages.img.large.src}" class="img-zoom img-float float-right" title="{$pages.img.title}" data-caption="{$pages.img.caption}">
                                <figure>
                                    {include file="img/img.tpl" img=$pages.img lazy=true}
                                    {if $pages.img.caption}
                                        <figcaption>{$pages.img.caption}</figcaption>
                                    {/if}
                                </figure>
                            </a>
                        {/if*}
{*                        <div class="col-4 col-md-5 col-xl-4">*}
                            {include file="img/loop/gallery.tpl" imgs=$pages.imgs}
{*                        </div>*}
                        {$pages.content}
                    </div>
                </div>
                <div class="col-4 col-xs-6 col-sm-8 col-md-3 pull-md-7 pull-lg-9 menu-cms">
                    {widget_about_data conf=['context' => 'all'] assign="aboutPages"}
                    <ul>
                        {if $aboutPages}
                            <li class="dropdown-header{if $controller === 'about'} active{/if}">
                                <a href="{$url}/{$lang}/about/" title="{#about_footer#}">{#about_footer#}</a>
                                <button class="btn btn-link{if $controller === 'about'} open{/if}" type="button" data-toggle="collapse" data-target="#aboutPages">
                                    <span class="show-more"><i class="material-icons ico ico-add"></i></span>
                                    <span class="show-less"><i class="material-icons ico ico-remove"></i></span>
                                </button>
                            </li>
                            <li class="submenu">
                                <ul id="aboutPages" class="collapse{if $controller === 'about'} in{/if}">
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
                </div>
            </div>
            {*{if $childs}
                *}{*<h3>{#subcategories#|ucfirst}</h3>*}{*
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="pages/loop/pages.tpl" data=$childs classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4 col-xl-3'}
                        </div>
                    </div>
                </div>
            {/if}*}
        {/block}
    </article>
{/block}