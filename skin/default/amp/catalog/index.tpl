{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/catalog.min.css"}{/block}
{block name='body:id'}catalog{/block}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>{$root.name}]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>{$root.content|truncate:100:'...'}]}{/block}
{block name="webType"}CollectionPage{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Series">
        {block name='article:content'}
            <h1 itemprop="name">{$root.name}</h1>
            <div itemprop="text">
                {amp_content content=$root.content}
            </div>
            {if $categories}
            <div class="vignette-list">
                <div class="section-block">
                    <div class="row row-center">
                        {include file="amp/catalog/loop/category.tpl" data=$categories classCol='vignette col-ph-12 col-xs-8 col-sm-6 col-md-4'}
                    </div>
                </div>
            </div>
            {/if}
        {/block}
    </article>
{/block}