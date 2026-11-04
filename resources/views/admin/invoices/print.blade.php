<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة ضريبية - {{ $invoice->invoice_number }}</title>
    
    <!-- Saudi Riyal Font -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@emran-alhaddad/saudi-riyal-font/index.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', Arial, sans-serif;
            line-height: 1.3;
            color: #000000;
            background: #ffffff;
            font-size: 12px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 10px;
            background: white;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 2px solid #000000;
            padding-bottom: 10px;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-logo {
            width: 100px;
            height: 100px;
            margin-bottom: 8px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .company-name {
            font-size: 18px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 4px;
        }
        
        .company-details {
            color: #000000;
            line-height: 1.4;
            font-size: 11px;
        }
        
        .invoice-title {
            text-align: center;
            flex: 1;
        }
        
        .invoice-title h1 {
            font-size: 16px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 6px;
        }
        
        .invoice-number {
            font-size: 14px;
            color: #000000;
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
            border: 1px solid #000000;
        }
        
        .qr-section {
            text-align: center;
            flex: 0 0 120px;
        }
        
        .qr-code {
            width: 110px;
            height: 110px;
            border: 1px solid #000000;
            border-radius: 6px;
            margin: 0 auto;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000000;
            font-size: 9px;
            overflow: hidden;
        }
        
        .qr-code canvas {
            width: 100% !important;
            height: 100% !important;
        }
        
        .qr-code img {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain;
        }
        
        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .meta-section {
            background: #f8f8f8;
            padding: 8px;
            border-radius: 8px;
            border-right: 3px solid #000000;
            border: 1px solid #000000;
        }
        
        .meta-section h3 {
            font-size: 12px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .meta-section p {
            margin-bottom: 4px;
            color: #000000;
            font-size: 11px;
        }
        
        .meta-section .label {
            font-weight: 600;
            color: #000000;
            display: inline-block;
            min-width: 80px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border: 1px solid #000000;
        }
        
        .items-table thead {
            background: #000000;
            color: white;
        }
        
        .items-table th {
            padding: 6px 3px;
            text-align: center;
            font-weight: 600;
            font-size: 9px;
            border: 1px solid #000000;
        }
        
        .items-table tbody tr {
            border-bottom: 1px solid #000000;
        }
        
        .items-table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .items-table td {
            padding: 6px 3px;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            border: 1px solid #000000;
        }
        
        .item-details {
            text-align: right;
        }
        
        .item-name {
            font-weight: 600;
            color: #000000;
            margin-bottom: 2px;
            font-size: 11px;
        }
        
        .item-description {
            color: #000000;
            font-size: 10px;
        }
        
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        
        .totals-table {
            width: 300px;
            background: #f8f8f8;
            border-radius: 8px;
            padding: 8px;
            border: 1px solid #000000;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3px 0;
            border-bottom: 1px solid #000000;
            font-size: 10px;
        }
        
        .totals-row:last-child {
            border-bottom: none;
            background: #000000;
            color: white;
            padding: 8px;
            margin: 6px -12px -12px;
            border-radius: 0 0 8px 8px;
            font-weight: 700;
            font-size: 13px;
        }
        
        .payment-info {
            background: #f0f0f0;
            border: 1px solid #000000;
            border-radius: 8px;
            padding: 8px;
            margin-bottom: 10px;
        }
        
        .payment-info h3 {
            color: #000000;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
        }
        
        .notes-section {
            background: #f8f8f8;
            border: 1px solid #000000;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .notes-section h3 {
            color: #000000;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .footer {
            text-align: center;
            color: #000000;
            font-size: 10px;
            border-top: 1px solid #000000;
            padding-top: 8px;
            margin-top: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #000000;
        }
        
        .status-paid {
            background: #f0f0f0;
            color: #000000;
        }
        
        .status-unpaid {
            background: #f0f0f0;
            color: #000000;
        }
        
        .status-partially-paid {
            background: #f0f0f0;
            color: #000000;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-size: 11px;
                color: #000000 !important;
            }
            
            .company-logo {
                width: 90px;
                height: 90px;
                margin-bottom: 6px;
            }
            
            #barcode {
                width: 100% !important;
                height: 100% !important;
            }
            
            .qr-code canvas {
                width: 100% !important;
                height: 100% !important;
            }
            
            .qr-code img {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain;
            }
            
            .invoice-container {
                padding: 10px;
                box-shadow: none;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
            
            .invoice-meta {
                gap: 8px;
                margin-bottom: 15px;
            }
            
            .meta-section {
                padding: 8px;
                border: 1px solid #000000 !important;
            }
            
            .items-table th {
                padding: 4px 2px;
                font-size: 9px;
                border: 1px solid #000000 !important;
            }
            
            .items-table td {
                padding: 4px 2px;
                font-size: 10px;
                border: 1px solid #000000 !important;
            }
            
            .totals-section {
                display: flex !important;
                gap: 10px !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
            }
            
            .totals-table {
                flex: 1 !important;
                border: 1px solid #000000 !important;
            }
            
            .invoice-header {
                border-bottom: 2px solid #000000 !important;
            }
            
            .company-name, .company-details, .invoice-title h1, .invoice-number {
                color: #000000 !important;
            }
            
            .meta-section h3, .meta-section p, .meta-section .label {
                color: #000000 !important;
            }
            
            .item-name, .item-description {
                color: #000000 !important;
            }
            
            .payment-info h3, .notes-section h3 {
                color: #000000 !important;
            }
            
            .footer {
                color: #000000 !important;
                border-top: 1px solid #000000 !important;
            }
        }
        
        .amount {
            font-weight: 600;
            color: #000000;
        }
        
        .currency {
            color: #000000;
            font-size: 12px;
        }
        
        .highlight {
            background: #000000;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <img src="{{ url('/logo1.php') }}" alt="{{ config('brand.name') }}" class="company-logo">
                <div class="company-name">{{ config('brand.name') }}</div>
                <div class="company-details">
                    الرياض، المملكة العربية السعودية<br>
                    <strong>الرقم الضريبي:</strong> 310329522000003<br>
                    <strong>رقم السجل التجاري:</strong> 1010566459
                </div>
            </div>
            
            <div class="invoice-title">
                <h1>فاتورة ضريبية - Tax Invoice</h1>
                <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            </div>
            
            <div class="qr-section">
                <div class="qr-code">
                    <canvas id="barcode" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="invoice-meta">
            <!-- Customer Name -->
            <div class="meta-section">
                <h3>👤 اسم العميل</h3>
                <p><span class="label">الاسم:</span><br>{{ $invoice->customer_name }}</p>
            </div>
            
            <!-- Customer Phone -->
            <div class="meta-section">
                <h3>📱 رقم الجوال</h3>
                <p><span class="label">الجوال:</span><br>{{ $invoice->customer_phone }}</p>
            </div>
            
            <!-- Customer Address -->
            <div class="meta-section">
                <h3>📍 العنوان</h3>
                @if($invoice->customer_address)
                <p><span class="label">العنوان:</span><br>{{ $invoice->customer_address }}</p>
                @else
                <p><span class="label">العنوان:</span><br>الموقع المحدد على الخريطة</p>
                @endif
            </div>
        </div>

        <!-- Booking Info (if exists) -->
        @if($invoice->booking)
        <div class="meta-section" style="margin-bottom: 10px;">
            <h3>📋 معلومات الحجز المرتبط</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                <p><span class="label">رقم الحجز:</span><br>{{ $invoice->booking->booking_number }}</p>
                <p><span class="label">تاريخ الحجز:</span><br>{{ $invoice->booking->booking_date->format('Y/m/d') }}</p>
                <p><span class="label">وقت الحجز:</span><br>{{ $invoice->booking->booking_time }} - {{ date('H:i', strtotime($invoice->booking->booking_time) + 7200) }}</p>
            </div>
        </div>
        @endif

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>تفاصيل المنتج<br>Product Details</th>
                    <th>سعر الوحدة<br>Unit Price</th>
                    <th>الكمية<br>Quantity</th>
                    <th>المجموع الفرعي<br>Subtotal</th>
                    <th>الخصم<br>Discount</th>
                    <th>السعر بعد الخصم<br>Price After Discount</th>
                    <th>قيمة الضريبة<br>Tax Value</th>
                    <th>نسبة الضريبة<br>Tax Rate</th>
                    <th>المجموع شامل الضريبة<br>Total Includes TAX</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $taxRate = $invoice->tax_rate / 100;
                    $priceIncludingTax = $invoice->subtotal;
                    $taxAmount = ($priceIncludingTax * $taxRate) / (1 + $taxRate);
                    $priceBeforeTax = $priceIncludingTax - $taxAmount;
                @endphp
                
                @php
                    // البحث عن العنصر الرئيسي (الخدمة) فقط
                    $mainService = null;
                    foreach($invoice->invoice_items as $item) {
                        if($item['type'] === 'service') {
                            $mainService = $item;
                            break;
                        }
                    }
                    
                    // إذا لم نجد عنصر من نوع service، نأخذ الأول
                    if(!$mainService && count($invoice->invoice_items) > 0) {
                        $mainService = $invoice->invoice_items[0];
                    }
                @endphp
                
                @if($mainService)
                <tr>
                    <td class="item-details">
                        <div class="item-name">{{ $mainService['name'] }}</div>
                        @if(isset($mainService['description']) && $mainService['description'])
                            <div class="item-description">{{ $mainService['description'] }}</div>
                        @endif
                        @if($invoice->booking)
                        <div style="margin-top: 4px; font-size: 9px; color: #666;">
                            {{ $invoice->booking->booking_date->format('l') }} - {{ $invoice->booking->booking_time }}
                        </div>
                        @endif
                    </td>
                    <td>{{ number_format($priceBeforeTax / $mainService['quantity'], 2) }} <span class="icon-saudi_riyal"></span></td>
                    <td>{{ $mainService['quantity'] }}</td>
                    <td>{{ number_format($priceBeforeTax, 2) }} <span class="icon-saudi_riyal"></span></td>
                    <td>{{ number_format($invoice->discount_amount, 2) }} <span class="icon-saudi_riyal"></span></td>
                    <td>{{ number_format($priceBeforeTax - $invoice->discount_amount, 2) }} <span class="icon-saudi_riyal"></span></td>
                    <td>{{ number_format($taxAmount, 2) }} <span class="icon-saudi_riyal"></span></td>
                    <td>{{ number_format($invoice->tax_rate, 1) }}%</td>
                    <td><strong>{{ number_format($invoice->total_amount, 2) }} <span class="icon-saudi_riyal"></span></strong></td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-table" style="width: 100%; max-width: 400px; margin-left: auto;">
                    <div class="totals-row">
                        <span>المجموع الفرعي<br><small>Subtotal</small></span>
                        <span class="amount">{{ number_format($priceBeforeTax, 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                    
                    @if($invoice->discount_amount > 0)
                    <div class="totals-row">
                        <span>الخصم<br><small>Discount</small></span>
                        <span class="amount">{{ number_format($invoice->discount_amount, 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                    @endif
                    
                    <div class="totals-row">
                        <span>المبلغ بدون الضريبة<br><small>Amount without TAX</small></span>
                        <span class="amount">{{ number_format($priceBeforeTax - $invoice->discount_amount, 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                    
                    <div class="totals-row">
                        <span>مجموع الضريبة<br><small>Total Tax</small></span>
                        <span class="amount">{{ number_format($taxAmount, 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                    
                    <div class="totals-row">
                        <span>المبلغ المدفوع<br><small>Paid Amount</small></span>
                        <span class="amount">{{ number_format($invoice->paid_amount, 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                    
                    <div class="totals-row">
                        <span>المجموع الإجمالي<br><small>Total Amount</small></span>
                        <span class="amount" style="color: white;">{{ number_format($invoice->total_amount, 2) }} <span class="icon-saudi_riyal" style="color: white;"></span></span>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        @if($invoice->status === 'paid')
        <div class="payment-info">
            <h3 style="font-size: 11px;">✅ معلومات الدفع</h3>
            <p style="font-size: 10px; margin: 4px 0;"><strong>حالة الدفع:</strong> <span class="status-badge status-paid" style="font-size: 10px;">تم الدفع بنجاح</span></p>
            @if($invoice->payment_date)
                <p style="font-size: 10px; margin: 4px 0;"><strong>تاريخ الدفع:</strong> {{ $invoice->payment_date->format('Y/m/d H:i') }}</p>
            @endif
            @if($invoice->payment_method)
                <p style="font-size: 10px; margin: 4px 0;"><strong>طريقة الدفع:</strong> {{ $invoice->payment_method === 'cash' ? 'نقداً' : ($invoice->payment_method === 'card' ? 'بطاقة ائتمان' : $invoice->payment_method) }}</p>
            @endif
            <div style="margin-top: 6px; text-align: center; background: #f0f0f0; padding: 4px; border-radius: 6px; border: 1px solid #000000; font-size: 9px;">
                تم الدفع بسداد ({{ $invoice->invoice_number }}), طريقة الدفع: {{ $invoice->payment_method === 'cash' ? 'نقطة بيع' : 'PointOfSales' }}
                <br><small style="font-size: 8px;">Paid by receipt ({{ $invoice->invoice_number }}), payment method: PointOfSales</small>
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes-section">
            <h3>📝 ملاحظات</h3>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p style="font-size: 9px;">شكراً لاختياركم خدماتنا - نتطلع لخدمتكم مرة أخرى</p>
            <p style="font-size: 8px;">Thank you for choosing our services - We look forward to serving you again</p>
            <p style="margin-top: 6px; font-size: 12px; line-height: 1.3;">
                أُقر العميل {{ $invoice->customer_name }} بالموافقة على جميع شروط إخلاء المسؤولية المذكورة في موقع {{ config('brand.name') }}، والتي تشمل: الموافقة على إجراءات السلامة للحيوان، إخلاء مسؤولية المتجر عن الحالة الصحية، الالتزام بمواعيد استلام الحيوان، احترام فريق العمل.
            </p>
            <p style="margin-top: 6px; font-size: 7px;">
                تم إنشاء هذه الفاتورة إلكترونياً في {{ now()->format('Y/m/d H:i') }} من نظام إدارة الحجوزات
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
    <script>
        // Function to generate QR Code using QRCode library
        function generateQRCode() {
            const canvas = document.getElementById("barcode");
            const invoiceNumber = "{{ $invoice->invoice_number }}";
            
            QRCode.toCanvas(canvas, invoiceNumber, {
                width: 110,
                height: 110,
                color: {
                    dark: '#000000',
                    light: '#ffffff'
                },
                margin: 1
            }, function (error) {
                if (error) {
                    console.error('QR Code generation error:', error);
                    // Try alternative method
                    generateQRCodeAlternative();
                } else {
                    console.log('QR Code generated successfully');
                }
            });
        }
        
        // Alternative QR Code generation using QRious
        function generateQRCodeAlternative() {
            const canvas = document.getElementById("barcode");
            const invoiceNumber = "{{ $invoice->invoice_number }}";
            
            try {
                const qr = new QRious({
                    element: canvas,
                    value: invoiceNumber,
                    size: 110,
                    background: '#ffffff',
                    foreground: '#000000'
                });
                console.log('QR Code generated successfully with QRious');
            } catch (error) {
                console.error('QRious generation error:', error);
                // Try Google Charts as last resort
                generateQRCodeGoogle();
            }
        }
        
        // Last resort: Google Charts QR Code
        function generateQRCodeGoogle() {
            const canvas = document.getElementById("barcode");
            const invoiceNumber = "{{ $invoice->invoice_number }}";
            
            try {
                const img = document.createElement('img');
                img.src = 'https://chart.googleapis.com/chart?chs=110x110&cht=qr&chl=' + encodeURIComponent(invoiceNumber);
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.display = 'block';
                
                // Clear canvas and add image
                canvas.innerHTML = '';
                canvas.appendChild(img);
                
                console.log('QR Code generated successfully with Google Charts');
            } catch (error) {
                console.error('Google Charts generation error:', error);
            }
        }
        
        // Wait for both page load and QRCode library to be available
        window.onload = function() {
            // Check if QRCode is available, if not wait a bit more
            if (typeof QRCode !== 'undefined') {
                generateQRCode();
                // Print after QR code is generated
                setTimeout(function() {
                    window.print();
                }, 1000);
            } else if (typeof QRious !== 'undefined') {
                // Try QRious if QRCode is not available
                generateQRCodeAlternative();
                setTimeout(function() {
                    window.print();
                }, 1000);
            } else {
                // Try Google Charts immediately
                generateQRCodeGoogle();
                setTimeout(function() {
                    window.print();
                }, 1000);
                
                // Also wait for libraries to load as backup
                setTimeout(function() {
                    if (typeof QRCode !== 'undefined') {
                        generateQRCode();
                        setTimeout(function() {
                            window.print();
                        }, 1000);
                    } else if (typeof QRious !== 'undefined') {
                        generateQRCodeAlternative();
            setTimeout(function() {
                window.print();
                        }, 1000);
                    } else {
                        console.log('Using Google Charts QR Code');
                    }
                }, 1000);
            }
        }
    </script>
</body>
</html> 