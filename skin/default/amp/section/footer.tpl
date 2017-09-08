{strip}
    {* facebook, news, cms, contact, newsletter*}
    {if !isset($blocks)}
        {assign var="blocks" value=['news','contact']}
    {/if}
    {widget_share_data assign="shareData"}
{/strip}
<footer id="footer">
    {include file="section/footer/sharebar.tpl"}
    {include file="instafeed/instafeed.tpl"}
    {if is_array($blocks) && !empty($blocks)}
        <div id="footer-blocks">
            <div class="container">
                <div class="row">
                    {foreach $blocks as $block}
                        {include file="section/footer/block/$block.tpl"}
                    {/foreach}
                </div>
            </div>
        </div>
    {/if}
    <div id="colophon">
        {include file="section/footer/about.tpl"}
    </div>
</footer>