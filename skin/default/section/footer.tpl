{strip}
    {* facebook, news, cms, contact, newsletter*}
    {if !isset($blocks)}
        {assign var="blocks" value=['news','contact']}
    {/if}
    {*{widget_share_data assign="shareData"}*}
{/strip}
<footer id="footer"{if $touch} class="mobile-footer"{/if}>
    {if !$touch}{include file="section/footer/sharebar.tpl"}{/if}
    {if is_array($blocks) && !empty($blocks)}
        <section id="footer-blocks">
            <div class="container-fluid">
                <div class="blocks">
                    <div class="row">
                        <div class="container">
                            <div class="row">
                            {foreach $blocks as $block}
                                {include file="section/footer/block/$block.tpl"}
                            {/foreach}
                            </div>
                        </div>
                    </div>
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
{if $touch}{include file="section/footer/footbar.tpl"}{else}{include file="section/nav/btt.tpl"}{/if}