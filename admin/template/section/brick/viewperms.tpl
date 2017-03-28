{$eClass = $smarty.server.SCRIPT_NAME|substr:1:-4}
<div class="panels row">
    <section class="panel col-xs-12">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header">
            <h2 class="panel-heading h5">{#error_root_access#|ucfirst} : {#$eClass#}</h2>
        </header>
        <div class="panel-body">
            <div class="alert alert-warning"><span class="fa fa-warning"></span> Vous n'avez pas les permissions suffisante pour accéder à "{#$eClass#}"</div>
        </div>
    </section>
</div>