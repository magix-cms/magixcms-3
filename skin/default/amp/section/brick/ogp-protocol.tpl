{strip}
{* Default Meta => Home *}
{capture name="ogType"}website{/capture}
{if $smarty.get.controller === 'catalog' || $smarty.get.controller === 'category' || $smarty.get.controller === 'product' || $smarty.get.controller === 'news'}
{if $smarty.get.idproduct || $smarty.get.uri_get_news}
    {capture name="ogType"}article{/capture}
{/if}
{/if}
prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# {$smarty.capture.ogType}: http://ogp.me/ns/{$smarty.capture.ogType}#"{/strip}