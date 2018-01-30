{extends file="mail/layout.tpl"}
{block name='body:content'}
    <table class="spacer">
        <tbody>
        <tr>
            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
        </tr>
        </tbody>
    </table>
    <h1 class="text-center">{#login_text_mail#}</h1>
    <table class="spacer">
        <tbody>
        <tr>
            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
        </tr>
        </tbody>
    </table>
    <table class="button large expand">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>
                            <center data-parsed="">
                                <a href="{geturl}/{getlang}/account/{$data.keyuniqid_ac}/login/" align="center" class="float-center">{#login_title_mail#}</a>
                            </center>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="expander"></td>
        </tr>
    </table>
    <table class="spacer">
        <tbody>
        <tr>
            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
        </tr>
        </tbody>
    </table>
    <p class="text-center">(&thinsp;{#your_login#|ucfirst}&nbsp;: {$data.email_ac}&thinsp;)</p>
    <table class="spacer">
        <tbody>
        <tr>
            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
        </tr>
        </tbody>
    </table>
    <p class="text-center">{#signup_text_footer_mail#|sprintf:$companyData.name}</p>
    <hr>
    <p class="text-left"><small>{#noreply_mail#}</small></p>
{/block}