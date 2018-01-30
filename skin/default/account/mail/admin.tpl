{extends file="account/mail/layout.tpl"}
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
                    <td class="small-12 large-6 columns first last">
                        {if $smarty.get.pstring1 == 'activate'}
                            <table class="row">
                                <tr>
                                    <td>
                                        <p>{#login_text_mail#}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class="button">
                                            <tr>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <a href="{geturl}/{getlang}/account/login_redirect/">{#login_title_mail#}</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>(&thinsp;{#your_login#|ucfirst}&nbsp;: {$data.email}&thinsp;)</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>{$smarty.config.activation_text_footer_mail|sprintf:{#website_name#}}</p>
                                    </td>
                                </tr>
                            </table>
                        {elseif $smarty.get.pstring1 == "lostpassword"}
                            {capture name="loginLinkMail"}
                                <a href="{geturl}/{getlang}/account/login_redirect/"> {#login_title_mail#}</a>
                            {/capture}
                            <table class="row">
                                <tr>
                                    <td>
                                        <p>{#password_renew_text_mail#}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class="button">
                                            <tr>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                {$smarty.capture.loginLinkMail}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <ul>
                                            <li><strong>{#pn_account_mail#|ucfirst}</strong> : {$data.email}</li>
                                            <li><strong>{#pn_account_password#|ucfirst}</strong> : {$data.password}</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        {else}
                            <table class="row">
                                <tr>
                                    <td>
                                        <h1>{$setTitle}</h1>
                                        <p>{$smarty.config.activation_thx_mail|sprintf:{#website_name#}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>{$smarty.config.activation_text_mail}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class="button">
                                            <tr>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <a href="{geturl}/{getlang}/account/activate/{$data.keyuniqid_ac}">
                                                                    {#activation_title_mail#}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <ul>
                                            <li>{#pn_account_firstname#|ucfirst} : {$data.firstname_ac}</li>
                                            <li>{#pn_account_lastname#|ucfirst} : {$data.lastname_ac}</li>
                                            <li>{#pn_account_mail#|ucfirst} : {$data.email_ac}</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>{$smarty.config.activation_text_footer_mail|sprintf:{#website_name#}}</p>
                                    </td>
                                </tr>
                            </table>
                        {/if}
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