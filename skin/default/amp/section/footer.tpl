{strip}
    {widget_share_data assign="shareData"}
{/strip}
<footer id="footer"{if $adjust == 'fluid'} class="section-block container-fluid"{/if}>
    {include file="section/footer/sharebar.tpl"}
    {if is_array($blocks) && !empty($blocks)}
        <section id="footer-blocks">
            <div class="container">
                <div class="row">
                    {foreach $blocks as $block}
                        {include file="section/footer/block/$block.tpl"}
                    {/foreach}
                </div>
            </div>
        </section>
    {/if}
    <section id="colophon">
        <div class="container">
            {include file="section/footer/about.tpl"}
        </div>
    </section>
</footer>
{include file="section/footer/footbar.tpl"}