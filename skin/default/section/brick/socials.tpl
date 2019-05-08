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
    {if is_array($social) && !empty($social)}
        {$meta["og:image"] = {''|cat:{$url}|cat:$social.img.src}}
        {$meta["og:image:width"] = $social.img.w}
        {$meta["og:image:height"] = $social.img.h}
    {else}
        {$meta["og:image"] = {''|cat:{$url}|cat:'/skin/'|cat:{$theme}|cat:'/img/logo/logo.png'}}
        {$meta["og:image:width"] = '250'}
        {$meta["og:image:height"] = '250'}
    {/if}
    {$meta["og:type"] = 'website'}
    {$data = null}

    {switch $smarty.get.controller}
    {case 'pages' break}
        {* Pages *}
    {if $pages.img.medium}
        {$meta["og:image"] = {''|cat:{$url}|cat:{$pages.img.medium.src}}}
        {$meta["og:image:width"] = {$pages.img.medium.w}}
        {$meta["og:image:height"] = {$pages.img.medium.h}}
    {/if}
        {* /Pages *}

    {case 'catalog' break}
        {* Catalogue *}
    {if isset($product)}
        {$meta["og:type"] = 'product'}
        {$meta["product:price:amount"] = {$product.price|round:2|number_format:2:',':' '|decimal_trim:','}}
        {$meta["product:price:currency"] = "EUR"}
        {$meta["og:availability"] = "instock"}
        {if !empty($product.imgs)}
            {foreach $product.imgs as $img}
                {if $img.default}
                    {$meta["og:image"] = {''|cat:{$url}|cat:{$img.img.medium.src}}}
                    {$meta["og:image:width"] = {$img.img.medium.w}}
                    {$meta["og:image:height"] = {$img.img.medium.h}}
                {/if}
            {/foreach}
        {/if}
    {elseif isset($cat)}
        {if $cat.img.medium}
            {$meta["og:image"] = {''|cat:{$url}|cat:{$cat.img.medium.src}}}
            {$meta["og:image:width"] = {$cat.img.medium.w}}
            {$meta["og:image:height"] = {$cat.img.medium.h}}
        {/if}
    {/if}
        {* /Catalogue *}

    {case 'news' break}
        {* Actualités *}
    {if $news}
        {if $news.img.medium}
            {$meta["og:image"] = {''|cat:{$url}|cat:{$news.img.medium.src}}}
            {$meta["og:image:width"] = {$news.img.medium.w}}
            {$meta["og:image:height"] = {$news.img.medium.h}}
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