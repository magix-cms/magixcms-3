{extends file="layout.tpl"}
{block name='article:header'}
    <h1>Analysis</h1>
{/block}
{block name='article:content'}
    <table class="table table-bordered table-condensed table-hover">
    <thead>
        <tr><th>Extension</th>
        <th>Résolution</th></tr>
        </thead>
    <tbody><tr><td>phpversion</td><td>{if $getBuildItems.php eq '1'}Votre version de PHP est compatible{/if}</td></tr>
        <tr><td>mbstring</td><td>{if $getBuildItems.encoding eq '1'}est installé{else}n'est pas installé{/if}</td></tr>
        <tr><td>iconv</td><td>{if $getBuildItems.iconv eq '1'}est installé{else}n'est pas installé{/if}</td></tr>
        <tr><td>ob_start</td><td>{if $getBuildItems.ob eq '1'}est installé{else}n'est pas installé{/if}</td></tr>
        <tr><td>simplexml</td><td>{if $getBuildItems.xml eq '1'}est installé{else}n'est pas installé{/if}</td></tr>
        <tr><td>dom_xml</td><td>{if $getBuildItems.dom eq '1'}est installé{else}n'est pas installé{/if}</td></tr>
        <tr><td>spl</td><td>{if $getBuildItems.spl eq '1'}est installé{else}n'est pas installé{/if}</td></tr>
    </tbody>
    </table>
    <table class="table table-bordered table-condensed table-hover">
    <thead>
    <tr>
        <th>Dossier</th>
        <th>Permission</th></tr>
    </thead>
    <tbody>
    <tr><td>/var</td><td>{if $getBuildItems.writable_var eq '1'}est accessible en écriture{else}n'est pas accessible en écriture{/if}</td></tr>
    <tr><td>/app/config</td><td>{if $getBuildItems.writable_config eq '1'}est accessible en écriture{else}n'est pas accessible en écriture{/if}</td></tr>
    </tbody>
    </table>
    <a href="/install/config.php" class="btn btn-box btn-invert btn-main-theme">Configuration de votre installation</a>
{/block}