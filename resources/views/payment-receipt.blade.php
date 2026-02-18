<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            line-height: 1.3;
            padding: 0;
            margin: 0;
            background: #fff;
        }

        .receipt-container {
            width: 100%;
            padding: 10px;
            background: #fff;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .receipt-header h1 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 3px;
            letter-spacing: 1px;
        }

        .receipt-header .school-info {
            font-size: 7pt;
            margin: 2px 0;
            line-height: 1.2;
        }

        .receipt-title {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 8px 0;
            padding: 5px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .meta-section {
            margin: 8px 0;
            font-size: 8pt;
        }

        .meta-row {
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
            word-wrap: break-word;
        }

        .meta-label {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }

        .meta-value {
            display: block;
            padding-left: 5px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 6.5pt;
            table-layout: fixed;
        }

        .receipt-table th {
            background: #000;
            color: #fff;
            padding: 3px 1px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 6.5pt;
        }

        .receipt-table th:nth-child(1),
        .receipt-table td:nth-child(1) {
            width: 8%;
            text-align: center;
            
        }

        .receipt-table th:nth-child(2),
        .receipt-table td:nth-child(2) {
            width: 32%;
              text-align: center;
        }

        .receipt-table th:nth-child(3),
        .receipt-table td:nth-child(3) {
            width: 30%;
            text-align: center;
        }

        .receipt-table th:nth-child(4),
        .receipt-table td:nth-child(4) {
            width: 30%;
              text-align: center;
        }

        .receipt-table th.text-right,
        .receipt-table td.text-right {
            text-align: right;
        }

        .receipt-table td {
            padding: 3px 1px;
            border-bottom: 1px dotted #ccc;
            font-size: 6.5pt;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .receipt-table tr:last-child td {
            border-bottom: 1px solid #000;
        }

        .empty-message {
            text-align: center;
            padding: 10px;
            font-style: italic;
            color: #666;
            font-size: 7pt;
        }

        .receipt-footer {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed #000;
            font-size: 6pt;
            text-align: center;
        }

        .footer-note {
            margin: 3px 0;
            font-style: italic;
            line-height: 1.3;
        }

        .signature-section {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px dotted #000;
        }

        .signature-box {
            margin: 8px 0;
            text-align: left;
            font-size: 7pt;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin-top: 20px;
            padding-bottom: 2px;
            font-size: 7pt;
            display: inline-block;
            width: 60%;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>{{ $schoolName }}</h1>
            @if($schoolAddress)
                <div class="school-info">{{ $schoolAddress }}</div>
            @endif
            @if($schoolPhone)
                <div class="school-info">Tel: {{ $schoolPhone }}</div>
            @endif
            @if($schoolEmail)
                <div class="school-info">Email: {{ $schoolEmail }}</div>
            @endif
        </div>

        <div class="receipt-title">PAYMENT RECEIPT LOG</div>

        <div class="meta-section">
            <div class="meta-row">
                <span class="meta-label">STUDENT</span>
                <span class="meta-value">{{ $studentName }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">INVOICE</span>
                <span class="meta-value">{{ $invoiceName }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">CATEGORY</span>
                <span class="meta-value">{{ $categoryName }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">REFERENCE</span>
                <span class="meta-value">{{ $reference }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>#</th>
                  
                    <th class="text-center">PAID</th>
                    <th class="text-center">BAL</th>
                    <th class="text-center">DATE</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $index => $receipt)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-center">{{ number_format($receipt->amount_paid, 0) }}</td>
                        <td class="text-center">{{ number_format($receipt->balance, 0) }}</td>
                         <td class="text-center">{{ $receipt->issued_at->format('d/m/y') }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-message">NO RECEIPTS</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="meta-section">
            <div class="meta-row">
                <span class="meta-label">TOTAL PAID</span>
                <span class="meta-value">NGN {{ number_format($totalPaid, 0) }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">BALANCE</span>
                <span class="meta-value">NGN {{ number_format($currentBalance, 0) }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">STATUS</span>
                <span class="meta-value">{{ $isPaid ? 'PAID' : 'UNPAID' }}</span>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                Accountant: <span class="signature-line"></span>
            </div>
            <div class="signature-box">
                Date: <span class="signature-line"></span>
            </div>
        </div>

        <div class="receipt-footer">
            <div class="footer-note">OFFICIAL PAYMENT RECEIPT</div>
            <div class="footer-note">{{ now()->format('d/m/Y H:i') }}</div>
            @if($schoolMotto)
                <div class="footer-note" style="margin-top: 5px; font-weight: bold;">{{ $schoolMotto }}</div>
            @endif
            <div class="footer-note" style="margin-top: 5px;">* * * THANK YOU * * *</div>
        </div>
    </div>
</body>
</html>

