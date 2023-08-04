{strip}
    {* facebook, news, cms, contact, newsletter*}
    {if !isset($blocks)}
        {assign var="blocks" value=['news','contact']}
    {/if}
{/strip}
<footer id="footer">
    {if is_array($blocks) && !empty($blocks)}
        <div id="footer-blocks">
            <div class="container">
                <div class="blocks row">
                    {foreach $blocks as $block}
                        {include file="section/footer/block/$block.tpl"}
                    {/foreach}
                </div>
            </div>
            {if $consentAsked}<div id="rgpd-param" class="fade in hide">
                <button class="btn btn-default" type="button" id="paramCookies" data-toggle="modal" data-target="#cookiesModal">
                    <span class="ico ico-cookie-bite"></span><span class="sr-only">{#param_cookies#}</span>
                </button>
            </div>{/if}
        </div>
    {/if}
    <div id="colophon" class="container-fluid">
        <div class="container">
            {include file="section/footer/about.tpl"}
        </div>
    </div>
</footer>
{include file="section/footer/footbar.tpl"}