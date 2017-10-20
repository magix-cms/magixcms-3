{strip}{if $msg && $input}
    <span visible-when-invalid="valueMissing"
          validation-for="{$input}"
          class="feedback">{$msg}</span>
{/if}{/strip}