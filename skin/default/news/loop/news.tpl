{strip}
    {if isset($data.id)}
        {$data = [$data]}
    {/if}
    {if !isset($truncate)}
        {$truncate = 150}
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
                        {if isset($item.img.name)}{$src = $item.img.medium.src}{else}{$src = $item.img.default}{/if}
                        {strip}<picture>
                            {if isset($item.img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
                            <source type="image/webp" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src_webp']} {$item.img.medium['w']}w">
                            <source type="{$item.img.medium.ext}" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src']} {$item.img.medium['w']}w">
                            <!--[if IE 9]></video><![endif]-->{/if}
                            <img {if $lazy}data-{/if}src="{$src}" itemprop="image" alt="{$item.img.alt}" title="{$item.img.title}"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium['w']}" height="{$item.img.medium['h']}"{/if} class="img-responsive{if $lazy} lazyload{/if}" />
                            </picture>{/strip}
                    </div>
                </div>
                <div itemprop="description" class="desc col-12 col-xs-6 col-sm-12 col-md-6">
                    <h2 itemprop="name" class="h3">{$item.name}</h2>
                    <p>{if $truncate}
                        {$item.resume|truncate:$truncate:'&hellip;'}
                    {else}
                        {$item.resume}
                    {/if}</p>
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
                <a class="all-hover" href="{$item.url}" title="{$item.seo.description}" itemprop="url">{$item.seo.title}</a>
            </div>
        </div>
    {/foreach}
{/if}