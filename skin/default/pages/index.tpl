{extends file="layout.tpl"}
{block name='body:id'}pages{/block}
{block name="title"}{$pages.seo.title}{/block}
{block name="description"}{$pages.seo.description}{/block}
{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <header>
                {include file="section/brick/toc.tpl" pages=$pagesTree root=['url' => "{$url}/{$lang}/","title" => {#home#}]}
                <h1 itemprop="name">{$pages.name}</h1>
            </header>
            <div class="text clearfix" itemprop="text">
                {if isset($pages.img.name)}
                    <a href="{$pages.img.large.src}" class="img-zoom img-float pull-right" title="{$pages.img.title}" data-caption="{$pages.img.caption}">
                        <figure>
                            {strip}<picture>
                                <!--[if IE 9]><video style="display: none;"><![endif]-->
                                <source type="image/webp" sizes="{$pages.img.medium['w']}px" srcset="{$pages.img.medium['src_webp']} {$pages.img.medium['w']}w">
                                <source type="{$pages.img.medium.ext}" sizes="{$pages.img.medium['w']}px" srcset="{$pages.img.medium['src']} {$pages.img.medium['w']}w">
                                <!--[if IE 9]></video><![endif]-->
                                <img data-src="{$pages.img.medium['src']}" width="{$pages.img.medium['w']}" height="{$pages.img.medium['h']}" alt="{$pages.img.alt}" title="{$pages.img.title}" class="img-responsive lazyload" />
                                </picture>{/strip}
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