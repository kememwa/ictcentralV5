<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Casual Requisition Approval</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 30px 15px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                       style="max-width: 600px; width: 100%; background: #ffffff; border-radius: 8px; overflow: hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background: #1f2937; padding: 25px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px;">
                                Casual Requisition Approval
                            </h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 30px; color: #374151;">

                            <p style="font-size: 15px;">
                                Dear Approver,
                            </p>

                            <p style="font-size: 15px; line-height: 1.6;">
                                A casual requisition has been approved by the COO and is now awaiting HR approval.
                            </p>

                            {{-- Requisition Details --}}
                            <table width="100%" cellpadding="8" cellspacing="0"
                                   style="margin-top: 20px; border-collapse: collapse;">

                                <tr>
                                    <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                        Requested By
                                    </td>
                                    <td style="border-bottom: 1px solid #e5e7eb;">
                                        {{ $requisition->requester->name ?? 'N/A' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                        Division
                                    </td>
                                    <td style="border-bottom: 1px solid #e5e7eb;">
                                        {{ $requisition->requester->designation->division->name ?? 'N/A' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                        Department
                                    </td>
                                    <td style="border-bottom: 1px solid #e5e7eb;">
                                        {{ $requisition->requester->designation->division->department->name ?? 'N/A' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                        Number of Casuals
                                    </td>
                                    <td style="border-bottom: 1px solid #e5e7eb;">
                                        {{ $requisition->no_of_casuals }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                        Start Date
                                    </td>
                                    <td style="border-bottom: 1px solid #e5e7eb;">
                                        {{ $requisition->start_date }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                        End Date
                                    </td>
                                    <td style="border-bottom: 1px solid #e5e7eb;">
                                        {{ $requisition->end_date }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                        Duration
                                    </td>
                                    <td style="border-bottom: 1px solid #e5e7eb;">
                                        {{ $requisition->duration }} days
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bold;">
                                        Reason
                                    </td>
                                    <td>
                                        {{ $requisition->reason }}
                                    </td>
                                </tr>

                            </table>

                            {{-- Approval History --}}
                            <div style="margin-top: 30px;">

                                <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #1f2937;">
                                    Approval History
                                </h3>

                                <table width="100%" cellpadding="10" cellspacing="0"
                                       style="border-collapse: collapse; border: 1px solid #e5e7eb;">

                                    {{-- HOD --}}
                                    <tr>
                                        <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb; width: 35%;">
                                            HOD Approval
                                        </td>

                                        <td style="border-bottom: 1px solid #e5e7eb; color: #166534;">
                                            <strong>Approved</strong><br>

                                            <span style="color: #374151; font-size: 13px;">
                                                {{ $requisition->hod->name ?? 'N/A' }}
                                            </span>

                                            @if($requisition->hod_approval_date)
                                                <br>
                                                <span style="color: #6b7280; font-size: 12px;">
                                                    {{ \Carbon\Carbon::parse($requisition->hod_approval_date)->format('d M Y, h:i A') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- COO --}}
                                    <tr>
                                        <td style="font-weight: bold;">
                                            COO Approval
                                        </td>

                                        <td style="color: #166534;">
                                            <strong>Approved</strong><br>

                                            <span style="color: #374151; font-size: 13px;">
                                                {{ $requisition->coo->name ?? 'N/A' }}
                                            </span>

                                            @if($requisition->coo_approval_date)
                                                <br>
                                                <span style="color: #6b7280; font-size: 12px;">
                                                    {{ \Carbon\Carbon::parse($requisition->coo_approval_date)->format('d M Y, h:i A') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- HR --}}
                                    <tr>
                                        <td style="font-weight: bold;">
                                            HR Approval
                                        </td>

                                        <td style="color: #d97706;">
                                            <strong>Pending</strong><br>

                                            <span style="color: #6b7280; font-size: 12px;">
                                                Awaiting HR action
                                            </span>
                                        </td>
                                    </tr>

                                </table>

                            </div>

                            {{-- Approval Button --}}
                            <div style="text-align: center; margin-top: 30px;">

                                <a href="{{ route('hr.casual.manage') }}"
                                   style="
                                       display: inline-block;
                                       padding: 12px 24px;
                                       background-color: #2563eb;
                                       color: #ffffff;
                                       text-decoration: none;
                                       border-radius: 6px;
                                       font-size: 14px;
                                       font-weight: bold;
                                   ">
                                    Review Requisition
                                </a>

                            </div>

                            <p style="margin-top: 30px; font-size: 14px; line-height: 1.6;">
                                Please review the requisition and take the necessary
                                action through the ICT Central system.
                            </p>

                            <p style="font-size: 14px;">
                                Regards,<br>
                                <strong>ICT Team</strong>
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f9fafb; padding: 20px; text-align: center;">

                            <p style="margin: 0; font-size: 12px; color: #6b7280;">
                                This is an automated email from ICT Central system.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>