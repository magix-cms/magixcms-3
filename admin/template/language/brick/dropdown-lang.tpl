<div class="row">
    <div class="col-xs-12 col-md-4">
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
</div>