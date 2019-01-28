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
        <div{if $classCol} class="{$classCol}{/if}">
            <figure class="effect-steve thumbnail">
                {if count($item.img) > 1}
                    <img class="img-responsive lazyload" src="{$item.img.default}" data-src="{$item.img.medium.src}" alt="{$item.title}" title="{$item.title}"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}/>
                {else}
                    <img class="img-responsive" src="{$item.img.default}" alt="{$item.title}" title="{$item.title}" />
                {/if}
                <figcaption>
                    <h2>{$item.title|ucfirst}</h2>
                    <div class="desc">
                        {if $item.resume}
                            <p>{$item.resume|truncate:$truncate:'...'}</p>
                        {elseif $item.content}
                            <p>{$item.content|strip_tags|truncate:$truncate:'...'}</p>
                        {/if}
                    </div>
                    <a class="all-hover" href="{$item.url}" title="{$item.title|ucfirst}" itemprop="url" itemprop="relatedLink">{$item.title|ucfirst}</a>
                </figcaption>
            </figure>
        </div>
    {/foreach}
{/if}