<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Order: {{ $groupOrder->kode_group }} - Dashboard</title>
    <style>
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        .header {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }
        
        .header-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
        
        .info-item {
            font-size: 14px;
            color: #4a5568;
        }
        
        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-gray {
            background-color: #718096;
            color: white;
        }
        
        .btn-gray:hover {
            background-color: #4a5568;
        }
        
        .btn-blue {
            background-color: #4299e1;
            color: white;
        }
        
        .btn-blue:hover {
            background-color: #3182ce;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
        }
        
        /* Card Styles */
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #edf2f7;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-box {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 20px;
            font-weight: 700;
        }
        
        .stat-green {
            color: #38a169;
        }
        
        .stat-blue {
            color: #3182ce;
        }
        
        .stat-orange {
            color: #dd6b20;
        }
        
        /* Payment Grid */
        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .payment-box {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        /* Job Tags */
        .job-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        
        .tag {
            display: inline-block;
            padding: 6px 12px;
            background-color: #ebf8ff;
            color: #2c5282;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-header {
            padding: 15px 20px;
            background-color: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            padding: 12px 15px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: #f7fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .table td {
            padding: 12px 15px;
            font-size: 14px;
            color: #2d3748;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table tbody tr:hover {
            background-color: #f7fafc;
        }
        
        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-green {
            background-color: #c6f6d5;
            color: #22543d;
        }
        
        .badge-yellow {
            background-color: #fefcbf;
            color: #744210;
        }
        
        .badge-orange {
            background-color: #feebc8;
            color: #7b341e;
        }
        
        /* Table Footer */
        .table-footer {
            padding: 15px 20px;
            background-color: #f7fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .total-label {
            font-weight: 600;
            color: #4a5568;
        }
        
        .total-value {
            font-size: 18px;
            font-weight: 700;
            color: #38a169;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header-top {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .btn-group {
                width: 100%;
                justify-content: flex-start;
            }
            
            .stats-grid,
            .payment-grid {
                grid-template-columns: 1fr;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .table {
                min-width: 700px;
            }
            
            .header-info {
                grid-template-columns: 1fr;
            }
        }
        
        @media print {
            .btn-group {
                display: none;
            }
            
            body {
                background: white;
            }
            
            .container {
                padding: 0;
            }
            
            .card, .table-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <h1 class="page-title">Group Order: {{ $groupOrder->kode_group }}</h1>
                <div class="btn-group">
                    <a href="{{ route('group-order.index') }}" class="btn btn-gray">
                        ← Kembali
                    </a>
                    <button onclick="window.print()" class="btn btn-blue">
                        🖨️ Print
                    </button>
                </div>
            </div>
            <div class="header-info">
                <div class="info-item">
                    <strong>Tanggal:</strong> {{ $groupOrder->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="info-item">
                    <strong>Pelanggan:</strong> {{ $groupOrder->pelanggan->nama }}
                </div>
                @if($groupOrder->affiliate)
                <div class="info-item">
                    <strong>Sales:</strong> {{ $groupOrder->affiliate->nama }} ({{ $groupOrder->affiliate->kode }})
                </div>
                @endif
            </div>
        </div>
        
        <!-- Info Group Order -->
        <div class="card">
            <h2 class="card-title">Informasi Group Order</h2>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-label">Total Harga Group</div>
                    <div class="stat-value stat-green">Rp {{ number_format($totalHargaGroup) }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Jumlah Order</div>
                    <div class="stat-value stat-blue">{{ $groupOrder->orders->count() }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Status Pembayaran</div>
                    <div class="stat-value {{ $groupOrder->payment_status ? 'stat-green' : 'stat-orange' }}">
                        {{ $groupOrder->payment_status ? 'LUNAS' : 'BELUM LUNAS' }}
                    </div>
                </div>
            </div>
            
            <!-- Info Nama Job -->
            @if(count($semuaNamaJob) > 0)
            <div style="margin-top: 15px;">
                <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">Nama Job:</div>
                <div class="job-tags">
                    @foreach($semuaNamaJob as $job)
                    <span class="tag">{{ $job }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Detail Pembayaran -->
        @if($groupOrder->pembayaran)
        <div class="card">
            <h2 class="card-title">Detail Pembayaran</h2>
            <div class="payment-grid">
                <div class="payment-box">
                    <div class="stat-label">DP</div>
                    <div class="stat-value stat-blue">Rp {{ number_format($groupOrder->pembayaran->dp) }}</div>
                </div>
                <div class="payment-box">
                    <div class="stat-label">Harus Dibayar</div>
                    <div class="stat-value">Rp {{ number_format($groupOrder->pembayaran->harus_dibayar) }}</div>
                </div>
                <div class="payment-box">
                    <div class="stat-label">Sisa Bayar</div>
                    <div class="stat-value {{ $groupOrder->pembayaran->sisa_bayar > 0 ? 'stat-orange' : 'stat-green' }}">
                        Rp {{ number_format($groupOrder->pembayaran->sisa_bayar) }}
                    </div>
                </div>
                <div class="payment-box">
                    <div class="stat-label">Status</div>
                    <div class="stat-value {{ $groupOrder->pembayaran->status ? 'stat-green' : 'stat-orange' }}">
                        {{ $groupOrder->pembayaran->status ? 'LUNAS' : 'BELUM LUNAS' }}
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Daftar Order dalam Group -->
        <div class="table-container">
            <div class="table-header">
                <h2 style="font-size: 16px; font-weight: 600; color: #2d3748;">
                    Daftar Order ({{ $groupOrder->orders->count() }})
                </h2>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Jenis Order</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupOrder->orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ optional($order->jenisOrder)->nama_jenis ?? '-' }}</td>
                            <td>{{ $order->qty }}</td>
                            <td>Rp {{ number_format($order->harga_jual_satuan) }}</td>
                            <td style="font-weight: 600;">Rp {{ number_format($order->harga_jual_total) }}</td>
                            <td>
                                <span class="badge {{ $order->status ? 'badge-green' : 'badge-yellow' }}">
                                    {{ $order->status ? 'Selesai' : 'Proses' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <div class="total-label">TOTAL:</div>
                <div class="total-value">Rp {{ number_format($totalHargaGroup) }}</div>
            </div>
        </div>
    </div>
    
    <script>
        // Print optimization
        function optimizeForPrint() {
            document.body.style.backgroundColor = 'white';
            const cards = document.querySelectorAll('.card, .table-container');
            cards.forEach(card => {
                card.style.boxShadow = 'none';
                card.style.border = '1px solid #ddd';
            });
        }
        
        // Jika print dipanggil
        window.onbeforeprint = optimizeForPrint;
        
        // Keyboard shortcut untuk print (Ctrl+P)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                optimizeForPrint();
                setTimeout(() => window.print(), 100);
            }
        });
    </script>
</body>
</html>