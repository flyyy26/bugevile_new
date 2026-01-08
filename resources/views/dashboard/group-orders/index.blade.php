<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Orders - Dashboard</title>
    <style>
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f3f4f6;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #3b82f6;
        }
        
        .stat-card.pending {
            border-left-color: #f59e0b;
        }
        
        .stat-card.paid {
            border-left-color: #10b981;
        }
        
        .stat-card.revenue {
            border-left-color: #8b5cf6;
        }
        
        .stat-card.today {
            border-left-color: #ef4444;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 5px;
        }
        
        .filter-input,
        .filter-select {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        /* Button Styles */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        .btn-success {
            background-color: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #059669;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table thead {
            background-color: #f9fafb;
        }
        
        .table th {
            padding: 16px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .table td {
            padding: 16px;
            font-size: 14px;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table tbody tr:hover {
            background-color: #f9fafb;
        }
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-lunas {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-belum {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-progress {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-icon {
            padding: 6px;
            border-radius: 6px;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-icon:hover {
            background-color: #f3f4f6;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination-btn {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .pagination-btn:hover:not(:disabled) {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .pagination-info {
            font-size: 14px;
            color: #6b7280;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state-icon {
            font-size: 48px;
            color: #9ca3af;
            margin-bottom: 20px;
        }
        
        .empty-state-text {
            color: #6b7280;
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .table {
                min-width: 800px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="page-title">Group Orders</h1>
            <div class="header-actions">
                <button class="btn btn-success" onclick="exportToExcel()">
                    📊 Export Excel
                </button>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Group Orders</div>
                <div class="stat-value">{{ number_format($stats['total_groups']) }}</div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-label">Belum Lunas</div>
                <div class="stat-value">{{ number_format($stats['total_pending']) }}</div>
            </div>
            
            <div class="stat-card paid">
                <div class="stat-label">Sudah Lunas</div>
                <div class="stat-value">{{ number_format($stats['total_paid']) }}</div>
            </div>
            
            <div class="stat-card revenue">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">Rp {{ number_format($stats['total_revenue']) }}</div>
            </div>
            
            <div class="stat-card today">
                <div class="stat-label">Hari Ini</div>
                <div class="stat-value">{{ number_format($stats['today_groups']) }}</div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ url()->current() }}">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">Cari</label>
                        <input type="text" 
                               name="search" 
                               class="filter-input" 
                               placeholder="Kode group atau nama pelanggan..."
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Tanggal Mulai</label>
                        <input type="date" 
                               name="start_date" 
                               class="filter-input"
                               value="{{ request('start_date') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Tanggal Akhir</label>
                        <input type="date" 
                               name="end_date" 
                               class="filter-input"
                               value="{{ request('end_date') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Status Pembayaran</label>
                        <select name="payment_status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="lunas" {{ request('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum" {{ request('payment_status') == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="reset" class="btn btn-secondary" onclick="window.location.href='{{ url()->current() }}'">
                        🔄 Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        🔍 Filter
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Table -->
        <div class="table-container">
            @if($groupOrders->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Group</th>
                        <th>Pelanggan</th>
                        <th>Sales</th>
                        <th>Total Harga</th>
                        <th>DP</th>
                        <th>Sisa</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupOrders as $group)
                    <tr>
                        <td>
                            <strong>{{ $group->kode_group }}</strong>
                            <div style="font-size: 12px; color: #6b7280;">
                                {{ $group->orders_count ?? 0 }} order
                            </div>
                        </td>
                        <td>{{ $group->pelanggan->nama ?? '-' }}</td>
                        <td>
                            @if($group->affiliate)
                                {{ $group->affiliate->nama }}
                                <div style="font-size: 12px; color: #6b7280;">
                                    {{ $group->affiliate->kode }}
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <strong>Rp {{ number_format($group->grand_total) }}</strong>
                        </td>
                        <td>
                            @if($group->dp_amount > 0)
                                <span style="color: #3b82f6;">Rp {{ number_format($group->dp_amount) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($group->sisa_bayar > 0)
                                <span style="color: #ef4444;">Rp {{ number_format($group->sisa_bayar) }}</span>
                            @else
                                <span style="color: #10b981;">Lunas</span>
                            @endif
                        </td>
                        <td>
                            @if($group->payment_status)
                                <span class="badge badge-lunas">LUNAS</span>
                            @else
                                <span class="badge badge-belum">BELUM LUNAS</span>
                            @endif
                        </td>
                        <td>
                            {{ $group->created_at->format('d/m/Y') }}
                            <div style="font-size: 12px; color: #6b7280;">
                                {{ $group->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon" title="Detail" 
                                        onclick="window.location.href='{{ route('group-orders.show', $group->id) }}'">
                                    👁️
                                </button>
                                <button class="btn-icon" title="Print" 
                                        onclick="printGroupOrder({{ $group->id }})">
                                    🖨️
                                </button>
                                @if(!$group->payment_status)
                                <button class="btn-icon" title="Tandai Lunas" 
                                        onclick="markAsPaid({{ $group->id }})" 
                                        style="color: #10b981;">
                                    💰
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3 class="empty-state-text">Tidak ada data group order</h3>
                <p style="color: #9ca3af; margin-bottom: 20px;">Coba ubah filter pencarian Anda</p>
                <button class="btn btn-primary" onclick="window.location.href='{{ url()->current() }}'">
                    🔄 Reset Filter
                </button>
            </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($groupOrders->hasPages())
        <div class="pagination">
            <button class="pagination-btn" 
                    onclick="window.location.href='{{ $groupOrders->previousPageUrl() }}'"
                    {{ $groupOrders->onFirstPage() ? 'disabled' : '' }}>
                ← Prev
            </button>
            
            <span class="pagination-info">
                Halaman {{ $groupOrders->currentPage() }} dari {{ $groupOrders->lastPage() }}
            </span>
            
            <button class="pagination-btn" 
                    onclick="window.location.href='{{ $groupOrders->nextPageUrl() }}'"
                    {{ !$groupOrders->hasMorePages() ? 'disabled' : '' }}>
                Next →
            </button>
        </div>
        @endif
    </div>
    
    <script>
        // Fungsi Export Excel
        function exportToExcel() {
            // Get filter parameters
            const params = new URLSearchParams(window.location.search);
            const url = '{{ route("group-order") }}' + '/export?' + params.toString();
            
            // Create temporary link
            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        // Fungsi Print Group Order
        function printGroupOrder(id) {
            const url = '{{ route("group-order.show", ":id") }}'.replace(':id', id) + '?print=true';
            window.open(url, '_blank');
        }
        
        // Fungsi Tandai sebagai Lunas
        function markAsPaid(id) {
            if (confirm('Tandai group order ini sebagai LUNAS?')) {
                fetch('{{ url("dashboard/group-order") }}/' + id + '/mark-paid', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Group order berhasil ditandai sebagai LUNAS!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui status.');
                });
            }
        }
        
        // Auto-refresh setiap 30 detik jika ada filter aktif
        @if(request()->hasAny(['search', 'start_date', 'end_date', 'payment_status']))
        setTimeout(() => {
            location.reload();
        }, 30000); // 30 detik
        @endif
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + E untuk export
            if (e.ctrlKey && e.key === 'e') {
                e.preventDefault();
                exportToExcel();
            }
            
            // Ctrl + F untuk focus search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.querySelector('input[name="search"]').focus();
            }
        });
    </script>
</body>
</html>