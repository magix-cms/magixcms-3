{extends file="layout.tpl"}
{block name='body:id'}catalog{/block}
{block name="title"}{$root.seo.title}{/block}
{block name="description"}{$root.seo.description}{/block}
{block name="webType"}CollectionPage{/block}
{block name="styleSheet"}
    {$css_files = [
    "/skin/{$theme}/css/catalog{if $setting.mode.value !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/lightbox{if $setting.mode.value !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/slider{if $setting.mode.value !== 'dev'}.min{/if}.css"
    ]}
{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage">
        {block name='article:content'}
            <h1 itemprop="name">{$root.name}</h1>
            <div class="text" itemprop="text">
                {$root.content}
            </div>
            {if $categories}
            <p class="h2">{#categories#}</p>
            <div class="vignette-list">
                <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-12 col-xs-6 col-md-4'}
                </div>
            </div>
            {/if}
        {/block}
    </article>
{/block}