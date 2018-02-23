{extends file="layout.tpl"}
{block name='article:header'}
    <h1>Employee</h1>
{/block}
{block name='article:content'}
    <div class="mc-message-container clearfix">
        <div class="mc-message"></div>
    </div>
    <div class="row">
        <section id="form" class="col-ph-12 col-md-8 col-lg-7" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
            <form id="config-form" class="validate_form" method="post" action="{$smarty.server.REQUEST_URI}">

            </form>
        </section>
    </div>
{/block}