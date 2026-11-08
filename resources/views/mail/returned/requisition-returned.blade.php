
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Returned</title>
</head>

<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color:#f1f5f9; padding:35px 12px;">

        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:600px; width:100%; background-color:#ffffff; border-radius:12px; overflow:hidden;">

                    {{-- HEADER --}}
                    <tr>
                        <td align="center"
                            style="background-color:#062f22; padding:28px 20px;">

                            <h1 style="margin:0; color:#ffffff; font-size:23px; font-weight:700;">
                                ICT Central
                            </h1>

                            <p style="margin:7px 0 0; color:#d1e5dc; font-size:12px;">
                                Powered by Kim-Fay ICT Team
                            </p>

                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:32px 30px;">

                            <h2 style="margin:0 0 18px; color:#1e293b; font-size:21px;">
                                Requisition Returned
                            </h2>

                            <p style="color:#475569; font-size:14px; line-height:1.7;">
                                Hello <strong>{{ $requester->name }}</strong>,
                            </p>

                            <p style="color:#475569; font-size:14px; line-height:1.7;">
                                Your casual requisition has been returned for correction.
                                Please review the feedback below and make the necessary
                                amendments before resubmitting it.
                            </p>

                            {{-- REQUISITION DETAILS --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin:22px 0; background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">

                                <tr>
                                    <td style="padding:15px 16px;">

                                        <p style="margin:0 0 12px; color:#64748b; font-size:11px; font-weight:bold; text-transform:uppercase;">
                                            Requisition Details
                                        </p>

                                        <p style="margin:0 0 8px; color:#334155; font-size:13px;">
                                            <strong>Reference:</strong>
                                            {{ $requisition->ref_number }}
                                        </p>

                                        <p style="margin:0 0 8px; color:#334155; font-size:13px;">
                                            <strong>Requester:</strong>
                                            {{ $requester->name }}
                                        </p>

                                        <p style="margin:0; color:#334155; font-size:13px;">
                                            <strong>Returned by:</strong>
                                            {{ $returnedBy }}
                                        </p>

                                    </td>
                                </tr>
                            </table>

                            {{-- RETURN REASON --}}
                            <div style="margin:22px 0; padding:16px; background-color:#fffbeb; border-left:4px solid #d97706; border-radius:4px;">

                                <p style="margin:0 0 9px; color:#92400e; font-size:12px; font-weight:bold; text-transform:uppercase;">
                                    Reason for Return
                                </p>

                                <p style="margin:0; color:#78350f; font-size:14px; line-height:1.7; white-space:pre-line;">
                                    {{ $returnReason }}
                                </p>

                            </div>

                            <p style="color:#475569; font-size:14px; line-height:1.7;">
                                Kindly use the button below to access your requisition,
                                review the comments and make the required corrections.
                            </p>

                            {{-- ACTION BUTTON --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin:28px 0;">

                                <tr>
                                    <td align="center">

                                        <a href="{{ $actionUrl }}"
                                            style="display:inline-block; padding:13px 26px; background-color:#065f46; color:#ffffff; text-decoration:none; font-size:14px; font-weight:bold; border-radius:7px;">

                                            View &amp; Edit Requisition

                                        </a>

                                    </td>
                                </tr>

                            </table>

                            <p style="color:#64748b; font-size:12px; line-height:1.7;">
                                If the button does not work, copy and paste the following
                                link into your browser:
                            </p>

                            <p style="font-size:12px; word-break:break-all;">
                                <a href="{{ $actionUrl }}" style="color:#047857;">
                                    {{ $actionUrl }}
                                </a>
                            </p>

                            <p style="margin-top:25px; color:#475569; font-size:13px; line-height:1.7;">
                                Regards,<br>
                                <strong>Kim-Fay ICT Team</strong>
                            </p>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td align="center"
                            style="padding:20px; background-color:#f8fafc; border-top:1px solid #e2e8f0;">

                            <p style="margin:0 0 5px; color:#64748b; font-size:11px;">
                                This is an automated notification from ICT Central.
                            </p>

                            <p style="margin:0; color:#94a3b8; font-size:10px;">
                                Please do not reply directly to this email.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
