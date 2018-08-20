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
                        <p>Une réinitialisation de mot de passe à été effectuée pour le compte associé à cette adresse mail.</p>
                        <p>Voici votre nouveau mot de passe: {$data.newPassword}</p>
                    </td>
                </tr>
                <tr>
                    <td class="small-12 large-12 columns first last">
                        <p>Veuillez changer votre mot de passe dès que possible.</p>
                    </td>
                </tr>
                <tr>
                    <td class="small-12 large-12 columns first last">
                        <table class="button">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td><a href="{$url}/admin/index.php?controller=login">Se connecter</a></td>
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