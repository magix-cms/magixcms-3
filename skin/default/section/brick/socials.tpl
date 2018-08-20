{strip}
    {* Default Meta => Home *}
    {$meta['og:site_name'] = {$companyData.name}}
    {if $shareConfig['twitter_id']}
        {$meta["twitter:card"] = 'summary'}
        {$meta['twitter:site'] = {'@'|cat:$shareConfig['twitter_id']}}
    {/if}
    {$meta["og:title"] = {$title}}
    {$meta["og:description"] = {$description}}
    {$meta["og:url"] = {''|cat:{$url}|cat:{$smarty.server.REQUEST_URI}}}
    {$meta["og:image"] = {''|cat:{$url}|cat:'/skin/'|cat:{$theme}|cat:'/img/logo/'|cat:{#logo_img#}}}
    {$meta["og:type"] = 'website'}
    {$data = null}

    {switch $smarty.get.controller}
    {* Pages *}
    {case 'pages' break}
    {if $pages.imgSrc.large}
        {$meta["og:image"] = {''|cat:{$url}|cat:{$pages.imgSrc.large}}}
    {/if}
    {* /Pages *}

    {* Catalogue *}
    {case 'catalog' break}
    {if isset($product)}
        {$meta["og:type"] = 'product'}
        {$meta["product:price:amount"] = {$product.price|round:2|number_format:2:',':' '|decimal_trim:','}}
        {$meta["product:price:currency"] = "EUR"}
        {$meta["og:availability"] = "instock"}
        {if !empty($product.img)}
            {foreach $product.img as $img}
                {if $img.default}
                    {$meta["og:image"] = {''|cat:{$url}|cat:{$img.imgSrc.large}}}
                {/if}
            {/foreach}
        {/if}
    {elseif isset($cat)}
        {if $cat.imgSrc.large}
            {$meta["og:image"] = {''|cat:{$url}|cat:{$cat.imgSrc.large}}}
        {/if}
    {/if}
    {* /Catalogue *}

    {* Actualités *}
    {case 'news' break}
    {if $news}
        {if $news.imgSrc.large}
            {$meta["og:image"] = {''|cat:{$url}|cat:{$news.imgSrc.large}}}
        {/if}
        {$meta["og:type"] = 'article'}
        {$meta["article:published_time"] = $news.date_publish}
        {$meta["article:author"] = $companyData.name}
    {/if}
    {* /Actualités *}
    {/switch}
{/strip}
{foreach $meta as $k => $v}
{if in_array($k,['twitter:site','twitter:card'])}
<meta name="{$k}" content="{$v}" />
{else}
<meta property="{$k}" content="{$v}" />
{/if}
{/foreach}