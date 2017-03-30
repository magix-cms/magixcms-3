{extends file="layout.tpl"}
{block name='head:title'}{#login_pwd#|ucfirst}{/block}
{block name='body:id'}login{/block}
{block name="header"}{/block}

{block name="main"}
    <main id="page" class="container-fluid">
        <div class="login-panel">
            {if $error}
                <div class="error">
                    {$error}
                </div>
            {/if}
            {if $debug}
                {$debug}
            {/if}
            <div id="logo">
                <img src="/{baseadmin}/template/img/logo/logo-magix_cms.png" alt="Magix CMS" width="269" height="50">
            </div>
            <div class="flip-container">
                <div class="flipper">
                    <div class="pwd-box front panel">
                        <form id="forgot_password_form" method="post" action="#">
                            {if isset($error_tikcet) && $error_tikcet}
                            <div class="mc-message alert alert-warning">
                                <h4>Impossible de renouveler le mot de passe</h4>
                                <p>Aucune demande de renouvellement de mot de passe n'a été enregistrée pour ce compte.</p>
                            </div>
                            {else}
                            <div class="mc-message alert alert-success">
                                <h4>Mot de passe renouvelé !</h4>
                                <p>Votre mot de passe à été réinitialisé. Vous devirez recevoir un mail avec votre nouveau mot de passe.</p>
                                <p>Si vous ne recevez pas de mail, veuillez consulter votre dossier de spam.</p>
                            </div>
                            {/if}
                            <div class="panel-footer">
                                <a class="btn btn-default" href="{geturl}/login.php" type="button">
                                    <i class="icon-caret-left"></i>
                                    Retour à la connexion
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <p><i class="fa fa-copyright"></i> 2016 Magix CMS &mdash; Tous droits réservés</p>
        </div>
    </main>
{/block}

{block name="foot" append}
    {script src="/min/?f=/skin/js/login.min.js" type="javascript"}
{/block}