{{-- =====================================================================
     KIM-FAY | DAILY CASUAL RECRUITMENT PACK  (prints 3 documents, in order)

       1. Casual Request to Recruit      (1 page)
       2. Daily Casuals Approval Sheet   (1 page)
       3. Casual Employment Contract     (2 pages)

     USAGE (DomPDF):
       use Barryvdh\DomPDF\Facade\Pdf;

       return Pdf::loadView('hr.casual-print', ['c' => $data])
                 ->setPaper('a4', 'portrait')
                 ->stream('casual-recruitment-pack.pdf');

     $data is optional – anything you leave out falls back to the sample
     values in the DATA section below.
====================================================================== --}}

@php
    /* ------------------------------------------------------------------
     | 1. DATA
     ------------------------------------------------------------------ */
    $c = array_merge([

        // Casual Request to Recruit
        'department'            => 'Kimfay Professional',
        'department_head'       => 'Susan Ngina',
        'coo'                   => 'James Mwangi',
        'number_required'       => 1,
        'reason_for_request'    => 'Additional casual staff are required to support pending installation and operational activities during the engagement period and to ensure timely completion of assigned work.',

        // Shared
        'days'                  => 4,
        'start_date'            => '10th August 2026',
        'end_date'              => '15th August 2026',

        // Daily Casuals Approval Sheet
        'reference_token'       => '800804',
        'division'              => 'Technical Services',
        'applicant'             => 'Berna Piwang',
        'reason_for_engagement' => 'Installation completion requested to support pending works and ensure timely completion of critical client assignments including Gardaworld, Vertiv, Industrial Solutions Ltd, Biafra Hospital, Treasure Communication and Aurum Iris Ltd.',
        'budgeted'              => true,

        // Wages (per casual)
        'daily_rate'            => 3000,
        'nssf'                  => 216,
        'sha'                   => 564,

        // Casuals  (name, id_no, sha_no, nssf_no, tel)
        'casuals' => [
            [
                'name'    => 'Moses Okoth Odhiambo',
                'id_no'   => '36384941',
                'sha_no'  => 'CR3044103386906-1',
                'nssf_no' => '2032510376',
                'tel'     => '0712 345 678',
            ],
        ],

        // Approvals  (signature = path relative to /public, or null)
        'hod_name'       => 'Susan Ngina',
        'hod_signature'  => null,
        'hod_date'       => null,

        'hr_name'        => 'Althea Marie',
        'hr_signature'   => null,
        'hr_date'        => null,

        'hopc_name'      => 'Alice Mworia',
        'hopc_signature' => 'dist/signatures/hr_manager_sign.png',
        'hopc_date'      => '3rd September 2026',

        // Contract
        'contract_date'  => '5th September 2026',

    ], $c ?? []);

    /* ------------------------------------------------------------------
     | 2. ASSETS
     |    $logo must be the Kim-Fay logo ONLY (no address text) – the
     |    address block is drawn by this template.
     ------------------------------------------------------------------ */
    $logo     = public_path('images/kimfay.png');
    $stamp    = public_path('images/budgeted_circle.png');

    // returns an absolute path only when the file really exists
    $sig = function ($path) {
        return ($path && file_exists(public_path($path))) ? public_path($path) : null;
    };
    $hodSig  = $sig($c['hod_signature']);
    $hrSig   = $sig($c['hr_signature']);
    $hopcSig = $sig($c['hopc_signature']);

    /* ------------------------------------------------------------------
     | 3. LAYOUT FLAGS & MONEY
     ------------------------------------------------------------------ */
    $count       = max(count($c['casuals']), 1);

    // The casual list normally sits at the bottom of contract page 2.
    // With more than 2 casuals it moves to its own page 3 so nothing overflows.
    $listOnPage2 = count($c['casuals']) <= 2;
    $listOnPage3 = ! $listOnPage2;
    $listPage    = $listOnPage2 ? 2 : 3;

    $showStamp   = $c['budgeted'] && file_exists($stamp);

    $grossEach = $c['daily_rate'] * $c['days'];
    $dedEach   = $c['nssf'] + $c['sha'];

    $rate      = number_format($c['daily_rate'], 2);
    $gross     = number_format($grossEach, 2);
    $nssf      = number_format($c['nssf'], 2);
    $sha       = number_format($c['sha'], 2);
    $dedEachF  = number_format($dedEach, 2);
    $totalDed  = number_format($dedEach * $count, 2);
    $totalPay  = number_format(($grossEach - $dedEach) * $count, 2);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Casual Recruitment Documents | Kim-Fay</title>

    <style>
        /* =============================================================
           PAGE
           A4, 12.7mm (0.5") margins. Documents 1 & 2 add 12.7mm of side
           padding to give the 1" margins used in the Word originals.
        ============================================================= */
        @page {
            size: A4 portrait;
            margin: 12.7mm 12.7mm 10mm 12.7mm;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000000;
        }

        table {
            border-collapse: collapse;
        }

        td, th {
            padding: 0;
            vertical-align: top;
        }

        img {
            border: 0;
        }

        .new-page {
            page-break-before: always;
        }

        .b  { font-weight: bold; }
        .u  { text-decoration: underline; }


        /* =============================================================
           DOCUMENT 1 – CASUAL REQUEST TO RECRUIT
        ============================================================= */
        .req {
            padding: 4mm 12.7mm 0 12.7mm;
        }

        .req-logo {
            text-align: center;
        }

        .req-logo img {
            width: 46mm;
        }

        .req-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 1mm;
        }

        .req-version {
            text-align: center;
            font-size: 8pt;
            font-style: italic;
            margin-top: 1mm;
            margin-bottom: 11mm;
        }

        .req-section {
            margin-bottom: 10mm;
        }

        .req-heading {
            font-weight: bold;
            margin-bottom: 0.5mm;
        }

        /* label + a ruled line that runs to the right margin
           (separate borders so every ruled line is always drawn) */
        .field {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .field td {
            height: 8mm;
            vertical-align: bottom;
        }

        .field .fl {
            width: 1%;
            white-space: nowrap;
            padding: 0 1mm 0.8mm 0;
        }

        .field .fv {
            border-bottom: 1px solid #000000;
            padding: 0 1mm 0.8mm 1mm;
        }

        /* signature rows */
        .sign-row {
            width: 100%;
            margin-bottom: 2mm;
            border-collapse: separate;
            border-spacing: 0;
        }

        .sign-row td {
            height: 9mm;
            vertical-align: bottom;
        }

        .sign-row .sl {
            width: 1%;
            white-space: nowrap;
            padding: 0 1mm 0.8mm 0;
        }

        .sign-row .sline {
            border-bottom: 1px solid #000000;
        }

        .sign-row .dl {
            width: 1%;
            white-space: nowrap;
            padding: 0 1mm 0.8mm 2mm;
        }

        .sign-row .dline {
            width: 34%;
            border-bottom: 1px solid #000000;
            padding: 0 1mm 0.8mm 1mm;
        }


        /* =============================================================
           DOCUMENT 2 – DAILY CASUALS APPROVAL SHEET
        ============================================================= */
        .appr {
            padding: 0 12.7mm;
        }

        /* letterhead box: logo | address | contact  (visible grey borders) */
        .hb {
            width: 100%;
            border: 1px solid #a5a5a5;
        }

        .hb td {
            width: 33.33%;
            border: 1px solid #a5a5a5;
            padding: 2mm 2mm 3mm 2mm;
            font-size: 8pt;
            line-height: 1.35;
        }

        .hb img {
            width: 42mm;
        }

        .appr-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 7mm;
        }

        .appr-version {
            text-align: center;
            font-size: 10pt;
            margin-top: 0.5mm;
            margin-bottom: 2.5mm;
        }

        /* the light-grey frames that wrap the text blocks */
        .frame {
            border: 1px solid #d9d9d9;
            padding: 1mm 1.5mm 2mm 1.5mm;
            page-break-inside: avoid;
        }

        .ref {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 4mm;
        }

        .a-head {
            font-size: 10pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 3.5mm;
        }

        .eng {
            width: 100%;
        }

        .eng .eng-left {
            width: 70%;
        }

        .eng .eng-right {
            width: 30%;
            text-align: center;
            vertical-align: middle;
        }

        .eng .eng-right img {
            width: 36mm;
            height: 36mm;
        }

        .dl-line {
            font-weight: bold;
            margin-bottom: 0.6mm;
        }

        .dl-line span {
            font-weight: normal;
        }

        .reason {
            min-height: 10mm;
            margin-bottom: 3mm;
        }

        .wages {
            margin-bottom: 3mm;
        }

        .sec-gap {
            margin-top: 4mm;
        }

        /* casual details table – solid black grid */
        .ct {
            width: 100%;
            table-layout: fixed;
            margin-top: 1.5mm;
            margin-bottom: 4mm;
        }

        .ct th,
        .ct td {
            border: 1px solid #000000;
            padding: 1.5mm 1.2mm;
            font-size: 8.5pt;
            line-height: 1.25;
        }

        .ct th {
            font-weight: bold;
            text-align: left;
        }

        .ct td {
            height: 7.5mm;
            vertical-align: middle;
        }

        .ct .c-no   { width: 6%; }
        .ct .c-name { width: 19%; }
        .ct .c-id   { width: 11%; }
        .ct .c-sha  { width: 22%; }
        .ct .c-nssf { width: 12%; }
        .ct .c-tel  { width: 15%; }
        .ct .c-sign { width: 15%; }

        /* approvals */
        .ap {
            width: 100%;
            margin-bottom: 3mm;
        }

        .ap td {
            height: 9.5mm;
            vertical-align: middle;
            font-size: 9pt;
        }

        .ap .ap-role { width: 46%; }
        .ap .ap-sign { width: 28%; }
        .ap .ap-date { width: 26%; }

        .ap img.sig {
            height: 9mm;
            vertical-align: middle;
            margin-left: 1mm;
        }


        /* =============================================================
           DOCUMENT 3 – CASUAL EMPLOYMENT CONTRACT  (2 pages)
        ============================================================= */
        /*
         * .c-page is a fixed-height box so that the footer sits at the
         * bottom of each of the two contract pages.
         */
        .c-page {
            height: 254mm;
            font-size: 8.5pt;
            line-height: 1.3;
        }

        .c-hdr {
            width: 100%;
        }

        .c-hdr td {
            font-size: 8pt;
            line-height: 1.35;
        }

        .c-hdr .h-logo { width: 42%; }
        .c-hdr .h-addr { width: 27%; }
        .c-hdr .h-cont { width: 31%; }

        .c-hdr img {
            width: 34mm;
        }

        .c-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 9pt;
            margin-top: 0.5mm;
            margin-bottom: 2mm;
        }

        .c-h {
            font-weight: bold;
            text-decoration: underline;
            margin: 3mm 0 2mm 0;
        }

        .c-p {
            text-align: justify;
            margin: 0 0 2mm 0;
        }

        .c-list {
            margin: 0 0 2mm 0;
            padding-left: 8mm;
            text-align: justify;
        }

        .c-list li {
            margin-bottom: 0.4mm;
        }

        .c-sub {
            margin: 0.4mm 0 0.4mm 0;
        }

        .c-sub td {
            font-size: 8.5pt;
            line-height: 1.3;
            text-align: justify;
        }

        .c-sub .sn {
            width: 9mm;
        }

        .c-foot {
            width: 100%;
            height: 8mm;
        }

        .c-foot td {
            vertical-align: bottom;
        }

        .c-foot .f-left {
            font-size: 9pt;
            font-style: italic;
        }

        .c-foot .f-right {
            width: 15mm;
            text-align: right;
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
        }

        /* annexture block */
        .c-sig {
            margin-top: 1mm;
        }

        .c-sig td {
            font-size: 8.5pt;
            height: 6mm;
            vertical-align: middle;
        }

        .c-sig .s-label { width: 27mm; }
        .c-sig .s-value { width: 62mm; font-weight: bold; }
        .c-sig .s-sign  { width: 14mm; font-weight: bold; }

        .c-sig img.sig {
            height: 9mm;
            vertical-align: middle;
        }

        /* contract casual details table */
        .c-ct {
            width: 100%;
            table-layout: fixed;
            margin-top: 1.5mm;
        }

        .c-ct th,
        .c-ct td {
            border: 1px solid #000000;
            padding: 1.5mm 1.2mm;
            font-size: 8.5pt;
            line-height: 1.25;
        }

        .c-ct th {
            font-weight: bold;
            text-align: left;
        }

        .c-ct td {
            height: 8mm;
            vertical-align: middle;
        }

        .c-ct .k-no   { width: 7%; }
        .c-ct .k-name { width: 21%; }
        .c-ct .k-id   { width: 11%; }
        .c-ct .k-sha  { width: 22%; }
        .c-ct .k-nssf { width: 12%; }
        .c-ct .k-tel  { width: 13%; }
        .c-ct .k-sign { width: 14%; }

        /* paying officer */
        .pay {
            width: 100%;
            margin-top: 3mm;
            border-collapse: separate;
            border-spacing: 0;
        }

        .pay td {
            height: 8mm;
            vertical-align: bottom;
            font-weight: bold;
        }

        .pay .p-label {
            width: 1%;
            white-space: nowrap;
            padding-right: 1.5mm;
            padding-bottom: 0.8mm;
        }

        .pay .p-line {
            border-bottom: 1px solid #000000;
        }

        .pay .p-gap {
            width: 3mm;
        }

        .pay .p-date {
            width: 1%;
            white-space: nowrap;
            padding: 0 1.5mm 0.8mm 3mm;
        }

        .pay .p-dline {
            width: 40%;
            border-bottom: 1px solid #000000;
        }
    </style>
</head>

<body>


{{-- =====================================================================
     DOCUMENT 1 : CASUAL REQUEST TO RECRUIT
====================================================================== --}}
<div class="req">

    <div class="req-logo">
        <img src="{{ $logo }}" alt="Kim-Fay">
    </div>

    <div class="req-title">CASUAL REQUEST TO RECRUIT</div>
    <div class="req-version">HR Record - Version 1</div>


    <div class="req-section">
        <div class="req-heading">General Information</div>

        <table class="field">
            <tr>
                <td class="fl">Department:</td>
                <td class="fv">{{ $c['department'] }}</td>
            </tr>
        </table>

        <table class="field">
            <tr>
                <td class="fl">Department Head:</td>
                <td class="fv">{{ $c['department_head'] }}</td>
            </tr>
        </table>
    </div>


    <div class="req-section">
        <div class="req-heading">Position Information</div>

        <table class="field">
            <tr>
                <td class="fl">Number required:</td>
                <td class="fv">{{ $c['number_required'] }}</td>
            </tr>
        </table>

        <table class="field">
            <tr>
                <td class="fl">Reason for request:</td>
                <td class="fv">{{ $c['reason_for_request'] }}</td>
            </tr>
            <tr>
                <td colspan="2" class="fv">&nbsp;</td>
            </tr>
        </table>

        <table class="field">
            <tr>
                <td class="fl">Number of days:</td>
                <td class="fv">{{ $c['days'] }}</td>
            </tr>
        </table>

        <table class="field">
            <tr>
                <td class="fl">Start date:</td>
                <td class="fv">{{ $c['start_date'] }}</td>
            </tr>
        </table>
    </div>


    <div class="req-section">
        <div class="req-heading">Approval signatures</div>

        <table class="sign-row">
            <tr>
                <td class="sl">Department Head: {{ $c['department_head'] }}</td>
                <td class="sline">&nbsp;</td>
                <td class="dl">Date:</td>
                <td class="dline">&nbsp;</td>
            </tr>
        </table>

        <table class="sign-row">
            <tr>
                <td class="sl">Chief Operations Officer: {{ $c['coo'] }}</td>
                <td class="sline">&nbsp;</td>
                <td class="dl">Date:</td>
                <td class="dline">&nbsp;</td>
            </tr>
        </table>
    </div>

</div>



{{-- =====================================================================
     DOCUMENT 2 : DAILY CASUALS APPROVAL SHEET
====================================================================== --}}
<div class="appr new-page">

    {{-- LETTERHEAD BOX --}}
    <table class="hb">
        <tr>
            <td>
                <img src="{{ $logo }}" alt="Kim-Fay">
            </td>
            <td>
                Kim-Fay E.A Ltd<br>
                Maasai Road, Off Mombasa Road<br>
                Behind Libra House<br>
                Box 31437-00600, Nairobi
            </td>
            <td>
                T/+254 20351824/19/24<br>
                F/ +254 20 6531458, 6533518<br>
                E/ <u>customercare&#64;kimfay.com</u><br>
                <br>
                www.kimfay.com
            </td>
        </tr>
    </table>

    <div class="appr-title">DAILY CASUALS APPROVAL SHEET</div>
    <div class="appr-version">HR Record &ndash; Version 2</div>


    {{-- FRAME 1 : reference, engagement, wages --}}
    <div class="frame">

        <div class="ref">Reference Token: <span style="font-weight: normal;">{{ $c['reference_token'] }}</span></div>

        <div class="a-head">ENGAGEMENT DETAILS</div>

        <table class="eng">
            <tr>
                <td class="eng-left">
                    <div class="dl-line">Department: <span>{{ $c['department'] }}</span></div>
                    <div class="dl-line">Division: <span>{{ $c['division'] }}</span></div>
                    <div class="dl-line">Applicant: <span>{{ $c['applicant'] }}</span></div>
                    <div class="dl-line">Start Date: <span>{{ $c['start_date'] }}</span></div>
                    <div class="dl-line">End Date: <span>{{ $c['end_date'] }}</span></div>
                    <div class="dl-line">Duration of Engagement (Days): <span>{{ $c['days'] }}</span></div>
                    <div class="dl-line reason">Reason for Engagement: <span>{{ $c['reason_for_engagement'] }}</span></div>
                </td>
                <td class="eng-right">
                    @if($showStamp)
                        <img src="{{ $stamp }}" alt="BUDGETED">
                    @endif
                </td>
            </tr>
        </table>

        <div class="a-head sec-gap">WAGES DETAILS</div>

        <div class="wages">
            <div class="dl-line">Daily Payment Rate: <span>Ksh. {{ $rate }}</span></div>
            <div class="dl-line">No. of Casuals: <span>{{ $count }}</span></div>
            <div class="dl-line">Total Deductions: <span>Ksh. {{ $totalDed }}</span></div>
            <div class="dl-line">Total Amount Payable: <span>Ksh. {{ $totalPay }}</span></div>
        </div>

    </div>


    {{-- CASUAL DETAILS (black grid) --}}
    <div class="a-head sec-gap" style="margin-bottom: 0;">CASUAL DETAILS</div>

    <table class="ct">
        <thead>
            <tr>
                <th class="c-no">No.</th>
                <th class="c-name">Casual Name</th>
                <th class="c-id">ID No.</th>
                <th class="c-sha">SHA No.</th>
                <th class="c-nssf">NSSF No.</th>
                <th class="c-tel">Tel. No</th>
                <th class="c-sign">Casual Sign</th>
            </tr>
        </thead>
        <tbody>
            @foreach($c['casuals'] as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['id_no'] }}</td>
                    <td>{{ $row['sha_no'] }}</td>
                    <td>{{ $row['nssf_no'] }}</td>
                    <td>{{ $row['tel'] }}</td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    {{-- FRAME 2 : approvals --}}
    <div class="frame">

        <div class="a-head" style="margin-top: 1mm; margin-bottom: 3mm;">APPROVALS</div>

        <table class="ap">
            <tr>
                <td class="ap-role"><span class="b">Head of Department:</span> {{ $c['hod_name'] }}</td>
                <td class="ap-sign">
                    <span class="b">Sign:</span>
                    @if($hodSig)<img class="sig" src="{{ $hodSig }}" alt="Signature">@endif
                </td>
                <td class="ap-date"><span class="b">Date:</span> {{ $c['hod_date'] }}</td>
            </tr>
        </table>

        <table class="ap">
            <tr>
                <td class="ap-role"><span class="b">HR Representative:</span> {{ $c['hr_name'] }}</td>
                <td class="ap-sign">
                    <span class="b">Sign:</span>
                    @if($hrSig)<img class="sig" src="{{ $hrSig }}" alt="Signature">@endif
                </td>
                <td class="ap-date"><span class="b">Date:</span> {{ $c['hr_date'] }}</td>
            </tr>
        </table>

        <table class="ap">
            <tr>
                <td class="ap-role"><span class="b">Head of People &amp; Culture:</span> {{ $c['hopc_name'] }}</td>
                <td class="ap-sign">
                    <span class="b">Sign:</span>
                    @if($hopcSig)<img class="sig" src="{{ $hopcSig }}" alt="Signature">@endif
                </td>
                <td class="ap-date"><span class="b">Date:</span> {{ $c['hopc_date'] }}</td>
            </tr>
        </table>

    </div>

</div>



{{-- =====================================================================
     DOCUMENT 3 : CASUAL EMPLOYMENT CONTRACT  –  PAGE 1 OF 2
====================================================================== --}}
<div class="c-page new-page">

    <table class="c-hdr">
        <tr>
            <td class="h-logo"><img src="{{ $logo }}" alt="Kim-Fay"></td>
            <td class="h-addr">
                Kim-Fay E.A Ltd<br>
                Maasai Road, Off Mombasa Road<br>
                Behind Libra House<br>
                Box 31437-00600, Nairobi
            </td>
            <td class="h-cont">
                T/+254 20351824/19/24<br>
                F/ +254 20 6531458, 6533518<br>
                E/ <u>customercare&#64;kimfay.com</u><br>
                www.kimfay.com
            </td>
        </tr>
    </table>

    <div class="c-title">CASUAL EMPLOYMENT CONTRACT</div>


    <div class="c-h" style="margin-top: 4mm;">PARTIES</div>

    <p class="c-p">
        <span class="b">THIS AGREEMENT</span> is made on the
        <span class="b">{{ $c['contract_date'] }}</span>
        <span class="b">BETWEEN; Kim-Fay (E.A) Ltd</span>
        (hereinafter called &ldquo;the Company&rdquo;)
        <span class="b">AND (See listed Employees on Page {{ $listPage }})</span>
        (hereinafter called &ldquo;the employee&rdquo;)
    </p>

    <p class="c-p" style="margin-top: 4mm;">
        <span class="b">WHEREAS</span> the company has offered the employee a contract of employment on a temporary
        basis the employee hereby confirms his/her acceptance of the offer of the employment contract under the
        following terms and conditions:
    </p>


    <div class="c-h">NATURE OF EMPLOYMENT</div>

    <ul class="c-list">
        <li>The employee hereby represents and warrants as material warranty to the Company that:</li>
        <li>He/ She has full power to enter into and perform in terms of this Agreement, has taken and shall take all
            necessary statutory and other actions to authorize the fulfillment of his/her obligations under this
            Agreement;</li>
        <li>That he/she has obtained a Certificate of Good Conduct as evidence of his non involvement of criminal
            activities whether in the past or currently;</li>
        <li>That he/she has provided all the educational certificates as requested by the Company;</li>
        <li>That he/she is of good health and able to take up his training and assignment.</li>
        <li>That he/she fully understands the nature of her assignment and risks involved and the Company has
            explained this to him and undertakes his employment knowingly, willingly and voluntarily.</li>
    </ul>


    <div class="c-h">PERIOD OF EMPLOYMENT</div>

    <p class="c-p">
        The employee shall be engaged for a period of
        <span class="b">{{ $c['days'] }} days</span> as from
        <span class="b">{{ $c['start_date'] }}</span> to
        <span class="b">{{ $c['end_date'] }}.</span>
    </p>

    <p class="c-p">
        Terminating thereof without notice BUT SUBJECT always to the provisions as to earlier termination as set out
        hereinafter. The Company shall not be obliged to employ the employee after the lapse of the period contracted
        as per this agreement. Should the employee be offered a further period of employment by the company at the
        expiry of the signed contract, then it shall be on the condition that such other period of employment shall
        be subject to the terms and conditions of a fresh and separate agreement and shall not in any way be regarded
        as relating to or as an extension of the old contract. The decision whether or not to sign a new agreement as
        mentioned in (4.3) hereinabove shall be entirely upon the discretion of the management.
    </p>


    <div class="c-h">NATURE OF DUTIES</div>

    <p class="c-p" style="margin-bottom: 1.5mm;">The employee shall: -</p>

    <ul class="c-list">
        <li>Undertake to perform such duties and exercise such powers as the company assigns to the employee.</li>
        <li>Undertake his/her duties in any station or branch of the company within the Republic of Kenya, depending
            on work availability, as may be required of him/her from time to time.</li>
        <li>Work for a maximum period of twelve hours daily depending on shift allocations and as required by the
            company either;

            <table class="c-sub">
                <tr>
                    <td class="sn">i.</td>
                    <td>Reporting at 8:30a.m and handing over at 5:00 p.m. or</td>
                </tr>
                <tr>
                    <td class="sn">ii.</td>
                    <td>Reporting at ____________ p.m. and handing over at ____________ a.m.</td>
                </tr>
                <tr>
                    <td class="sn">iii.</td>
                    <td>The hours of reporting and handing over may be altered by the company without notice to the
                        employee.</td>
                </tr>
            </table>
        </li>
        <li>Be entitled to one day of rest in every seven (7) consecutive days of duty.</li>
    </ul>


    <div class="c-h">REMUNERATION</div>

    <p class="c-p">
        The company shall pay to the employee a sum of <span class="b">Kshs. {{ $gross }}</span>
    </p>

    <p class="c-p">
        This payment will be paid at the end of the period of contract, being the accumulated daily wages of the
        aggregate days worked only.
        A further deduction of <span class="b">Kshs. {{ $nssf }}</span> and
        <span class="b">Kshs. {{ $sha }}</span> shall be made from your aggregate accumulated wages at the end of
        this contract and shall be remitted to <span class="b">NSSF</span> and <span class="b">SHA</span>
        respectively as is our statutory obligation. Therefore, total deductions shall amount to
        <span class="b">Kshs. {{ $dedEachF }}</span>
    </p>

    <p class="c-p">
        Payment of the employee wages as hereinabove shall be deemed as settlement of all his/her claims from the
        company for the period payable to her/him then and/or in the future.
    </p>

</div>

<table class="c-foot">
    <tr>
        <td class="f-left">HR Record - Version 2</td>
        <td class="f-right">1</td>
    </tr>
</table>



{{-- =====================================================================
     DOCUMENT 3 : CASUAL EMPLOYMENT CONTRACT  –  PAGE 2 OF 2
====================================================================== --}}
<div class="c-page new-page">

    <table class="c-hdr">
        <tr>
            <td class="h-logo"><img src="{{ $logo }}" alt="Kim-Fay"></td>
            <td class="h-addr">
                Kim-Fay E.A Ltd<br>
                Maasai Road, Off Mombasa Road<br>
                Behind Libra House<br>
                Box 31437-00600, Nairobi
            </td>
            <td class="h-cont">
                T/+254 20351824/19/24<br>
                F/ +254 20 6531458, 6533518<br>
                E/ <u>customercare&#64;kimfay.com</u><br>
                www.kimfay.com
            </td>
        </tr>
    </table>

    <div class="c-title">CASUAL EMPLOYMENT CONTRACT</div>


    <div class="c-h" style="margin-top: 4mm;">UNIFORMS AND PROTECTIVE GEAR:</div>

    <p class="c-p">
        The Company shall provide the employee with the uniform and equipment required by the employee for the
        performance of his duties.
    </p>

    <p class="c-p">
        The employee shall be deducted Kshs. 500/= from his/her wages for any damage to or loss of the equipment or
        uniform as a result by the neglect of the employee.
    </p>


    <div class="c-h">CODE OF CONDUCT:</div>

    <p class="c-p">
        The employee shall observe and comply with all regulations and conditions of the company and other standing
        orders and procedures given in the course of employment for the purpose of the efficient and competent
        discharge of his or her duties.
    </p>

    <p class="c-p">
        Non-compliance with the instructions and regulations thereby may lead to the immediate termination of this
        contract. The employee shall devote the whole of his time and attention during duties to the performance of
        his duties and shall not engage in any business or occupation directly or indirectly which may in the opinion
        of the management hinder or otherwise in any way detract from the satisfactory performance of his/her duties
        under this agreement.
    </p>

    <p class="c-p">
        The Work Injury Benefits Act, 2007 and/or any other relevant statute shall apply strictly in relation to
        injuries sustained by the employee in the course of duty.
    </p>

    <p class="c-p">
        The company shall be entitled to claim from the employee indemnity incase of losses and/or damages suffered
        by the company owing to the employee&rsquo;s negligence either wholly or partly, in the discharge of his
        duties or otherwise.
    </p>

    <p class="c-p">
        The employee shall while in the employment of the company and reasonably thereafter treat with utmost
        confidentiality all the company&rsquo;s matter not authorized to be disclosed to the public or any other
        persons.
    </p>


    <div class="c-h">TERMINATION:</div>

    <p class="c-p" style="margin-bottom: 1.5mm;">
        The contract shall terminate at the expiry of the period hereinabove specified. However, and in addition;
    </p>

    <ul class="c-list">
        <li>The employee is entitled to terminate the contract by giving to the Company one days written notice or
            one day&rsquo;s wages in lieu of notice.</li>
        <li>The company has authority to summarily terminate the contract without any notice or compensation
            whatsoever to the employee in the event of gross negligence, absenteeism, drunkenness part and/or
            suspicion of or commission of a criminal offence of any kind on the part of the employee.</li>
        <li>The employee&rsquo;s contract with the company is tied to the company&rsquo;s contracts with third
            parties; the employee&rsquo;s contract may thereof terminate automatically without notice on termination
            of the company&rsquo;s contract with third parties.</li>
    </ul>


    <div class="c-h">RESTRICTIONS AFTER TERMINATION:</div>

    <p class="c-p">
        After termination of this contract, you shall not seek to entice away from the company any of its customers
        nor use for your own benefit or to the possible detriment of the company any information concerning the
        company&rsquo;s business affairs, customers&rsquo; secrets which you may have acquired in the course of your
        employment under this contract or as incidental thereto.
    </p>


    <div class="c-h">ANNEXTURES</div>

    <table class="c-sig">
        <tr>
            <td class="s-label">For:</td>
            <td class="s-value" colspan="3">Kim-Fay (E.A) Ltd</td>
        </tr>
        <tr>
            <td class="s-label">Signed:</td>
            <td class="s-value">{{ $c['hr_name'] }}</td>
            <td class="s-sign">Sign:</td>
            <td>
                @if($hrSig)<img class="sig" src="{{ $hrSig }}" alt="Signature">@endif
            </td>
        </tr>
        <tr>
            <td class="s-label">Name:</td>
            <td class="s-value" colspan="3">{{ $c['hopc_name'] }}</td>
        </tr>
        <tr>
            <td class="s-label">Designation:</td>
            <td class="s-value" colspan="3">Head of People &amp; Culture</td>
        </tr>
    </table>


    @if($listOnPage2)

    <div class="c-h" style="margin-bottom: 0;">CASUAL DETAILS</div>

    <table class="c-ct">
        <thead>
            <tr>
                <th class="k-no">NO.</th>
                <th class="k-name">Casual Name</th>
                <th class="k-id">ID No.</th>
                <th class="k-sha">SHA No.</th>
                <th class="k-nssf">NSSF No.</th>
                <th class="k-tel">Tel No.</th>
                <th class="k-sign">Casual Sign</th>
            </tr>
        </thead>
        <tbody>
            @foreach($c['casuals'] as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['id_no'] }}</td>
                    <td>{{ $row['sha_no'] }}</td>
                    <td>{{ $row['nssf_no'] }}</td>
                    <td>{{ $row['tel'] }}</td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="pay">
        <tr>
            <td class="p-label">Paying Officer Name:</td>
            <td class="p-line" colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td class="p-label">Sign:</td>
            <td class="p-line">&nbsp;</td>
            <td class="p-date">Date:</td>
            <td class="p-dline" colspan="2">&nbsp;</td>
        </tr>
    </table>

    @endif

</div>

<table class="c-foot">
    <tr>
        <td class="f-left">HR Record - Version 2</td>
        <td class="f-right">2</td>
    </tr>
</table>



{{-- =====================================================================
     DOCUMENT 3 : CASUAL EMPLOYMENT CONTRACT  -  PAGE 3 OF 3
     (only printed when there are more than 2 casuals)
====================================================================== --}}
@if($listOnPage3)
<div class="c-page new-page">

    <table class="c-hdr">
        <tr>
            <td class="h-logo"><img src="{{ $logo }}" alt="Kim-Fay"></td>
            <td class="h-addr">
                Kim-Fay E.A Ltd<br>
                Maasai Road, Off Mombasa Road<br>
                Behind Libra House<br>
                Box 31437-00600, Nairobi
            </td>
            <td class="h-cont">
                T/+254 20351824/19/24<br>
                F/ +254 20 6531458, 6533518<br>
                E/ <u>customercare&#64;kimfay.com</u><br>
                www.kimfay.com
            </td>
        </tr>
    </table>

    <div class="c-title">CASUAL EMPLOYMENT CONTRACT</div>

    <div class="c-h" style="margin-top: 6mm; margin-bottom: 0;">CASUAL DETAILS</div>

    <table class="c-ct">
        <thead>
            <tr>
                <th class="k-no">NO.</th>
                <th class="k-name">Casual Name</th>
                <th class="k-id">ID No.</th>
                <th class="k-sha">SHA No.</th>
                <th class="k-nssf">NSSF No.</th>
                <th class="k-tel">Tel No.</th>
                <th class="k-sign">Casual Sign</th>
            </tr>
        </thead>
        <tbody>
            @foreach($c['casuals'] as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['id_no'] }}</td>
                    <td>{{ $row['sha_no'] }}</td>
                    <td>{{ $row['nssf_no'] }}</td>
                    <td>{{ $row['tel'] }}</td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="pay">
        <tr>
            <td class="p-label">Paying Officer Name:</td>
            <td class="p-line" colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td class="p-label">Sign:</td>
            <td class="p-line">&nbsp;</td>
            <td class="p-date">Date:</td>
            <td class="p-dline" colspan="2">&nbsp;</td>
        </tr>
    </table>

</div>

<table class="c-foot">
    <tr>
        <td class="f-left">HR Record - Version 2</td>
        <td class="f-right">3</td>
    </tr>
</table>
@endif

</body>
</html>