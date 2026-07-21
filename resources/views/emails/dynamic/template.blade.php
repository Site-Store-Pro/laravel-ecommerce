<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? '' }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            max-width: 100%;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding: 40px 0;
        }
        .container {
            max-width: 640px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px border-solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .header-content {
            padding: 20px;
            text-align: center;
        }
        .banner {
            width: 100%;
            display: block;
        }
        .content-body {
            padding: 40px 32px;
            line-height: 1.6;
            font-size: 16px;
        }
        .content-body p {
            margin-top: 0;
            margin-bottom: 16px;
        }
        .signature-block {
            margin-top: 32px;
            border-top: 1px solid #f1f5f9;
            padding-top: 24px;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 24px 32px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
        .footer-image {
            margin-top: 16px;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="wrapper">
        <tr>
            <td align="center" valign="top">
                @if(!empty($header_html))
                    <div style="max-width: 640px; margin-bottom: 20px; text-align: left;">
                        {!! $header_html !!}
                    </div>
                @endif

                <table cellpadding="0" cellspacing="0" border="0" class="container">
                    @if($show_banner && !empty($banner_image_url))
                        <tr>
                            <td valign="top" align="center">
                                @if(!empty($banner_image_link))
                                    <a href="{{ $banner_image_link }}" target="_blank">
                                        <img src="{{ $banner_image_url }}" alt="Banner" class="banner">
                                    </a>
                                @else
                                    <img src="{{ $banner_image_url }}" alt="Banner" class="banner">
                                @endif
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="content-body" align="left" valign="top">
                            @if($include_salutation && !empty($salutation))
                                <p><strong>{!! $salutation !!}</strong></p>
                            @endif

                            @if(!empty($greeting))
                                <div>{!! $greeting !!}</div>
                            @endif

                            @if(!empty($body))
                                <div style="margin-top: 20px;">{!! $body !!}</div>
                            @endif

                            @if(!empty($sign_off) || !empty($signature))
                                <div class="signature-block">
                                    @if(!empty($sign_off))
                                        <p style="margin-bottom: 8px;">{!! $sign_off !!}</p>
                                    @endif
                                    @if(!empty($signature))
                                        <p style="margin-top: 0; font-weight: 600;">{!! $signature !!}</p>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>

                    @if(!empty($disclaimer) || !empty($copyright) || ($show_footer_image && !empty($footer_image_url)) || !empty($footer_html))
                        <tr>
                            <td class="footer" valign="top">
                                @if(!empty($disclaimer))
                                    <p style="margin-top: 0; margin-bottom: 12px; font-style: italic;">{!! $disclaimer !!}</p>
                                @endif

                                @if(!empty($copyright))
                                    <p style="margin-top: 0; margin-bottom: 12px;">{!! $copyright !!}</p>
                                @endif

                                @if(!empty($footer_html))
                                    <div style="margin-top: 12px; text-align: center;">
                                        {!! $footer_html !!}
                                    </div>
                                @endif

                                @if($show_footer_image && !empty($footer_image_url))
                                    <div style="margin-top: 16px;">
                                        @if(!empty($footer_image_link))
                                            <a href="{{ $footer_image_link }}" target="_blank">
                                                <img src="{{ $footer_image_url }}" alt="Footer Logo" class="footer-image">
                                            </a>
                                        @else
                                            <img src="{{ $footer_image_url }}" alt="Footer Logo" class="footer-image">
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
