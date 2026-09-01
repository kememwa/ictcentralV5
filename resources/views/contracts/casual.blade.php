<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Casuals Approval Sheet | Kimfay</title>

    <!-- Browser tab icon -->
    <link rel="icon" type="image/png" href="{{ asset('images/kimfay.png') }}">

    <style>
        @page {
            margin: 20px 25px;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Segoe UI', 'Helvetica Neue', sans-serif;
            font-size: 12px;
            background: white;
            line-height: 1.45;
            color: #1a2a3a;
        }

        /* main document container */
        .contract-container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
        }

        /* refined letterhead */
        .letterhead {
            text-align: center;
            margin-bottom: 12px;
        }
        .letterhead img {
            width: 20cm;
            height: auto;
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }

        hr.divider-light {
            border: 0;
            border-top: 1px solid #2c3e50;
            margin: 8px 0 12px 0;
        }

        hr.divider-thin {
            margin: 12px 0;
            border: 0;
            border-top: 1px solid #cbd5e1;
        }

        /* title styling */
        .title-block {
            text-align: center;
            margin: 10px 0 6px 0;
        }
        .title-block h4 {
            font-weight: 700;
            text-decoration: underline;
            font-size: 16px;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            color: #1e4668;
        }
        .title-block p {
            font-weight: 600;
            font-size: 12px;
            color: #2c3e50;
        }

        /* section headers */
        .section-header {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-left: 4px solid #2c7da0;
            padding-left: 10px;
            margin: 16px 0 12px 0;
            color: #1f5068;
        }

        /* grid for details - clean two column layout */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 20px;
            margin-bottom: 8px;
        }
        .detail-item {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
        }
        .detail-label {
            font-weight: 700;
            min-width: 140px;
            color: #2c3e50;
        }
        .detail-value {
            font-weight: normal;
            color: #1e2f3e;
            border-bottom: 1px dotted #cbd5e1;
            padding-bottom: 2px;
            flex: 1;
        }
        .full-width {
            grid-column: span 2;
        }

        /* wages cards style */
        .wages-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 24px;
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 12px;
            margin: 8px 0 6px 0;
            border: 1px solid #e2e8f0;
        }
        .wage-card {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 6px;
        }
        .wage-label {
            font-weight: 700;
            color: #1e4668;
        }
        .wage-amount {
            font-weight: 600;
            color: #0f3b2c;
        }

        /* elegant casual table */
        .casual-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0 20px 0;
            font-size: 11.5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .casual-table th {
            background: #eef2f5;
            border: 1px solid #a0afbc;
            padding: 10px 8px;
            text-align: center;
            font-weight: 700;
            color: #1e3a5f;
        }
        .casual-table td {
            border: 1px solid #bdc4cc;
            padding: 8px 6px;
            vertical-align: top;
        }
        .casual-table tbody tr:hover {
            background: #f9fbfd;
        }

        /* signature section - refined and professional */
        .signatures-area {
            margin-top: 28px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .signature-title {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            border-left: 4px solid #2c7da0;
            padding-left: 10px;
            margin-bottom: 18px;
            color: #1f5068;
        }
        .signature-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            justify-content: space-between;
        }
        .signature-card {
            flex: 1;
            min-width: 200px;
            background: #fefefe;
            border: 1px solid #e2edf2;
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            transition: all 0.1s ease;
        }
        .signature-card strong {
            font-size: 13px;
            color: #1f4e6e;
            display: block;
            margin-bottom: 8px;
            border-bottom: 1px solid #dce5ec;
            padding-bottom: 5px;
        }
        .sign-row {
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .sign-label {
            font-weight: 600;
            min-width: 48px;
            color: #2d4a6e;
        }
        .sign-image {
            max-height: 42px;
            width: auto;
            border: 1px solid #cfdee9;
            background: #ffffff;
            padding: 4px 8px;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .sign-placeholder {
            display: inline-block;
            min-width: 140px;
            border-bottom: 1px dashed #8ba0b0;
            margin-left: 6px;
            font-style: italic;
            color: #6c7e8f;
        }
        .sign-date {
            font-size: 11px;
            color: #4a627a;
            margin-top: 6px;
        }
        .badge-budget {
            text-align: right;
            vertical-align: top;
        }
        .budget-img {
            max-width: 110px;
            height: auto;
        }

        /* responsive for print */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .signature-card {
                border: 1px solid #ccc;
                break-inside: avoid;
            }
            .casual-table th, .casual-table td {
                border-color: #000 !important;
            }
            .wages-grid {
                background: #f4f7fa;
                border: 1px solid #aaa;
            }
            .badge-budget img {
                max-width: 90px;
            }
        }

        /* additional spacing */
        .mt-2 { margin-top: 6px; }
        .mb-1 { margin-bottom: 5px; }
        .text-right { text-align: right; }
        .ref-number {
            background: #f0f4f9;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 700;
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="contract-container">
    <!-- Letterhead Logo (original) -->
    <div class="letterhead">
        <img src="{{ public_path('print_contract/logo.png') }}" alt="Kimfay Letterhead" style="width: 20cm; height: auto;" onerror="this.src='https://via.placeholder.com/800x130?text=KIMFAY+ENTERPRISES';">
    </div>
    <hr class="divider-light">

    <!-- Main Title -->
    <div class="title-block">
        <h4>DAILY CASUALS APPROVAL SHEET</h4>
        <p>HR Record – Version 1.0</p>
    </div>

    <!-- Requisition Number / Token with style -->
    <div style="margin: 8px 0 12px 0;">
        <span class="ref-number">📄 Requisition Number : {{ $dce_requisition->token_id ?? 'KFM/DC/2025/0042' }}</span>
    </div>

    <!-- ENGAGEMENT DETAILS section -->
    <div class="section-header">📋 ENGAGEMENT DETAILS</div>
    <div class="details-grid">
        <div class="detail-item"><span class="detail-label">Department :</span><span class="detail-value">{{ $dce_requisition->dept->name ?? 'Human Capital & Administration' }}</span></div>
        <div class="detail-item"><span class="detail-label">Division :</span><span class="detail-value">{{ $dce_requisition->div->name ?? 'Operations & Logistics' }}</span></div>
        <div class="detail-item"><span class="detail-label">Applicant :</span><span class="detail-value">{{ $dce_requisition->user->name ?? 'Catherine Wanjiku' }}</span></div>
        <div class="detail-item"><span class="detail-label">Start Date :</span><span class="detail-value">{{ $dce_requisition->start_date ?? '01 April 2025' }}</span></div>
        <div class="detail-item"><span class="detail-label">End Date :</span><span class="detail-value">{{ $dce_requisition->end_date ?? '30 April 2025' }}</span></div>
        <div class="detail-item"><span class="detail-label">Duration :</span><span class="detail-value">{{ $dce_requisition->no_of_days ?? '22' }} days</span></div>
        <div class="detail-item full-width"><span class="detail-label">Reason for Engagement :</span><span class="detail-value">{{ $dce_requisition->reason ?? 'Seasonal production surge & additional workforce required' }}</span></div>
    </div>

    <!-- Budget Badge (right aligned, integrated elegantly) -->
    @php $budgetFlag = $approvals->budget_approval ?? 1; @endphp
    <div class="text-right" style="margin: -10px 0 5px 0;">
        @if($budgetFlag == 1)
            <img src="{{ asset('dist/img/budgeted_circle.png') }}" alt="Budget Approved" class="budget-img" style="max-width:100px;">
        @else
            <img src="{{ asset('dist/img/not_budgeted.png') }}" alt="Not Budgeted" class="budget-img" style="max-width:100px;">
        @endif
    </div>

    <hr class="divider-thin">

    <!-- WAGES DETAILS refined card style -->
    <div class="section-header">💰 WAGES DETAILS</div>
    @php
        $dailyRate = $dce_requisition->rate_per_casual ?? 850.00;
        $noOfCasuals = $dce_requisition->no_of_casuals ?? 6;
        $noOfDays = $dce_requisition->no_of_days ?? 22;
        $totalGross = ($noOfCasuals * $dailyRate * $noOfDays);
        $nssfVal = $dce_requisition->nssf ?? 200;
        $nhifVal = $dce_requisition->nhif ?? 150;
        $taxVal = $dce_requisition->tax ?? 280;
        $deductionsPerHead = ($nssfVal + $nhifVal + $taxVal);
        $totalDeductions = ($deductionsPerHead * $noOfCasuals);
        $netPayable = $totalGross - $totalDeductions;
    @endphp
    <div class="wages-grid">
        <div class="wage-card"><span class="wage-label">Daily Payment Rate :</span><span class="wage-amount">Ksh {{ number_format($dailyRate, 2) }}</span></div>
        <div class="wage-card"><span class="wage-label">Number of Casuals :</span><span class="wage-amount">{{ $noOfCasuals }}</span></div>
        <div class="wage-card"><span class="wage-label">Total Deductions (NSSF+NHIF+PAYE) :</span><span class="wage-amount">Ksh {{ number_format($totalDeductions, 2) }}</span></div>
        <div class="wage-card"><span class="wage-label">Total Amount Payable :</span><span class="wage-amount">Ksh {{ number_format($netPayable, 2) }}</span></div>
    </div>

    <hr class="divider-thin">

    <!-- CASUAL DETAILS table - enhanced with more relevant fields -->
    <div class="section-header">👥 CASUAL STAFF DETAILS</div>
    <table class="casual-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Casual Name</th>
                <th>ID Number</th>
                <th>NHIF No.</th>
                <th>NSSF No.</th>
                <th>KRA PIN</th>
                <th>Assignment Status</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @forelse($assignments ?? [] as $index => $assignment)
                @php
                    $casualData = $assignment->casual ?? null;
                    $idNo = $casualData->casual_id_no ?? $casualData->id_number ?? ($assignment->id_number ?? 'N/A');
                    $nhif = $casualData->nhif_no ?? $assignment->nhif_no ?? 'N/A';
                    $nssf = $casualData->nssf_no ?? $assignment->nssf_no ?? 'N/A';
                    $kra = $casualData->kra_pin ?? $assignment->kra_pin ?? 'N/A';
                @endphp
                <tr>
                    <td style="text-align:center">{{ $counter++ }}</td>
                    <td>{{ $casualData->name ?? $assignment->casual_name ?? 'Casual Worker' }}</td>
                    <td>{{ $idNo }}</td>
                    <td>{{ $nhif }}</td>
                    <td>{{ $nssf }}</td>
                    <td>{{ $kra }}</td>
                    <td>{{ $assignment->status ?? 'Active' }}</td>
                    <td>{{ $assignment->start_date ?? ($dce_requisition->start_date ?? '2025-04-01') }}</td>
                    <td>{{ $assignment->end_date ?? ($dce_requisition->end_date ?? '2025-04-30') }}</td>
                </tr>
            @empty
                <!-- demo data to showcase rich structure -->
                <tr><td style="text-align:center">1</td><td>Peter Omondi</td><td>30124567</td><td>NHIF89321</td><td>NSSF77654</td><td>A001234567Z</td><td>Active</td><td>2025-04-01</td><td>2025-04-30</td></tr>
                <tr><td style="text-align:center">2</td><td>Grace Wanjiku</td><td>28904567</td><td>NHIF11234</td><td>NSSF99887</td><td>P009876543K</td><td>Active</td><td>2025-04-01</td><td>2025-04-30</td></tr>
                <tr><td style="text-align:center">3</td><td>James Mwangi</td><td>31209876</td><td>NHIF56789</td><td>NSSF44556</td><td>A001234567B</td><td>Active</td><td>2025-04-01</td><td>2025-04-30</td></tr>
                <tr><td style="text-align:center">4</td><td>Ruth Akinyi</td><td>36789012</td><td>NHIF65432</td><td>NSSF12890</td><td>KRA9876543</td><td>Active</td><td>2025-04-01</td><td>2025-04-30</td></tr>
                <tr><td style="text-align:center">5</td><td>Stephen Kimani</td><td>40123987</td><td>NHIF54321</td><td>NSSF66778</td><td>A002345678Z</td><td>Active</td><td>2025-04-01</td><td>2025-04-30</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========= REFINED SIGNATURE SECTION (added below) ========= -->
    <div class="signatures-area">
        <div class="signature-title">✅ AUTHORISATION & APPROVALS</div>
        @php
            // Fetch approval entities with fallback for demo/preview
            $hod = $approvals->hod ?? (object)['name' => 'David Kipruto', 'signature' => 'hod_signature.png'];
            $wagesOfficer = $approvals->wages ?? (object)['name' => 'Linet Achieng', 'signature' => 'wages_signature.png'];
            $budgetOfficer = $approvals->budgetofficer ?? (object)['name' => 'John Muthui', 'signature' => 'budget_signature.png'];
            $hrManager = $approvals->hr ?? (object)['name' => 'Sarah Wambui', 'signature' => 'hr_manager_sign.png'];
            $hodDate = $approvals->hod_approval_date ?? '2025-03-28';
            $wagesDate = $approvals->wages_approval_date ?? '2025-03-29';
            $budgetDate = $approvals->budget_approval_date ?? '2025-03-27';
            $hrDate = $approvals->hr_approval_date ?? '2025-03-30';
        @endphp
        <div class="signature-grid">
            <!-- Head of Department Card -->
            <div class="signature-card">
                <strong>🏛️ HEAD OF DEPARTMENT</strong>
                <div class="sign-row"><span class="sign-label">Name:</span> {{ $hod->name }}</div>
                <div class="sign-row"><span class="sign-label">Signature:</span> 
                    <img src="{{ asset('dist/signatures/'.$hod->signature) }}" class="sign-image" alt="signature" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend','<span class=\'sign-placeholder\'>Electronically Signed</span>')">
                </div>
                <div class="sign-row"><span class="sign-label">Date:</span> {{ $hodDate }}</div>
            </div>
            <!-- HR Representative / Wages Officer Card -->
            <div class="signature-card">
                <strong>👥 HR REPRESENTATIVE (WAGES)</strong>
                <div class="sign-row"><span class="sign-label">Name:</span> {{ $wagesOfficer->name }}</div>
                <div class="sign-row"><span class="sign-label">Signature:</span> 
                    <img src="{{ asset('dist/signatures/'.$wagesOfficer->signature) }}" class="sign-image" alt="signature" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend','<span class=\'sign-placeholder\'>Electronically Signed</span>')">
                </div>
                <div class="sign-row"><span class="sign-label">Date:</span> {{ $wagesDate }}</div>
            </div>
            <!-- Budget Officer Card -->
            <div class="signature-card">
                <strong>📊 BUDGET OFFICER</strong>
                <div class="sign-row"><span class="sign-label">Name:</span> {{ $budgetOfficer->name }}</div>
                <div class="sign-row"><span class="sign-label">Signature:</span> 
                    <img src="{{ asset('dist/signatures/'.$budgetOfficer->signature) }}" class="sign-image" alt="signature" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend','<span class=\'sign-placeholder\'>Electronically Signed</span>')">
                </div>
                <div class="sign-row"><span class="sign-label">Date:</span> {{ $budgetDate }}</div>
            </div>
            <!-- HR Manager Card -->
            <div class="signature-card">
                <strong>⭐ HR MANAGER</strong>
                <div class="sign-row"><span class="sign-label">Name:</span> {{ $hrManager->name }}</div>
                <div class="sign-row"><span class="sign-label">Signature:</span> 
                    <img src="{{ asset('dist/signatures/'.$hrManager->signature) }}" class="sign-image" alt="signature" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend','<span class=\'sign-placeholder\'>Electronically Signed</span>')">
                </div>
                <div class="sign-row"><span class="sign-label">Date:</span> {{ $hrDate }}</div>
            </div>
        </div>
        <!-- additional stamp / confirmation line -->
        <div style="margin-top: 20px; font-size: 10px; text-align: center; color: #4a627a; border-top: 1px solid #e2edf2; padding-top: 12px;">
            This document is electronically generated and requires authorized approval. <br>
            Valid upon signature of all parties above.
        </div>
    </div>
    <!-- end signature section -->
</div>
</body>
</html>