<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Rejected</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#334155;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background-color:#f4f6f8; padding:30px 12px;">

        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                       style="max-width:600px; width:100%; background-color:#ffffff; border-radius:10px; overflow:hidden;">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background-color:#063b2b; padding:25px 30px; text-align:center;">

                            <h2 style="margin:0; color:#ffffff; font-size:21px;">
                                ICT Central
                            </h2>

                            <p style="margin:6px 0 0; color:#d1e7dd; font-size:12px;">
                                Kim-Fay East Africa Limited
                            </p>

                        </td>
                    </tr>

                    {{-- CONTENT --}}
                    <tr>
                        <td style="padding:30px;">

                            <h2 style="margin:0 0 18px; color:#dc2626; font-size:20px;">
                                Requisition Rejected
                            </h2>

                            <p style="font-size:14px; line-height:1.7; margin:0 0 15px;">
                                Dear {{ $requester->name }},
                            </p>

                            <p style="font-size:14px; line-height:1.7; margin:0 0 20px;">
                                We regret to inform you that your casual workforce requisition
                                has been <strong style="color:#dc2626;">rejected</strong>
                                during the approval process.
                            </p>

                            {{-- REQUISITION DETAILS --}}
                            <table width="100%" cellpadding="9" cellspacing="0"
                                   style="border-collapse:collapse; background-color:#f8fafc; border:1px solid #e2e8f0;">

                                <tr>
                                    <td colspan="2"
                                        style="background-color:#f1f5f9; font-size:13px; font-weight:bold; color:#334155;">
                                        REQUISITION DETAILS
                                    </td>
                                </tr>

                                <tr>
                                    <td width="40%" style="font-size:12px; color:#64748b;">
                                        Reference Number
                                    </td>
                                    <td style="font-size:13px; font-weight:bold; color:#0f172a;">
                                        {{ $requisition->ref_number }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size:12px; color:#64748b;">
                                        Number of Casuals
                                    </td>
                                    <td style="font-size:13px; color:#334155;">
                                        {{ $requisition->no_of_casuals }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size:12px; color:#64748b;">
                                        Start Date
                                    </td>
                                    <td style="font-size:13px; color:#334155;">
                                        {{ $requisition->start_date?->format('d M Y') ?? '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size:12px; color:#64748b;">
                                        End Date
                                    </td>
                                    <td style="font-size:13px; color:#334155;">
                                        {{ $requisition->end_date?->format('d M Y') ?? '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size:12px; color:#64748b;">
                                        Rejected By
                                    </td>
                                    <td style="font-size:13px; color:#334155;">
                                        {{ $rejectedBy }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size:12px; color:#64748b;">
                                        Rejection Date
                                    </td>
                                    <td style="font-size:13px; color:#334155;">
                                        {{ $requisition->rejected_at?->format('d M Y, h:i A') ?? now()->format('d M Y, h:i A') }}
                                    </td>
                                </tr>

                            </table>

                            {{-- REJECTION REASON --}}
                            <div style="margin-top:22px; padding:16px; background-color:#fef2f2; border-left:4px solid #dc2626;">

                                <p style="margin:0 0 8px; font-size:13px; font-weight:bold; color:#991b1b;">
                                    Reason for Rejection
                                </p>

                                <p style="margin:0; font-size:13px; line-height:1.7; color:#7f1d1d; white-space:pre-line;">
                                    {{ $rejectionReason }}
                                </p>

                            </div>

                            <p style="margin:22px 0; font-size:13px; line-height:1.7; color:#475569;">
                                This rejection is final, and the requisition has been closed
                                in the approval workflow.
                            </p>

                            {{-- ACTION BUTTON --}}
                            <div style="text-align:center; margin:25px 0;">

                                <a href="{{ $actionUrl }}"
                                   style="display:inline-block; padding:13px 25px; background-color:#063b2b; color:#ffffff; text-decoration:none; border-radius:6px; font-size:13px; font-weight:bold;">
                                    Create New Requisition
                                </a>

                            </div>

                            <p style="font-size:13px; line-height:1.7; color:#64748b;">
                                If you require clarification regarding this decision,
                                kindly contact the relevant approving department.
                            </p>

                            <p style="font-size:13px; line-height:1.7; margin-top:20px;">
                                Regards,<br>
                                <strong>Kim-Fay ICT Central</strong>
                            </p>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background-color:#f8fafc; border-top:1px solid #e2e8f0; padding:18px 25px; text-align:center;">

                            <p style="margin:0; font-size:11px; color:#94a3b8;">
                                This is an automated notification from ICT Central.
                                Please do not reply to this email.
                            </p>

                            <p style="margin:8px 0 0; font-size:11px; color:#94a3b8;">
                                &copy; {{ date('Y') }} Kim-Fay East Africa Limited.
                                All rights reserved.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>
</html>
