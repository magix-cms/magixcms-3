{if is_array($debugData)}
    <h3>Debug</h3>
<pre>
    {$debugData|print_r}
</pre>
{/if}