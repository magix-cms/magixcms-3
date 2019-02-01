{*<div class="row">
    <div class="col-ph-12 col-md-4">
        <label for="id_lang">{#language#|ucfirst} *</label>
        <div class="dropdown dropdown-lang">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                {foreach $langs as $id => $iso}
                    {if $iso@first}{$default = $id}{break}{/if}
                {/foreach}
                <span class="lang">{$langs[$default]}</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                {foreach $langs as $id => $iso}
                    <li role="presentation"{if $iso@first} class="active"{/if}>
                        <a href="#lang-{$id}" aria-controls="lang-{$id}" role="tab" data-toggle="tab">{$iso}</a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
</div>*}
{if !isset($label)}{$label = true}{/if}
{if !isset($onclass)}{$onclass = false}{/if}
<div class="form-group">
    <label{if !$label} class="sr-only"{/if} for="id_lang">{#language#|ucfirst} *</label>
    <div class="dropdown dropdown-lang">
        <button class="btn btn-default dropdown-toggle{if $custom_class} {$custom_class}{/if}" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            {foreach $langs as $id => $iso}
                {if $iso@first}{$default = $id}{break}{/if}
            {/foreach}
            <span class="lang">{$langs[$default]}</span>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdown{if $onclass}.{else}M{/if}enu1">
            {foreach $langs as $id => $iso}
                <li role="presentation"{if $iso@first} class="active"{/if}>
                    <a data-target="{if $onclass}.{else}#{/if}lang-{$id}" aria-controls="lang-{$id}" role="tab" data-toggle="tab">{$iso}</a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>
