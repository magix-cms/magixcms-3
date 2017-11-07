{strip}
    {* facebook, news, cms, contact, newsletter*}
    {if !isset($blocks)}
        {assign var="blocks" value=['news','contact']}
    {/if}
    {widget_share_data assign="shareData"}
{/strip}
<footer id="footer"{if $touch} class="mobile-footer"{/if}>
    {if is_array($blocks) && !empty($blocks)}
        <section id="footer-blocks">
            <div class="container-fluid">
                {if $viewport !== 'mobile'}<div class="row">{/if}
                {foreach $blocks as $block}
                    {include file="section/footer/block/$block.tpl"}
                {/foreach}
                {if $viewport !== 'mobile'}</div>{/if}
            </div>
        </section>
    {/if}
    <div id="colophon">
        <div class="container-fluid">
            {include file="section/footer/about.tpl"}
        </div>
    </div>
</footer>
{if $touch}{include file="section/footer/footbar.tpl"}{else}{include file="section/nav/btt.tpl"}{/if}