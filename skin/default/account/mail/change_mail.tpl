{extends file="mail/layout.tpl"}
{block name='body:content'}
    <table class="spacer">
        <tbody>
        <tr>
            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
        </tr>
        </tbody>
    </table>
    <h1 class="text-center">{#change_mail_text#|sprintf:$companyData.name}</h1>
    <table class="spacer">
        <tbody>
        <tr>
            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
        </tr>
        </tbody>
    </table>
    <p class="text-center">{#change_mail_new#}&nbsp;{$data.email_ac}</p>
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
    <hr>
    <p class="text-left"><small>{#noreply_mail#}</small></p>
{/block}