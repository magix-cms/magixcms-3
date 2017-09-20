<footer id="footer"{if $adjust == 'fluid'} class="section-block container-fluid"{/if}>
    {*{include file="amp/section/footer/sharebar.tpl"}*}
    {if is_array($blocks) && !empty($blocks)}
        <section id="footer-blocks">
            <div class="container">
                <div class="row">
                    {foreach $blocks as $block}
                        {include file="amp/section/footer/block/$block.tpl"}
                    {/foreach}
                </div>
            </div>
        </section>
    {/if}
    <section id="colophon">
        <div class="container">
            {include file="amp/section/footer/about.tpl"}
        </div>
    </section>
</footer>
{include file="amp/section/footer/footbar.tpl"}