<footer id="footer">
    {if is_array($blocks) && !empty($blocks)}
        <div id="footer-blocks">
            <div class="container-fluid">
                <div class="blocks">
                    <div class="row">
                        {foreach $blocks as $block}
                            {include file="amp/section/footer/block/$block.tpl"}
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    {/if}
    <div id="colophon">
        <div class="container">
            {include file="amp/section/footer/about.tpl"}
        </div>
    </div>
</footer>
{include file="amp/section/footer/footbar.tpl"}