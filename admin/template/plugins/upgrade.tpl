{extends file="layout.tpl"}
{block name='head:title'}Mise a jour du plugin {$smarty.get.controller}{/block}
{block name='body:id'}upgrade{/block}
{block name='article:header'}
    <h1 class="h2">Mise a jour du plugin {$smarty.get.controller}</h1>
{/block}
{block name='article:content'}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-8">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">Résultat de la mise a jour</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                {$message}
            </div>
        </section>
    </div>
{/block}