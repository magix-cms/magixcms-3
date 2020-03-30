{strip}
    {if isset($data.id)}
        {$data = [$data]}
    {/if}
    {if !isset($lazy)}
        {$lazy = true}
    {/if}
{/strip}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div{if $classCol} class="{$classCol}"{/if} itemprop="itemListElement" itemscope itemtype="http://schema.org/CreativeWork">
            <link itemprop="additionalType" href="http://schema.org/Article" />
            <meta itemprop="position" content="{$item@index + 1}">
            <time itemprop="datePublished" datetime="{$item.date.publish.date}"></time>
            <div class="tile">
                <figure class="time-figure">
                    {include file="img/img.tpl" img=$item.img lazy=$lazy}
                </figure>
                <div itemprop="description" class="desc">
                    {strip}<small class="date">{$item.date.publish.timestamp|date_format:"%e %b"}</small>
                    <h2 class="h4" itemprop="name">{$item.name}</h2>
                    <p class="text-justify">{if $truncate}{$item.resume|truncate:$truncate:'&hellip;'}{else}{$item.resume}{/if}</p>
                        {if !empty($item.tags)}
                            <p class="tag-list">
                                {$nbt = $item.tags|count}
                                <span class="fa ico ico-tag{if $nbt > 1}s{/if}"></span>
                                {foreach $item.tags as $tag}
                                    <span itemprop="about"><a href="{$tag.url}" title="{#see_more_news_about#} {$tag.name|ucfirst}">{$tag.name}</a></span>
                                    {if !$tag@last}, {/if}
                                {/foreach}
                            </p>
                        {/if}
                    {/strip}
                </div>
                <a class="all-hover" href="{$item.url}" title="{$item.seo.description}" itemprop="url">{$item.seo.title}</a>
            </div>
        </div>
    {/foreach}
{/if}