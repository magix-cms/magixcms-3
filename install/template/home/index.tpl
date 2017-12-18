{extends file="layout.tpl"}
{block name='body:id'}home{/block}
{block name='article:header'}
    <h1>Installation de Magix CMS</h1>
{/block}
{block name='article'}
    <div id="content" class="container">
        {block name='article:content'}
            <div id="logo" class="text-center">
                <img src="/install/template/img/logo/logo-magix_cms@500.png" alt="Magix CMS" width="500" height="121">
            </div>
            <div class="text-center">
                <p>
                    Vous êtes prêt démarrer l'installation,une série de tests sera effectuée pour la prise en charge de Magix CMS
                </p>
                <div>
                    <a href="{geturl}/install/analysis.php" class="btn btn-box btn-invert btn-fr-theme">{#start#|ucfirst}</a>
                </div>
            </div>
        {/block}
    </div>
{/block}