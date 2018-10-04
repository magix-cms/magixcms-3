{extends file="layout.tpl"}
{block name='body:id'}pages{/block}
{block name="title"}{if $pages.seo.title}{$pages.seo.title}{else}{$pages.title}{/if}{/block}
{block name="description"}{if $pages.seo.description}{$pages.seo.description}{elseif !empty($pages.resume)}{$pages.resume}{elseif !empty($pages.content)}{$pages.content|strip_tags|truncate:100:'...'}{/if}{/block}
{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <header>
                {include file="section/brick/toc.tpl" pages=$pagesTree root=['url' => "{$url}/{$lang}/","title" => {#home#}]}
                <h1 itemprop="name">{$pages.title}</h1>
            </header>
            <div class="text" itemprop="text">
                {*{if !empty($pages.imgSrc.medium)}
                    <figure>
                        <a href="{$pages.imgSrc.large}" class="img-zoom" title="{$pages.title}">
                            <img class="img-responsive" src="{$pages.imgSrc.medium}" alt="{$pages.title}" title="{$pages.title}" />
                        </a>
                    </figure>
                {/if}*}
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