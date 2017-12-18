{extends file="layout.tpl"}
{block name='body:id'}pages{/block}
{block name="title"}{if $pages.seoTitle}{$pages.seoTitle}{else}{$pages.title}{/if}{/block}
{block name="description"}{if $pages.seoTitle}{$pages.seoDescr}{elseif !empty($pages.content)}{$pages.content|truncate:100:'...'}{/if}{/block}
{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <header>
                {widget_cms_data
                conf = [
                'context' => 'all'
                ]
                assign="pagesTree"
                }
                {include file="section/brick/toc.tpl" pages=$pagesTree root=['url' => "{geturl}/{getlang}/","title" => {#home#}]}
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
        {/block}
    </article>
{/block}