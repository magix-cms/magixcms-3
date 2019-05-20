{autoload_i18n}{widget_about_data}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{if $title_mail}{$title_mail}{/if}</title>
    <style>
        body, table.body, h1, h2, h3, h4, h5, h6, p, td, th, a { font-family: Helvetica, Arial, sans-serif !important; font-weight: normal; }
        h1, h2, h3, h4, h5, h6 { margin: 0; Margin: 0; margin-bottom: 10px; Margin-bottom: 10px; }
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
    <table class="body" style="padding-bottom: 30px;">
    <tr>
        <td class="float-center" align="center" valign="top">
            <center data-parsed="">
                <table align="center" class="container">
                    <tbody>
                    <tr>
                        <td>
                            <table class="row header">
                                <tbody>
                                <tr>
                                    <th class="small-12 large-12 columns first last">
                                        <table style="width: 100%;">
                                            <tr>
                                                <th>
                                                    <table class="spacer">
                                                        <tbody>
                                                        <tr>
                                                            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <p class="text-center" style="text-align: center;">
                                                        <a href="{$url}" target="_blank" title="{$companyData.name}" style="display: block; text-decoration: none; font-size: 46px; padding: 15px;">
                                                            {if $logo && $logo.img.active eq 1}
                                                                <img src="{$url}{$logo.img.medium.src}" width="{$logo.img.medium.w}" height="{$logo.img.medium.h}" alt="{if !empty($logo.img.alt)}{$logo.img.alt}{else}Logo {$companyData.name}{/if}" />
                                                            {else}
                                                                <img src="{$url}/skin/{$theme}/img/logo/{#logo_img_mail#}" alt="Logo {$companyData.name}" width="192" height="95"/>
                                                            {/if}
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