{autoload_i18n}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{if $smarty.get.vactivate}{$smarty.config.activate_profil}{else}{$smarty.config.subject_profil}{/if}</title>
    <style>
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
    </style>
</head>
<body>
<!-- <style> -->
<table class="body" data-made-with-foundation>
    <tr>
        <td class="float-center" align="center" valign="top">
            <center data-parsed>
                <table align="center" class="container header float-center">
                    <tr>
                        <td class="wrapper-inner">
                            <table align="center" class="container">
                                <tbody>
                                <tr>
                                    <td valign="middle">
                                        <!-- Gmail/Hotmail image display fix -->
                                        <a href="{geturl}" target ="_blank" title="Dubois - Tanier" style="text-decoration: none;font-size: 46px;">
                                            <img src="{geturl}/skin/img/logo/dubois-tanier.png" alt="Dubois - Tanier" width="120" height="120"/>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                {block name='body:content'}{/block}
            </center>
        </td>
    </tr>
</table>
</body>
</html>