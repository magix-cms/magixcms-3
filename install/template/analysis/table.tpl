<h2 class="h3 text-center">PHP &dash; <small class="{if $results.php.version}success{else}warning{/if}" data-toggle="tooltip" title="{if $results.php.version}{#php_compatible#}{else}{#php_not_compatible#}{/if}">{$results.php.v}</small></h2>
<table class="table table-bordered table-condensed table-hover">
    <thead>
    <tr>
        <th>Extension</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <tr class="{if $results.php.encoding}success{else}warning{/if}">
        <td>mbstring</td>
        <td>{if $results.php.encoding}{#is_installed#}{else}{#is_not_installed#}{/if}</td>
    </tr>
    <tr class="{if $results.php.encoding}success{else}warning{/if}">
        <td>iconv</td>
        <td>{if $results.php.iconv}{#is_installed#}{else}{#is_not_installed#}{/if}</td>
    </tr>
    <tr class="{if $results.php.encoding}success{else}warning{/if}">
        <td>ob_start</td>
        <td>{if $results.php.ob}{#is_installed#}{else}{#is_not_installed#}{/if}</td>
    </tr>
    <tr class="{if $results.php.encoding}success{else}warning{/if}">
        <td>simplexml</td>
        <td>{if $results.php.xml}{#is_installed#}{else}{#is_not_installed#}{/if}</td>
    </tr>
    <tr class="{if $results.php.encoding}success{else}warning{/if}">
        <td>dom_xml</td>
        <td>{if $results.php.dom}{#is_installed#}{else}{#is_not_installed#}{/if}</td>
    </tr>
    <tr class="{if $results.php.encoding}success{else}warning{/if}">
        <td>spl</td>
        <td>{if $results.php.spl}{#is_installed#}{else}{#is_not_installed#}{/if}</td>
    </tr>
    </tbody>
</table>
<h2 class="h3 text-center">{#permissions#}</h2>
<table class="table table-bordered table-condensed table-hover">
    <thead>
    <tr>
        <th>{#folder#}</th>
        <th>{#permission#}</th></tr>
    </thead>
    <tbody>
    <tr class="{if $results.php.encoding}success{else}warning{/if}">
        <td>/var</td>
        <td>{if $results.access.writable_var}{#is_writable#}{else}{#is_not_writable#}{/if}</td>
    </tr>
    <tr class="{if $results.php.encoding}success{else}warning{/if}">
        <td>/app/config</td>
        <td>{if $results.access.writable_config}{#is_writable#}{else}{#is_not_writable#}{/if}</td>
    </tr>
    </tbody>
</table>