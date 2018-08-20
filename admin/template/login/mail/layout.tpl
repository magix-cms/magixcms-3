{autoload_i18n}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{if $smarty.get.vactivate}{$smarty.config.activate_profil}{else}{$smarty.config.subject_profil}{/if}</title>
    {*<style>
        .header {
            background: {$getDataCSSIColor[0].color_cssi};
        }

        .header .columns {
            padding-bottom: 0;
        }

        .header p {
            color: {$getDataCSSIColor[1].color_cssi};
            margin-bottom: 0;
        }

        .header .wrapper-inner {
            padding: 0;
            /*controls the height of the header*/
        }

        .header .container {
            background: {$getDataCSSIColor[0].color_cssi};
        }

        .header .container td {
            padding: 15px;
        }

        .spacer.spacer-hr td{
            border-top: 1px solid #eeeeee;
        }
        .footer{
            background: {$getDataCSSIColor[2].color_cssi};
        }
        .footer p{
            color: {$getDataCSSIColor[3].color_cssi};
            margin-bottom: 0;
        }
        .footer ul{
            list-style: none;
        }
        .footer ul li{
            color: {$getDataCSSIColor[3].color_cssi};
        }
        .footer .container {
            background: {$getDataCSSIColor[2].color_cssi};
        }
        .footer .container td{
            padding: 15px;
        }
    </style>*}
    <style>
        .header { background: {$getDataCSSIColor[0].color_cssi}; }
        .header .container { background: {$getDataCSSIColor[0].color_cssi}; }
        .header p { color: {$getDataCSSIColor[1].color_cssi}; }
        .footer{ background: {$getDataCSSIColor[2].color_cssi}; }
        .footer .container { background: {$getDataCSSIColor[2].color_cssi}; }
        .footer p{ color: {$getDataCSSIColor[3].color_cssi}; }
        .footer ul li{ color: {$getDataCSSIColor[3].color_cssi}; }
    </style>
</head>
<body>
    <span class="preheader"></span>
    <table class="body">
        <tr>
            <td {*class="center"*} {*align="center"*} valign="top">
                <center data-parsed="">
                    <table {*align="center"*} {*class="float-center"*}>
                        <tbody>
                        <tr>
                            <td>
                                <table class="row">
                                    <tbody>
                                    <tr>
                                        <th class="small-12 large-12 columns first last">
                                            <table>
                                                <tr>
                                                    <th>
                                                        <table class="spacer">
                                                            <tbody>
                                                            <tr>
                                                                <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <p class="text-right" style="text-align: right;">
                                                            <a href="{$url}" target ="_blank" title="Magix CMS" style="text-decoration: none;font-size: 46px;">
                                                                <img src="{$url}/admin/template/img/logo/logo-magix_cms@229.png" alt="Magix CMS" width="229" height="50"/>
                                                            </a>
                                                        </p>
                                                        <table class="spacer">
                                                            <tbody>
                                                            <tr>
                                                                <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </th>
                                                    <th class="expander"></th>
                                                </tr>
                                            </table>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="container">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="row">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <table class="row">
                                                            <tbody>
                                                            <tr>
                                                                <th class="vignette small-12 large-12 columns first last">
                                                                    <table>
                                                                        <tr>
                                                                            <th>
                                                                                {block name='body:content'}{/block}
                                                                            </th>
                                                                            <th class="expander"></th>
                                                                        </tr>
                                                                    </table>
                                                                </th>
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
                                <table class="spacer">
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
                </center>
            </td>
        </tr>
    </table>
    <!-- prevent Gmail on iOS font size manipulation -->
    <div style="display:none; white-space:nowrap; font:15px courier; line-height:0;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
</body>
</html>