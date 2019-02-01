{extends file="layout.tpl"}
{block name='body:id'}about{/block}
{block name="title"}{$pages.seo.title}{/block}
{block name="description"}{$pages.seo.description}{/block}
{block name="webType"}{if isset($parent)}WebPage{else}AboutPage{/if}{/block}
{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <header>
                {widget_about_data
                    conf = [
                        'context' => 'all'
                        ]
                    assign="aboutPages"
                }
                {include file="section/brick/toc.tpl" pages=$aboutPages root=['url' => "{$url}/{$lang}/about/","title" => $root.name]}
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