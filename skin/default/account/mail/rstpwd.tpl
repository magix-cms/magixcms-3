{extends file="mail/layout.tpl"}
{block name='body:content'}
    <table class="spacer">
        <tbody>
        <tr>
            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
        </tr>
        </tbody>
    </table>
    <h1 class="text-center">{#rstpwd_title#}</h1>
    <table class="spacer">
        <tbody>
        <tr>
            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
        </tr>
        </tbody>
    </table>
    <p class="text-center">{#rst_info_1#|sprintf:$companyData.name}</p>
    <p class="text-center">{#rst_info_2#}</p>
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
                                <a href="{geturl}/{getlang}/account/{$data.keyuniqid_ac}/newpwd/?key={$data.token}" align="center" class="float-center">{#rst_pwd#}</a>
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