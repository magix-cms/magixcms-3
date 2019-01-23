{extends file="mail/layout.tpl"}
<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
{block name='body:content'}
    <!-- move the above styles into your custom stylesheet -->
    <table align="center" class="vignette container content float-center">
        <tbody>
        <tr>
            <td>
                <table class="spacer {*spacer-hr*}">
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
                                        <h4>{$data.title}</h4>
                                        <p>Error {$data.error} on : <a href="{$data.content}">{$data.content}</a></p>
                                    </td>
                                    <td class="expander"></td>
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