{strip}
    {if isset($data.id)}
        {$data = [$data]}
    {/if}
    {if !isset($truncate)}
        {$truncate = 150}
    {/if}
{/strip}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div{if $classCol} class="{$classCol}"{/if} itemprop="itemListElement" itemscope itemtype="http://schema.org/CreativeWork">
            <link itemprop="additionalType" href="http://schema.org/Article" />
            <meta itemprop="position" content="{$item@index + 1}">
            <div class="time-published">
                <time itemprop="datePublished" datetime="{$item.date.publish}">{*$item.date.publish|date_format:"%e / %m / %Y"*}</time>
                {*<p>{$item.date.publish|date_format:"%A"}</p>*}
                {*<p class="tday">{$item.date.publish|date_format:"%e"}</p>*}
                {*<p>{$item.date.publish|date_format:"%m/%y"}</p>*}
                <p>{$item.date.publish|date_format:"%e&thinsp;/&thinsp;%m"}</p>
            </div>
            <div class="figure row">
                <div class="time-figure col-12 col-xs-6 col-sm-12 col-md-6">
                    <div>
                        {if count($item.img) > 1}
                            <img {if $item@first}src="{$item.img.medium.src}"{else}src="{$item.img.default}" data-src="{$item.img.medium.src}" class="lazy"{/if} alt="{$item.title}" title="{$item.title}" itemprop="image"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}>
                        {else}
                            <img src="{$item.img.default}" alt="{$item.title}" title="{$item.title}">
                        {/if}
                    </div>
                </div>
                <div itemprop="description" class="desc col-12 col-xs-6 col-sm-12 col-md-6">
                    <h2 itemprop="name" class="h3">{$item.title|ucfirst}</h2>
                    {if $item.resume}
                        <p>{$item.resume|truncate:$truncate:'...'}</p>
                    {elseif $item.content}
                        <p>{$item.content|strip_tags|truncate:$truncate:'...'}</p>
                    {/if}
                    {strip}
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
                <a class="all-hover" href="{$item.url}" title="{$item.title|ucfirst}" itemprop="url">{$item.title|ucfirst}</a>
            </div>
        </div>
    {/foreach}
{/if}