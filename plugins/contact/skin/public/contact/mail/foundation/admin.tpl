{extends file="mail/layout.tpl"}
<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
{block name='body:content'}
    <!-- move the above styles into your custom stylesheet -->
    <table align="center" class="vignette container content float-center">
        <tbody>
        <tr>
            <td>
                <table class="spacer spacer-hr">
                    <tbody>
                    <tr>
                        <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                    </tr>
                    </tbody>
                </table>
                <table class="row">
                    <tbody>
                    <tr>
                        <td class="small-12 large-12 columns first last">
                            <table>
                                <tr>
                                    <td>
                                        <h4>{#object_mail#|ucfirst}&nbsp;: {$data.title}</h4>
                                    </td>
{*                                    <td class="expander"></td>*}
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="small-12 large-12 first last">
                            <table class="spacer-hr">
                                <tr>
                                    <th class="hr" height="2px"></th>
                                </tr>
                                <tr>
                                    <th>
                                        <table>
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>
                            </table>
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
                            <table>
                                <tr>
                                    <td>
                                        <p>{$data.content|replace:'\n\n':'</p><p>'|replace:'\n':'<br />'}</p>
                                    </td>
                                    <td class="expander"></td>
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
                            <table class="spacer-hr">
                                <tr>
                                    <th class="hr" height="2px"></th>
                                </tr>
                                <tr>
                                    <th>
                                        <table>
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="small-12 large-12 columns first last">
                            <table>
                                <tr>
                                    <td>
                                        <p>{#mail_from#|ucfirst} <strong>{$data.firstname}&nbsp;{$data.lastname}</strong></p>
                                        {if $data.address != null}
                                            <p>{$data.address|ucfirst}, {$data.postcode} {$data.city}</p>
                                        {/if}
                                        {if $data.phone != null}
                                            <p>{#mail_phone#|ucfirst}&nbsp;: {$data.phone}</p>
                                        {/if}
                                        <p>{#mail_email#|ucfirst}&nbsp;: <a href="mailto:{$data.email}">{$data.email}</a></p>
                                    </td>
                                </tr>
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