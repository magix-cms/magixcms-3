{extends file="layout.tpl"}
{block name='head:title'}{$getTitleHeader}{/block}
{block name='body:id'}error{/block}
{block name='article:header'}
    <h1 class="h2">Gestionnaire d'erreur</h1>
{/block}
{block name='article:content'}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-8">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{$getTitleHeader}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <p>
                    <span class="fa fa-warning"></span> {$getTxtHeader}
                </p>
            </div>
        </section>
    </div>
{/block}