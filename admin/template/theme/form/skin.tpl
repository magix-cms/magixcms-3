{if is_array($skin) && !empty($skin)}
<div class="theme-manager row">
{foreach $skin as $item}
    <div class="col-ph-12 col-sm-6 col-md-3">
        <figure>
            {if $item.screenshot.small}
                <a class="img-zoom" href="{$item.screenshot.large}">
                    <img class="img-responsive" src="{$item.screenshot.small}" alt="{$item.name|ucfirst}"/>
                </a>
            {else}
                <a class="img-zoom" href="/{baseadmin}/template/img/skin/screenshot_l.jpg">
                    <img class="img-responsive" src="/{baseadmin}/template/img/skin/screenshot_s.jpg" alt="{$item.name|ucfirst}"/>
                </a>
            {/if}
            <figcaption>
                <h3>{$item.name|ucfirst}</h3>
                <a class="btn btn-block {if $item.current eq 'true'}btn-fr-theme{else}btn-default {/if} skin-select" data-skin="{$item.name}" href="#">{if $item.current eq 'true'}Sélectionné{else}Choisir{/if}</a>
            </figcaption>
        </figure>
    </div>
{/foreach}
</div>
{/if}