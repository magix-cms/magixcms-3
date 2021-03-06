{extends file="login/mail/layout.tpl"}
<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
{block name='body:content'}
<!-- move the above styles into your custom stylesheet -->
<table align="center" class="container content float-center">
    <tbody>
    <tr>
        <td>
            <table class="spacer">
                <tbody>
                <tr>
                    <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                </tr>
                </tbody>
            </table>
            <table class="row">
                <tbody>
                <tr>
                    <td class="small-12 large-12 first last">
                        <table class="spacer spacer-hr">
                            <tbody>
                            <tr>
                                <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="small-12 large-12 columns first last">
                        <p>Une demande de récupération de mot de passe à été effectuée pour le compte associé à cette adresse mail.</p>
                        <p>Si la demande ne vient pas de vous veuillez ignorer cet email.</p>
                    </td>
                </tr>
                <tr>
                    <td class="small-12 large-12 columns first last">
                        <p>Si la demande vient de vous, veuillez clicker sur le boutton ci-dessous pour continuer la pocédure de récupération de mot de passe.</p>
                    </td>
                </tr>
                <tr>
                    <td class="small-12 large-12 columns first last">
                        <table class="button">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td><a href="{$url}/admin/index.php?controller=login&amp;action=newpwd&amp;k={$data.keyuniqid_admin}&amp;t={$data.ticket}">Demander un nouveau mot de passe</a></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="small-12 large-12 first last">
                        <table class="spacer spacer-hr">
                            <tbody>
                            <tr>
                                <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
{/block}
<!-- End of wrapper table -->