{extends file="layout.tpl"}
{block name="styleSheet" append}
    <script src="{if isset($smarty.server.HTTPS) eq 'on'}https{else}http{/if}://maps.google.com/maps/api/js?sensor=false&amp;language={$lang}{if $config.api_key != '' AND $config.api_key != NULL}&amp;key={$config.api_key}{/if}" type="text/javascript"></script>
    {headlink rel="stylesheet" href="/min/?f=plugins/gmap/css/perfect-scrollbar.min.css" concat=$concat media="screen"}
{/block}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>{#seo_title_gmap#}]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>{#seo_desc_gmap#}]}{/block}
{block name='body:id'}gmap{/block}
{block name="main"}
    {*<div class="container">*}
        {include file="gmap/map.tpl"}
    {*</div>*}
{/block}
{block name="foot" append}
    {script src="/min/?g=form" concat=$concat type="javascript"}
    {capture name="formjs"}{strip}
        /min/?f=skin/{$theme}/js/form.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.formjs concat=$concat type="javascript" load='async'}
    {script src="/min/?f=plugins/gmap/js/perfect-scrollbar.min.js,plugins/gmap/js/gmap3-7.2.min.js,plugins/gmap/js/gmap.min.js" concat=$concat type="javascript"}
    <script type="text/javascript">
        $(function(){
            if (typeof gmap == "undefined"){
                console.log("gmap is not defined");
            }else{
                gmap.run({$config_gmap},{literal}{scrollwheel: false}{/literal});
            }
        });
    </script>
{/block}