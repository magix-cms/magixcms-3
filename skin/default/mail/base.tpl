{autoload_i18n}{widget_about_data}
{if $logo && $logo.img.active eq 1}
    {$logo_url = "{$url}{$logo.img.medium.src}"}
    {$logo_alt = "{if !empty($logo.img.alt)}{$logo.img.alt|escape}{else}Logo {$companyData.name|escape}{/if}"}
    {$logo_h = "{$logo.img.medium.h}"}
    {$logo_w = "{$logo.img.medium.w}"}
{else}
    {$logo_url = "{$url}/skin/{$theme}/img/logo/{#logo_img_mail#}"}
    {$logo_alt = "Logo {$companyData.name|escape}"}
    {$logo_h = "50"}
    {$logo_w = "225"}
{/if}
{$main_color = "#6F1125"}
{$footer_color = $getDataCSSIColor[3].color_cssi}
{$footer_color = "#ccc"}
{$light_grey = "#666"}
{function nl2pandbr level=0}
    {$text|replace:'\n\n':'</p><p>'|replace:'\n':'<br />'}
{/function}
{block name="layout"}{/block}