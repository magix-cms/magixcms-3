{strip}
    {* facebook, news, cms, contact, newsletter*}
    {if !isset($blocks)}
        {assign var="blocks" value=['news','contact']}
    {/if}
{/strip}
<footer id="footer">
    {if is_array($blocks) && !empty($blocks)}
        <section id="footer-blocks">
            <div class="container">
                <div class="blocks row">
                    {foreach $blocks as $block}
                        {include file="section/footer/block/$block.tpl"}
                    {/foreach}
                </div>
            </div>
        </section>
    {/if}
    <div id="colophon">
        <div class="container-fluid">
            {include file="section/footer/about.tpl"}
        </div>
    </div>
</footer>
{include file="section/footer/footbar.tpl"}