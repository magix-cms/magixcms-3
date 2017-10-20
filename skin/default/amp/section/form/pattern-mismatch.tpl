{strip}{if $msg && $input}
    <span visible-when-invalid="patternMismatch"
          validation-for="{$input}"
          class="feedback">{$msg}</span>
{/if}{/strip}