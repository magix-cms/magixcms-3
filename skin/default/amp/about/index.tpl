{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/pages.min.css"}{/block}
{block name='body:id'}about{/block}
{block name="title"}{$pages.seo.title}{/block}
{block name="description"}{$pages.seo.description}{/block}
{block name="webType"}{if isset($parent)}WebPage{else}AboutPage{/if}{/block}
{block name="amp-script"}{amp_components content=$pages.content}{/block}
{block name='article'}
    <article class="container cms" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            {*<meta itemprop="name" content="{$pages.name}" />
            <h1>
                <span class="row">
                    <span class="col-12 col-xs-3">{#about#|ucfirst}</span><span class="col-12 col-xs-9">{$pages.name}</span>
                </span>
            </h1>*}
            <header>
                {widget_about_data
                conf = [
                'context' => 'all'
                ]
                assign="aboutPages"
                }
                {include file="section/brick/toc.tpl" pages=$aboutPages root=['url' => "{$url}/{$lang}/about/","title" => $root.name] amp=true}
                <h1 itemprop="name">{$pages.name}</h1>
            </header>
{*            <h1 itemprop="name">{$pages.name}</h1>*}
            {if $pages.date.register}<time datetime="{$pages.date.register}" itemprop="datePublished"></time>{/if}
            {if $pages.date.update}<time datetime="{$pages.date.update}" itemprop="dateModified"></time>{/if}
            <div class="content">
                <div itemprop="text">
                    {amp_content content=$pages.content}
                </div>
            </div>
        {/block}
    </article>
{/block}