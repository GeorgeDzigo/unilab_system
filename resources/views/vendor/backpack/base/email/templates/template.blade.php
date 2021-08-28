<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <div marginwidth="0" marginheight="0" style="padding:0">
        <div id="m_4473952945165672635wrapper" dir="ltr" style="background-color:#f4f6ff;margin:0;padding:70px 0;width:100%">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                <tbody>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="10" cellspacing="0" width="600" id="m_4473952945165672635template_footer">
                            <tbody>
                            <tr>
                                <td valign="top" style="padding:0;border-radius:6px">
                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td colspan="2" valign="middle" id="m_4473952945165672635credit" style="border-radius:6px;border:0;color:#8a8a8a;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0">
                                                @isset($template->header_picture_path)
                                                    <img src="{{ asset('/storage/template/').'/'.$template->header_picture_path }}" width="100" height="100" style="text-align: center">
                                                @endisset
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="m_4473952945165672635template_container" style="background-color:#ffffff;border:1px solid #dcdde5;border-radius:3px">
                                <tbody>

                                    <tr>
                                        <td align="center" valign="top">
                                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="m_4473952945165672635template_body">
                                                <tbody>
                                                    <tr>
                                                        <td valign="top" id="m_4473952945165672635body_content" style="background-color:#ffffff">
                                                            <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                                <tbody>
                                                                    <tr>
                                                                        <td valign="top" style="padding:48px 48px 32px">
                                                                            <div id="m_4473952945165672635body_content_inner" style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">
                                                                                @isset($personName)
                                                                                <p>@lang('გამარჯობა') @isset($personName) {{ $personName }} @endisset,</p>
                                                                            @endisset

                                                                            {!! $body !!}
                                                                            </div>
                                                                        </td>
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
                        </td>
                    </tr>

                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="10" cellspacing="0" width="600" id="m_4473952945165672635template_footer">
                                <tbody>
                                    <tr>
                                        <td valign="top" style="padding:0;border-radius:6px">
                                            <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" valign="middle" id="m_4473952945165672635credit" style="border-radius:6px;border:0;color:#8a8a8a;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0">
                                                            @isset($template)
                                                                {!! $template->footer_content !!}
                                                            @endisset
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="10" cellspacing="0" width="600" id="m_4473952945165672635template_footer">
                            <tbody>
                            <tr>
                                <td valign="top" style="padding:0;border-radius:6px">
                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td colspan="2" valign="middle" id="m_4473952945165672635credit" style="border-radius:6px;border:0;color:#8a8a8a;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0">
                                                @isset($template->footer_picture_path)
                                                    <img src="{{ asset('/storage/template/').'/'.$template->footer_picture_path }}" width="100" height="100" style="text-align: center">
                                                @endisset
                                            </td>
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
        </div>
    </div>
</body>
</html>
