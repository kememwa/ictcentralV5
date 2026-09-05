<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Daily Casuals Approval Sheet | Kim-Fay</title>

    <style>
        /* =========================================================
        PAGE SETUP
        ========================================================= */

        @page {
            size: A4 portrait;
            margin: 20mm 18mm 15mm 18mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5px;
            line-height: 1.45;
            color: #000000;
        }


        /* =========================================================
        MAIN APPROVAL PAGE
        ========================================================= */

        .page {
            width: 100%;
            position: relative;
        }


        /* =========================================================
        LETTERHEAD
        ========================================================= */

        .letterhead {
            width: 100%;
            height: 32mm;
            margin-bottom: 7mm;
        }

        .letterhead img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
        }


        /* =========================================================
        DOCUMENT TITLE
        ========================================================= */

        .document-title {
            text-align: center;
            margin-top: 4mm;
            margin-bottom: 7mm;
        }

        .document-title .main-title {
            font-size: 11px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .document-title .version {
            font-size: 10px;
            font-weight: bold;
        }


        /* =========================================================
        REFERENCE
        ========================================================= */

        .reference {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 7mm;
        }


        /* =========================================================
        SECTION HEADINGS
        ========================================================= */

        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 0;
            margin-bottom: 5mm;
        }


        /* =========================================================
        ENGAGEMENT DETAILS
        ========================================================= */

        .engagement-wrapper {
            width: 100%;
            margin-bottom: 9mm;
        }

        .engagement-table {
            width: 100%;
            border-collapse: collapse;
        }

        .engagement-table td {
            vertical-align: top;
            padding: 0;
        }

        .engagement-details {
            width: 68%;
            padding-right: 8mm !important;
        }

        .budget-section {
            width: 32%;
            text-align: center;
            vertical-align: middle !important;
        }

        .detail-line {
            margin-bottom: 1px;
        }

        .detail-label {
            font-weight: bold;
        }

        .reason {
            line-height: 1.5;
            text-align: left;
        }

        .budget-stamp {
            width: 39mm;
            height: 39mm;
            object-fit: contain;
            display: inline-block;
        }


        /* =========================================================
        WAGES
        ========================================================= */

        .wages {
            margin-top: 4mm;
            margin-bottom: 10mm;
        }

        .wage-line {
            margin-bottom: 2px;
        }


        /* =========================================================
        CASUAL APPROVAL TABLE
        ========================================================= */

        .casual-section {
            margin-top: 4mm;
        }

        .casual-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8px;
        }

        .casual-table th,
        .casual-table td {
            border: 1px solid #000000;
            padding: 4px 3px;
            vertical-align: middle;
        }

        .casual-table th {
            font-weight: bold;
            text-align: left;
            white-space: nowrap;
        }

        .casual-table td {
            height: 22px;
        }

        .casual-table th:nth-child(1),
        .casual-table td:nth-child(1) {
            width: 4%;
            text-align: center;
        }

        .casual-table th:nth-child(2),
        .casual-table td:nth-child(2) {
            width: 19%;
        }

        .casual-table th:nth-child(3),
        .casual-table td:nth-child(3) {
            width: 9%;
        }

        .casual-table th:nth-child(4),
        .casual-table td:nth-child(4) {
            width: 16%;
        }

        .casual-table th:nth-child(5),
        .casual-table td:nth-child(5) {
            width: 13%;
        }

        .casual-table th:nth-child(6),
        .casual-table td:nth-child(6) {
            width: 8%;
        }

        .casual-table th:nth-child(7),
        .casual-table td:nth-child(7) {
            width: 15%;
        }

        .casual-table th:nth-child(8),
        .casual-table td:nth-child(8) {
            width: 16%;
        }


        /* =========================================================
        APPROVALS
        ========================================================= */

        .approvals {
            margin-top: 9mm;
        }

        .approval-line {
            width: 100%;
            margin-bottom: 6mm;
            white-space: nowrap;
            font-size: 10px;
        }

        .approval-role {
            font-weight: bold;
        }

        .approval-name {
            display: inline-block;
            width: 32mm;
        }

        .approval-sign-label {
            font-weight: bold;
        }

        .signature {
            display: inline-block;
            width: 18mm;
            height: 9mm;
            vertical-align: middle;
            object-fit: contain;
            margin-left: 2mm;
            margin-right: 2mm;
        }

        .date-label {
            font-weight: bold;
        }


        /* =========================================================
        APPROVAL PAGE FOOTER
        ========================================================= */

        .page-number {
            position: fixed;
            bottom: 5mm;
            right: 0;
            font-size: 9px;
        }


        /* =========================================================
        EMPLOYMENT CONTRACT PAGE
        ========================================================= */

        .contract-page {
            width: 100%;
            position: relative;

            /*
            * IMPORTANT:
            * This is the ONLY forced page break.
            */
            page-break-before: always;
        }


        /* =========================================================
        CONTRACT LETTERHEAD
        ========================================================= */

        .contract-letterhead {
            width: 100%;
            height: 32mm;
            margin-bottom: 6mm;
        }

        .contract-letterhead img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
        }


        /* =========================================================
        CONTRACT TITLE
        ========================================================= */

        .contract-title {
            text-align: center;
            margin-top: 3mm;
            margin-bottom: 7mm;
        }

        .contract-title .main-title {
            font-size: 11px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 4px;
        }

        .contract-title .version {
            font-size: 10px;
            font-weight: bold;
        }


        /* =========================================================
        CONTRACT SECTIONS
        ========================================================= */

        .contract-section {
            margin-top: 5mm;
            margin-bottom: 3mm;

            /*
            * Prevent a section from being split where possible.
            */
            page-break-inside: avoid;
        }

        .contract-section-title {
            font-size: 10px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 3mm;
        }

        .contract-text {
            text-align: justify;
            line-height: 1.5;
            margin-bottom: 4mm;
        }

        .contract-indent {
            padding-left: 7mm;
            text-align: justify;
            line-height: 1.5;
        }

        .contract-item {
            margin-bottom: 3mm;
            text-align: justify;
        }


        /* =========================================================
        CONTRACT SIGNATURE
        ========================================================= */

        .contract-signature {
            margin-top: 5mm;
            line-height: 1.7;
        }


        /* =========================================================
        CONTRACT CASUAL TABLE
        ========================================================= */

        .contract-casual-section {
            margin-top: 8mm;
        }

        .contract-casual-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 7.5px;
        }

        .contract-casual-table th,
        .contract-casual-table td {
            border: 1px solid #000000;
            padding: 4px 3px;
            vertical-align: middle;
        }

        .contract-casual-table th {
            font-weight: bold;
            text-align: center;
        }

        .contract-casual-table td {
            height: 24px;
        }

        .contract-casual-table th:nth-child(1),
        .contract-casual-table td:nth-child(1) {
            width: 4%;
            text-align: center;
        }

        .contract-casual-table th:nth-child(2),
        .contract-casual-table td:nth-child(2) {
            width: 19%;
        }

        .contract-casual-table th:nth-child(3),
        .contract-casual-table td:nth-child(3) {
            width: 10%;
        }

        .contract-casual-table th:nth-child(4),
        .contract-casual-table td:nth-child(4) {
            width: 14%;
        }

        .contract-casual-table th:nth-child(5),
        .contract-casual-table td:nth-child(5) {
            width: 13%;
        }

        .contract-casual-table th:nth-child(6),
        .contract-casual-table td:nth-child(6) {
            width: 9%;
        }

        .contract-casual-table th:nth-child(7),
        .contract-casual-table td:nth-child(7) {
            width: 15%;
        }

        .contract-casual-table th:nth-child(8),
        .contract-casual-table td:nth-child(8) {
            width: 16%;
        }

        .contract-casual-table tr {
            page-break-inside: avoid;
        }


        /* =========================================================
        CONTRACT FOOTER
        ========================================================= */

        .contract-footer-page {
        position: fixed;
        bottom: 5mm;
        left: 0;
        right: 0;
        width: 100%;
        text-align: right;
        font-size: 9px;
        height: 5mm;
        }


        /* =========================================================
        PRINT / DOMPDF
        ========================================================= */

        @media print {

            /*
            * Do NOT force a break after .page.
            *
            * The contract-page handles the single break.
            */
            .page {
                page-break-after: auto;
                page-break-inside: auto;
            }

            /*
            * This is the ONLY forced page break.
            */
            .contract-page {
                page-break-before: always;
                page-break-after: auto;
            }

            /*
            * Keep the approval table together.
            */
            .casual-table {
                page-break-inside: avoid;
            }

            /*
            * Keep approval signatures together.
            */
            .approvals {
                page-break-inside: avoid;
            }

            /*
            * Keep contract sections together where possible.
            */
            .contract-section {
                page-break-inside: avoid;
            }

            /*
            * Allow the contract table to flow naturally
            * if it becomes too long.
            */
            .contract-casual-table {
                page-break-inside: auto;
            }

            .contract-casual-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>

<div class="page">

    {{-- =========================================================
         LETTERHEAD
         ========================================================= --}}
    <div class="letterhead">
        <img
            src="{{ public_path('images/letterhead.png') }}"
            alt="Kim-Fay Letterhead"
        >
    </div>


    {{-- =========================================================
         TITLE
         ========================================================= --}}
    <div class="document-title">

        <div class="main-title">
            DAILY CASUALS APPROVAL SHEET
        </div>

        <div class="version">
            HR Record - Version 1
        </div>

    </div>


    {{-- =========================================================
         REFERENCE
         ========================================================= --}}
    <div class="reference">
        Reference Token : 800804
    </div>


    {{-- =========================================================
         ENGAGEMENT DETAILS
         ========================================================= --}}
    <div class="section-title">
        ENGAGEMENT DETAILS
    </div>

    <div class="engagement-wrapper">

        <table class="engagement-table">

            <tr>

                <td class="engagement-details">

                    <div class="detail-line">
                        <span class="detail-label">Department :</span>
                        Kimfay Professional
                    </div>

                    <div class="detail-line">
                        <span class="detail-label">Division :</span>
                        Default
                    </div>

                    <div class="detail-line">
                        <span class="detail-label">Applicant :</span>
                        Berna Piwang
                    </div>

                    <div class="detail-line">
                        <span class="detail-label">Start Date :</span>
                        2026-08-10
                    </div>

                    <div class="detail-line">
                        <span class="detail-label">End Date :</span>
                        2026-08-15
                    </div>

                    <div class="detail-line">
                        <span class="detail-label">Duration of Engagement :</span>
                        4
                    </div>

                    <div class="detail-line reason">
                        <span class="detail-label">Reason for Engagement :</span>
                        Installation completion requested to relieve technician Shadrach
                        following an accident and sick leave period, towards pending
                        installation works with crucial client needs - Gardaworld,
                        Vertiv, Industrial solutions ltd, Biafra hospital,
                        Treasure communication, Aurum Iris Ltd
                    </div>

                </td>


                <td class="budget-section">

                    <img
                        src="{{ public_path('images/budgeted_circle.png') }}"
                        alt="BUDGETED"
                        class="budget-stamp"
                    >

                </td>

            </tr>

        </table>

    </div>


    {{-- =========================================================
         WAGES DETAILS
         ========================================================= --}}
    <div class="wages">

        <div class="section-title">
            WAGES DETAILS
        </div>

        <div class="wage-line">
            <span class="detail-label">Daily Payment Rate :</span>
            Ksh. 3,000.00
        </div>

        <div class="wage-line">
            <span class="detail-label">No of Casuals :</span>
            1
        </div>

        <div class="wage-line">
            <span class="detail-label">Total Deductions :</span>
            Ksh. 780.00
        </div>

        <div class="wage-line">
            <span class="detail-label">Total Amount Payable :</span>
            Ksh. 11,220.00
        </div>

    </div>


    {{-- =========================================================
         CASUAL DETAILS
         ========================================================= --}}
    <div class="casual-section">

        <div class="section-title">
            CASUALS DETAILS
        </div>

        <table class="casual-table">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Casual Name</th>
                    <th>I.D No.</th>
                    <th>NHIF No.</th>
                    <th>NSSF No.</th>
                    <th>KRA Pin</th>
                    <th>Casual Staff Sign</th>
                    <th>Paying officer Name/Sign</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>1</td>
                    <td>Moses Okoth Odhiambo</td>
                    <td>36384941</td>
                    <td>CR3044103386906-1</td>
                    <td>2032510376</td>
                    <td>0</td>
                    <td></td>
                    <td></td>
                </tr>

            </tbody>

        </table>

    </div>


    {{-- =========================================================
         APPROVALS
         ========================================================= --}}
    <div class="approvals">

        <div class="section-title">
            APPROVALS
        </div>


        {{-- HOD --}}
        <div class="approval-line">

            <span class="approval-role">
                Head of Department :
            </span>

            <span class="approval-name">
                Susan Ngina
            </span>

            <span class="approval-sign-label">
                Sign :
            </span>

            <span class="signature"></span>

            <span class="date-label">
                Date :
            </span>

        </div>


        {{-- HR REPRESENTATIVE --}}
        <div class="approval-line">

            <span class="approval-role">
                H.R Representative :
            </span>

            <span class="approval-name">
                Althea Marie
            </span>

            <span class="approval-sign-label">
                Sign :
            </span>

            <span class="signature"></span>

            <span class="date-label">
                Date :
            </span>

            Tuesday 1st of September 2026 12:27:57 PM

        </div>


        {{-- BUDGET OFFICER --}}
        <div class="approval-line">

            <span class="approval-role">
                Budget Officer :
            </span>

            <span class="approval-name">
                REUBEN GITHINJI
            </span>

            <span class="approval-sign-label">
                Sign :
            </span>

            <img
                src="{{ public_path('dist/signatures/budget_signature.png') }}"
                class="signature"
                alt="Signature"
            >

            <span class="date-label">
                Date :
            </span>

            Thursday 3rd of September 2026 07:37:41 AM

        </div>


        {{-- HR MANAGER --}}
        <div class="approval-line">

            <span class="approval-role">
                H.R Manager :
            </span>

            <span class="approval-name">
                Alice Mworia
            </span>

            <span class="approval-sign-label">
                Sign :
            </span>

            <img
                src="{{ public_path('dist/signatures/hr_manager_sign.png') }}"
                class="signature"
                alt="Signature"
            >

            <span class="date-label">
                Date :
            </span>

            Thursday 3rd of September 2026 07:38:12 AM

        </div>

    </div>


    {{-- =========================================================
         PAGE NUMBER
         ========================================================= --}}
    <div class="page-number">
        1/1
    </div>

</div>

<div class="contract-page">

    <!-- =========================
         LETTERHEAD
    ========================== -->
    <div class="letterhead">
        <img src="{{ public_path('images/letterhead.png') }}" alt="Kim-Fay Letterhead">
    </div>


    <!-- =========================
         DOCUMENT TITLE
    ========================== -->
    <div class="document-title">
        Casual Employment Contract
    </div>

    <div class="document-version">
        HR Record - Version 1
    </div>


    <!-- =========================
         CONTRACT INFORMATION
    ========================== -->
    <table class="contract-info">
        <tr>
            <td class="label">Contract Date:</td>
            <td>5th September 2026</td>
        </tr>

        <tr>
            <td class="label">Company:</td>
            <td>Kim-Fay (E.A) Limited</td>
        </tr>

        <tr>
            <td class="label">Employee:</td>
            <td>Moses Okoth Odhiambo</td>
        </tr>

        <tr>
            <td class="label">Employee ID:</td>
            <td>36384941</td>
        </tr>

        <tr>
            <td class="label">Engagement Period:</td>
            <td>10th August 2026 to 15th August 2026</td>
        </tr>
    </table>


    <!-- =========================
         1. PARTIES
    ========================== -->
    <div class="section">

        <div class="section-title">
            1. Parties
        </div>

        <p>
            This Casual Employment Contract is made between
            <strong>Kim-Fay (E.A) Limited</strong>, hereinafter referred to as
            "the Company", and <strong>Moses Okoth Odhiambo</strong>,
            hereinafter referred to as "the Employee".
        </p>

        <p>
            The Employee agrees to provide casual services to the Company
            under the terms and conditions set out in this contract.
        </p>

    </div>


    <!-- =========================
         2. NATURE OF EMPLOYMENT
    ========================== -->
    <div class="section">

        <div class="section-title">
            2. Nature of Employment
        </div>

        <p>
            The Employee is engaged as a casual employee for the period
            specified in this contract. This engagement does not constitute
            permanent employment and shall be subject to the Company's
            policies, procedures and applicable employment laws.
        </p>

    </div>


    <!-- =========================
         3. PERIOD OF EMPLOYMENT
    ========================== -->
    <div class="section">

        <div class="section-title">
            3. Period of Employment
        </div>

        <table class="details-table">

            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>No. of Days</th>
            </tr>

            <tr>
                <td>10th August 2026</td>
                <td>15th August 2026</td>
                <td>4 Days</td>
            </tr>

        </table>

        <p>
            The engagement shall automatically terminate upon expiry of the
            agreed period unless otherwise extended in writing by the Company.
        </p>

    </div>


    <!-- =========================
         4. NATURE OF DUTIES
    ========================== -->
    <div class="section">

        <div class="section-title">
            4. Nature of Duties
        </div>

        <p>
            The Employee shall perform duties assigned by the Company,
            including general operational support, loading and offloading
            of goods, cleaning and organization of work areas, movement of
            materials and any other reasonable duties assigned by the
            supervisor.
        </p>

        <p>
            The Employee shall carry out all duties diligently, responsibly
            and in accordance with the instructions of the Company.
        </p>

    </div>


    <!-- =========================
         5. REMUNERATION
    ========================== -->
    <div class="section">

        <div class="section-title">
            5. Remuneration
        </div>

        <p>
            The Employee shall be paid a daily casual wage of
            <strong>KES 3,000.00</strong>.
        </p>

        <table class="details-table">

            <tr>
                <th>Description</th>
                <th class="amount">Amount (KES)</th>
            </tr>

            <tr>
                <td>Daily Rate</td>
                <td class="amount">3,000.00</td>
            </tr>

            <tr>
                <td>Number of Days</td>
                <td class="amount">4</td>
            </tr>

            <tr>
                <td class="bold">Gross Pay</td>
                <td class="amount bold">12,000.00</td>
            </tr>

            <tr>
                <td>NSSF</td>
                <td class="amount">216.00</td>
            </tr>

            <tr>
                <td>NHIF / SHIF</td>
                <td class="amount">564.00</td>
            </tr>

            <tr>
                <td>PAYE</td>
                <td class="amount">0.00</td>
            </tr>

            <tr>
                <td class="bold">Total Deductions</td>
                <td class="amount bold">780.00</td>
            </tr>

            <tr>
                <td class="bold">Net Pay</td>
                <td class="amount bold">11,220.00</td>
            </tr>

        </table>

    </div>


    <!-- =========================
         6. UNIFORMS & PROTECTIVE GEAR
    ========================== -->
    <div class="section">

        <div class="section-title">
            6. Uniforms and Protective Gear
        </div>

        <p>
            Where applicable, the Company shall provide the Employee with
            the necessary protective clothing and equipment required for
            the performance of assigned duties.
        </p>

        <p>
            The Employee shall take reasonable care of all Company-issued
            uniforms, protective equipment and other property and shall
            return them upon completion of the engagement.
        </p>

    </div>


    <!-- =========================
         7. CODE OF CONDUCT
    ========================== -->
    <div class="section">

        <div class="section-title">
            7. Code of Conduct
        </div>

        <p>
            The Employee shall comply with all Company rules, regulations,
            health and safety requirements, security procedures and lawful
            instructions issued by the Company or its representatives.
        </p>

        <p>
            Any misconduct, negligence, dishonesty, insubordination or
            violation of Company policies may result in termination of
            the engagement.
        </p>

    </div>


    <!-- =========================
         8. TERMINATION
    ========================== -->
    <div class="section">

        <div class="section-title">
            8. Termination
        </div>

        <p>
            The Company may terminate this engagement where the Employee
            fails to comply with the terms of this contract, Company
            policies or lawful instructions.
        </p>

        <p>
            The engagement shall also terminate automatically upon expiry
            of the agreed employment period.
        </p>

    </div>


    <!-- =========================
         9. TERMINATION ON INCAPACITY
    ========================== -->
    <div class="section">

        <div class="section-title">
            9. Termination on Incapacity
        </div>

        <p>
            Where the Employee becomes unable to perform the assigned duties
            due to incapacity, the Company shall handle the matter in
            accordance with applicable employment legislation and Company
            policy.
        </p>

    </div>


    <!-- =========================
         10. RESTRICTIONS AFTER TERMINATION
    ========================== -->
    <div class="section">

        <div class="section-title">
            10. Restrictions After Termination
        </div>

        <p>
            Upon completion or termination of the engagement, the Employee
            shall immediately return all Company property, documents,
            equipment, uniforms, identification cards and any other
            materials belonging to the Company.
        </p>

        <p>
            The Employee shall continue to maintain confidentiality regarding
            Company information obtained during the period of employment.
        </p>

    </div>


    <!-- =========================
         11. EMPLOYEE DETAILS
    ========================== -->
    <div class="section">

        <div class="section-title">
            11. Employee Details
        </div>

        <table class="employee-table">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>ID Number</th>
                    <th>NHIF / SHIF Number</th>
                    <th>NSSF Number</th>
                    <th>KRA PIN</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Moses Okoth Odhiambo</td>
                    <td>36384941</td>
                    <td>CR3044103386906-1</td>
                    <td>2032510376</td>
                    <td>A012345678P</td>
                </tr>

            </tbody>

        </table>

    </div>


    <!-- =========================
         12. SIGNED
    ========================== -->
    <div class="section">

        <div class="section-title">
            12. Signed
        </div>

        <p>
            By signing below, the parties confirm that they have read,
            understood and agreed to the terms and conditions contained
            in this Casual Employment Contract.
        </p>


        <table class="signature-table">

            <tr>

                <td>

                    <div class="signature-line"></div>

                    <div class="signature-label">
                        Employee Signature
                    </div>

                    <div>
                        Name: Moses Okoth Odhiambo
                    </div>

                    <div>
                        Date: 5th September 2026
                    </div>

                </td>


                <td>

                    <div class="signature-line"></div>

                    <div class="signature-label">
                        For and on behalf of Kim-Fay (E.A) Limited
                    </div>

                    <div>
                        Name: Alice Mworia
                    </div>

                    <div>
                        Designation: HR and Administration Manager
                    </div>

                    <div>
                        Date: 5th September 2026
                    </div>

                </td>

            </tr>

        </table>

    </div>


    <!-- =========================
         FOOTER
    ========================== -->
    <div class="contract-footer-page">
        Kim-Fay (E.A) Limited &nbsp; | &nbsp;
        Casual Employment Contract &nbsp; | &nbsp;
        HR Record - Version 1
    </div>

</div>

</body>
</html>