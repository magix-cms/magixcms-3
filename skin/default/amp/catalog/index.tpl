{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/catalog.min.css"}{/block}
{block name='body:id'}home{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Series">
        {block name='article:content'}
            <h1 itemprop="name">{$root.name}</h1>
            <div itemprop="text">
                {amp_content content=$root.content}
            </div>
            {if $categories}
            <div class="category-list section-block">
                <div class="row row-center">
                    {include file="amp/catalog/loop/category.tpl" data=$categories classCol='vignette col-ph-12 col-xs-8 col-sm-6 col-md-4'}
                </div>
            </div>
            {/if}
            {*<pre>{$root|print_r}</pre>
            {$categories|var_dump}
            {widget_catalog_data
            conf =[
            'context' =>  'category'
            ]
            assign='categoryData'
            }
            <pre>{$categoryData|print_r}</pre>*}
        {/block}
    </article>
{/block}