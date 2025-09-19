<!DOCTYPE html>
<html>
<head>
    <title>Tax Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0px; }
        .header { text-align: left; vertical-align: top; }
        .company { font-size: 24px; font-weight: bold; }
        .invoice-details { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; vertical-align: text-top;}
        th, td { border: 1px solid black; padding: 8px; text-align: left; vertical-align: top;}
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <table style="border: 1px solid black;>
        <tr>
            <td style="border:none; text-align:left;">
                @if($organization->logo)
                    <img src="{{ public_path('storage/' . $organization->logo) }}" 
                        alt="{{ $organization->name }} Logo" 
                        style="max-widht: 120px; max-height: 120px; vertical-align: top;">
                @endif
            </td>
            <td style="border:none; text-align:left;">
                <div class="header">
                    <div class="company">{{ $organization->name }}</div>
                    @if($organization->primaryAddress())
                        <div>
                            {{ Str::title($organization->primaryAddress()?->street) }},
                            {{ Str::title($organization->primaryAddress()?->area_town) }},
                            {{ Str::title($organization->primaryAddress()?->city?->name) }},
                            {{ Str::title($organization->primaryAddress()?->state?->name) }},
                            {{ Str::title($organization->primaryAddress()?->pin_code) }}
                        </div>
                    @endif
                    <div>Email: {{ $organization->email }}</div>
                    <div>Phone: {{ $organization->phone }}</div>
                    <div>GSTIN: {{ $organization->gst_number }}</div>
                </div>
             </td>
            <td style="border:none; text-align:right;">
                <h2>TAX INVOICE</h2>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="text-align:left; width:50%;">
                    Invoice No: :177720252601151</br>
                    Invoice Date: :27 Aug 2025</br>
                    Due Date: :27 Aug 2025</br>
            </td>
            <td style="text-align:left; width:50%;">
                    Place Of Supply: :Gujarat (24)</br>
                    Terms: :gujaratpolyplast.com</br>
            </td>   
        </tr>
    </table>
   <table>
        <tr>
            <th style="text-align:left; font-size:16px;">Bill To</th>
            <th style="text-align:left; font-size:16px;">Ship To</th>
        </tr>
            <td style="text-align:left; width:50%;">
                Searce India Private Limited<br>
                11 Arham, Subhash Road, Motilal Tanki, Rajkot Gujarat 360001<br>
                India<br>
                GSTIN: 24AAJCS1368L1ZZ<br>
                PAN: ABUJCS1368L
            </td>
            <td style="text-align:left; width:50%;">
                Gujarat Polyplast Pvt. Ltd.<br>
                Vah Estate,Plot No.559,Shed No.3, Nr.Acute Surgical,Rakanpur,<br>
                Kalol<br>
                382721 Gujarat<br>
                India<br>
                GSTIN: 24AACCG9417E1ZT<br>
                PAN: AACCG9417E
            </td>
        </tr>
    </table>

    @php
        $hasDiscount = $document->items->contains(fn($i) => !empty($i->discount) && $i->discount > 0);
        $hasTax = $document->items->contains(fn($i) => !empty($i->tax_rate) && $i->tax_rate > 0);

        $subTotal = $document->items->sum(fn($i) => $i->quantity * $i->price);
        $transactionDiscount = $document->transaction_discount ?? 0;
        $transactionDiscountAmount = $subTotal * ($transactionDiscount / 100);
        $subTotalAfterDiscount = $subTotal - $transactionDiscountAmount;

        // Group tax details
        $taxGroups = $document->taxDetails
            ->groupBy(fn($tax) => $tax->type . ' ' . $tax->rate . '%')
            ->map(fn($group) => $group->sum('amount'));

        $totalTax = $taxGroups->sum();
        $grandTotal = $subTotalAfterDiscount + $totalTax;
    @endphp
    
    <table>
        <tr>
            <th>#</th>
            <th>Item</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Price</th>

            @if($hasDiscount)
                <th>Disc %</th>
            @endif

            @if($hasTax)
                <th>Tax %</th>
            @endif

            <th>Amount</th>
        </tr>
        <tr>
            @foreach ($document->items as $index => $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->itemMaster?->item_name ?? '-' }}</td>
                    <td>{{ $item->description ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>

                    @if($hasDiscount)
                        <td>{{ $item->discount ? number_format($item->discount, 2) : '-' }}</td>
                    @endif

                    @if($hasTax)
                        <td>{{ $item->tax_rate ? rtrim(rtrim(number_format($item->tax_rate, 2), '0'), '.') . '%' : '-' }}</td>
                    @endif
                    <td>{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tr>
    </table>
    
    <!-- Summary Table -->
    <table style="width: 40%; margin-left: auto; border-collapse: collapse; margin-top: 15px;" border="1">
        <tr>
            <td style="text-align:right; font-weight:bold;">Subtotal</td>
            <td style="text-align:right;">{{ number_format($subTotal, 2) }}</td>
        </tr>

        @if($transactionDiscount > 0)
            <tr>
                <td style="text-align:right; font-weight:bold;">Transaction Discount ({{ $transactionDiscount }}%)</td>
                <td style="text-align:right;">-{{ number_format($transactionDiscountAmount, 2) }}</td>
            </tr>
        @endif

        @foreach($taxGroups as $label => $amount)
            <tr>
                <td style="text-align:right; font-weight:bold;">{{ $label }}</td>
                <td style="text-align:right;">+{{ number_format($amount, 2) }}</td>
            </tr>
        @endforeach

        <tr>
            <td style="text-align:right; font-weight:bold;">Grand Total</td>
            <td style="text-align:right; font-weight:bold;">{{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>

    <div>
        <p>Total in Words: Indian Rupee One Thousand Seven Hundred Seventy-Six Only</p>
        <p>Notes: Thanks for your business.</p>
    </div>
    <div>
        <p><strong>Payment Options</strong></p>
        <p>Bank Name & Branch - HSBC Bank, Bund Garden, Pune</p>
        <p>Account Type | Current Account</p>
        <p>Account Number # 05-00947-001</p>
        <p>IFSC | HSBC0411002</p>
        <p>Swift Code | HSBCINBB</p>
    </div>
    <p>Name: Searce India Private Limited</p>
    <p>Digitally signed by ABHISHEK JHAGARAWAT</p>
    <p>Date: 27-08-2025 11:52:03</p>
    <p>Supply meant for export/supply to SEZ unit or SEZ developer for authorized operations under Letter of Undertaking without payment of integrated tax vide LUT # AD2042320327713 valid upto 31-03-2026.</p>
</body>
</html>