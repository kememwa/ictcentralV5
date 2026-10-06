<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Casual Requisition Approved</title>
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
                                Casual Requisition Approved
                            </h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 30px; color: #374151;">

                            <p style="font-size: 15px;">
                                Dear {{ $requisition->requester->name ?? 'Requester' }},
                            </p>

                            <p style="font-size: 15px; line-height: 1.6;">
                                We are pleased to inform you that your casual requisition has successfully
                                gone through all the required approval processes, and the requested casuals
                                have now been assigned.
                            </p>

                            {{-- Success Notice --}}
                            <div style="
                                margin-top: 20px;
                                padding: 15px;
                                background: #f0fdf4;
                                border: 1px solid #bbf7d0;
                                border-radius: 6px;
                                color: #166534;
                            ">
                                <strong>Requisition Fully Approved</strong><br>
                                <span style="font-size: 13px;">
                                    Approval process completed and casuals successfully assigned.
                                </span>
                            </div>

                            {{-- Requisition Details --}}
                            <table width="100%" cellpadding="8" cellspacing="0"
                                   style="margin-top: 25px; border-collapse: collapse;">

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
                                    <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                        Reason
                                    </td>
                                    <td style="border-bottom: 1px solid #e5e7eb;">
                                        {{ $requisition->reason }}
                                    </td>
                                </tr>

                            </table>

                            {{-- Assigned Casuals --}}
                            <div style="margin-top: 30px;">

                                <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #1f2937;">
                                    Assigned Casuals
                                </h3>

                                <table width="100%" cellpadding="10" cellspacing="0"
                                       style="border-collapse: collapse; border: 1px solid #e5e7eb;">

                                    <tr style="background: #f9fafb;">
                                        <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                            #
                                        </td>
                                        <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                            Casual Name
                                        </td>
                                        <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                            Status
                                        </td>
                                    </tr>

                                    @forelse($assignments as $assignment)
                                        <tr>
                                            <td style="border-bottom: 1px solid #e5e7eb;">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td style="border-bottom: 1px solid #e5e7eb;">
                                                {{ $assignment->casual->name ?? 'N/A' }}
                                            </td>

                                            <td style="border-bottom: 1px solid #e5e7eb; color: #166534;">
                                                <strong>Assigned</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" style="text-align: center; color: #6b7280;">
                                                Casual assignment details unavailable.
                                            </td>
                                        </tr>
                                    @endforelse

                                </table>

                            </div>

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
                                        <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                            COO Approval
                                        </td>

                                        <td style="border-bottom: 1px solid #e5e7eb; color: #166534;">
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

                                    {{-- HRM --}}
                                    <tr>
                                        <td style="font-weight: bold; border-bottom: 1px solid #e5e7eb;">
                                            HRM Approval
                                        </td>

                                        <td style="border-bottom: 1px solid #e5e7eb; color: #166534;">
                                            <strong>Approved</strong><br>

                                            <span style="color: #374151; font-size: 13px;">
                                                {{ $requisition->hrm->name ?? 'N/A' }}
                                            </span>

                                            @if($requisition->hrm_approval_date)
                                                <br>
                                                <span style="color: #6b7280; font-size: 12px;">
                                                    {{ \Carbon\Carbon::parse($requisition->hrm_approval_date)->format('d M Y, h:i A') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Casual Assignment --}}
                                    <tr>
                                        <td style="font-weight: bold;">
                                            Casual Assignment
                                        </td>

                                        <td style="color: #166534;">
                                            <strong>Completed</strong><br>

                                            <span style="color: #374151; font-size: 13px;">
                                                {{ $requisition->hrRep->name ?? 'N/A' }}
                                            </span>

                                            @if($requisition->casual_assignment_date)
                                                <br>
                                                <span style="color: #6b7280; font-size: 12px;">
                                                    {{ \Carbon\Carbon::parse($requisition->casual_assignment_date)->format('d M Y, h:i A') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                </table>

                            </div>

                            <p style="margin-top: 30px; font-size: 14px; line-height: 1.6;">
                                The approval and assignment process is now complete. The assigned casuals
                                can proceed according to the approved requisition dates and requirements.
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