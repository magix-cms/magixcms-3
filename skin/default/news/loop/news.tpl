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
            <time itemprop="datePublished" datetime="{$item.date.publish}"></time>
            <div class="tile">
                <figure class="time-figure">
                    {if isset($item.img.name)}{$src = $item.img.medium.src}{else}{$src = $item.img.default}{/if}
                    {strip}<picture>
                        {if isset($item.img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
                        <source type="image/webp" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src_webp']} {$item.img.medium['w']}w">
                        <source type="{$item.img.medium.ext}" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src']} {$item.img.medium['w']}w">
                        <!--[if IE 9]></video><![endif]-->{/if}
                        <img {if $lazy}data-{/if}src="{$src}" itemprop="image" alt="{$item.img.alt}" title="{$item.img.title}"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium['w']}" height="{$item.img.medium['h']}"{/if} class="img-responsive{if $lazy} lazyload{/if}" />
                        </picture>{/strip}
                </figure>
                <div itemprop="description" class="desc text-justify">
                    {strip}<small class="date">{$item.date.publish|date_format:"%e<br>%b"}</small>
                    <h2 class="h4" itemprop="name">{$item.name}</h2>
                    <p>{if $truncate}{$item.resume|truncate:$truncate:'&hellip;'}{else}{$item.resume}{/if}</p>
                        {if !empty($item.tags)}
                            <p class="tag-list">
                                {$nbt = $item.tags|count}
                                <span class="fa fa-tag{if $nbt > 1}s{/if}"></span>
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