<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F1F5F9;
            margin: 0;
            padding: 40px 15px;
            color: #1E293B;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            background: #FFFFFF;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #E2E8F0;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #F1F5F9;
            padding-bottom: 24px;
            margin-bottom: 30px;
        }
        .site-logo {
            font-size: 24px;
            font-weight: 900;
            color: #2563EB;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 6px 0;
            text-align: right;
        }
        .invoice-meta {
            text-align: right;
            font-size: 13px;
            color: #64748B;
        }
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }
        .info-col {
            flex: 1;
        }
        .info-col h4 {
            font-size: 12px;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 8px 0;
        }
        .info-col p {
            margin: 0 0 4px 0;
            font-size: 14px;
            color: #334155;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #F8FAFC;
            color: #475569;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
        }
        td {
            padding: 16px;
            font-size: 14px;
            border-bottom: 1px solid #F1F5F9;
            color: #1E293B;
        }
        .total-box {
            margin-left: auto;
            width: 300px;
            margin-bottom: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            color: #64748B;
        }
        .total-row.grand {
            font-size: 18px;
            font-weight: 800;
            color: #0F172A;
            border-top: 2px solid #E2E8F0;
            padding-top: 12px;
            margin-top: 6px;
        }
        .paid-stamp {
            display: inline-block;
            border: 3px solid #10B981;
            color: #10B981;
            font-weight: 900;
            font-size: 20px;
            text-transform: uppercase;
            padding: 8px 24px;
            border-radius: 8px;
            transform: rotate(-8deg);
        }
        .print-btn {
            background: #2563EB;
            color: #FFFFFF;
            border: none;
            padding: 10px 24px;
            font-weight: 700;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .print-btn:hover { background: #1D4ED8; }
        @media print {
            body { background: #FFFFFF; padding: 0; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    
    {{-- Print Button --}}
    <div class="no-print" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <a href="javascript:window.history.back()" style="color: #64748B; font-size: 13px; text-decoration: none; font-weight: 600;">&larr; Back</a>
        <button onclick="window.print()" class="print-btn">
            Print / Save as PDF
        </button>
    </div>

    {{-- Header --}}
    <div class="invoice-header">
        <div>
            <div class="site-logo">{{ $siteSetting->site_name ?? 'JOB PORTAL' }}</div>
            <div style="font-size: 13px; color: #64748B; margin-top: 6px;">
                {{ $siteSetting->mail_to_address ?? 'support@jobportal.com' }}<br>
                India
            </div>
        </div>
        <div>
            <h1 class="invoice-title">TAX INVOICE</h1>
            <div class="invoice-meta">
                <strong>Invoice #:</strong> INV-{{ date('Y', strtotime($order->created_at)) }}-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Order #:</strong> {{ $order->order_number }}<br>
                <strong>Date:</strong> {{ date('d M Y, h:i A', strtotime($order->created_at)) }}
            </div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="info-grid">
        <div class="info-col">
            <h4>Billed To:</h4>
            <p><strong>{{ $order->buyer_name }}</strong></p>
            <p>{{ $order->buyer_email }}</p>
            @if($order->buyer_phone && $order->buyer_phone != 'N/A')
                <p>{{ $order->buyer_phone }}</p>
            @endif
        </div>
        <div class="info-col" style="text-align: right;">
            <h4>Payment Info:</h4>
            <p><strong>Gateway:</strong> {{ strtoupper($order->gateway) }}</p>
            <p><strong>Payment ID:</strong> {{ $payment->gateway_payment_id ?? 'N/A' }}</p>
            <p><strong>Method:</strong> {{ strtoupper($payment->payment_method ?? 'ONLINE / UPI') }}</p>
            <p><strong>Payment Status:</strong> <span style="color: #10B981; font-weight: 700;">{{ strtoupper($order->status) }}</span></p>
        </div>
    </div>

    {{-- Line Items Table --}}
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Type</th>
                <th>Validity</th>
                <th>Listings Quota</th>
                <th style="text-align: right;">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $order->package_title }} Package</strong>
                </td>
                <td>{{ ucfirst($order->package_type) }}</td>
                <td>{{ $order->package->package_num_days ?? '30' }} Days</td>
                <td>{{ $order->package->package_num_listings ?? '10' }} Jobs</td>
                <td style="text-align: right; font-weight: 700;">₹{{ number_format($order->package_price, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Totals --}}
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            @if($order->status === 'paid')
                <div class="paid-stamp">PAID</div>
            @endif
        </div>
        <div class="total-box">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₹{{ number_format($order->package_price, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row" style="color: #10B981;">
                <span>Discount:</span>
                <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            @if($order->tax_amount > 0)
            <div class="total-row">
                <span>Tax / GST:</span>
                <span>+₹{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand">
                <span>Total Paid:</span>
                <span style="color: #2563EB;">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Footer Terms --}}
    <div style="border-top: 1px solid #E2E8F0; padding-top: 20px; margin-top: 40px; font-size: 12px; color: #94A3B8; text-align: center;">
        This is a computer-generated invoice and requires no physical signature.<br>
        Thank you for choosing {{ $siteSetting->site_name ?? 'Job Portal' }}!
    </div>

</div>

</body>
</html>
