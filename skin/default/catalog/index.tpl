{extends file="layout.tpl"}
{block name='body:id'}catalog{/block}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>{$root.name}]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>{$root.content|strip_tags|truncate:100:'...'}]}{/block}
{block name="webType"}CollectionPage{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Series">
        {block name='article:content'}
            <h1 itemprop="name">{$root.name}</h1>
            <div class="text" itemprop="text">
                {$root.content}
            </div>
            {if $categories}
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}