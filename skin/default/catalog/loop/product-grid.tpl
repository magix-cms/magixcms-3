{strip}
    {if isset($data.id)}
        {$data = [$data]}
    {/if}
    {if !isset($truncate)}
        {$truncate = 220}
    {/if}
    {if !isset($lazy)}
        {$lazy = true}
    {/if}
{/strip}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div{if $classCol} class="{$classCol}{/if}" itemprop="itemListElement" itemscope itemtype="http://schema.org/Product">
            <div class="figure">
                {include file="img/img.tpl" img=$item.img lazy=$lazy}
                <div itemprop="description" class="desc">
                    <h3 itemprop="name">{$item.name}</h3>
                    {*<pre>{$item|var_dump}</pre>*}
                    <p class="text-justify">{if $truncate}
                            {$item.resume|truncate:$truncate:'&hellip;'}
                        {else}
                            {$item.resume}
                        {/if}</p>
                </div>
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    {strip}
                    {$price = $item.price * (1 + ($setting.vat_rate/100))}
                    {$promo_price = $item.promo_price * (1 + ($setting.vat_rate/100))}
                    <meta itemprop="availability" content="https://schema.org/{$item.properties.availability}" />
                    <div itemprop="price" class="product-price" content="{if $promo_price != '0.00'}{$promo_price|round:2|number_format:2:'.':' '|decimal_trim:','}{else}{$price|round:2|number_format:2:'.':' '|decimal_trim:','}{/if}">
                        {if $item.price != '0.00'}
                            {if $promo_price != '0.00'}
                                <span class="price">{$promo_price|round:2|number_format:2:',':' '|decimal_trim:','}<span class="currency">€</span></span>
                                <span class="crossed-price"><span class="strike">{$price|round:2|number_format:2:',':' '|decimal_trim:','}</span><span class="currency">€</span></span>
                            {else}
                                <span class="price">{$price|round:2|number_format:2:',':' '|decimal_trim:','}<span class="currency">€</span></span>
                            {/if}
                        {/if}
                    </div>
                    <span itemprop="priceCurrency" content="EUR"></span>
                    {/strip}
                </div>
                <a class="all-hover" href="{$item.url}" title="{$item.seo.description}" itemprop="url">{$item.seo.title}</a>
            </div>
        </div>
    {/foreach}
{/if}