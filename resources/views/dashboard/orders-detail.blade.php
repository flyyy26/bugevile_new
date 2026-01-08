@if($pembayaranList->count() > 0)
                    <div class="detail-pembayaran" style="margin-top: 20px;">
                        <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 10px; color: #555;">Detail Pembayaran Per Order</h4>
                        
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 6px; overflow: hidden;">
                                <thead style="background-color: #f5f5f5;">
                                    <tr>
                                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; font-weight: 600;">Order ID</th>
                                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; font-weight: 600;">DP</th>
                                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; font-weight: 600;">Harus Dibayar</th>
                                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; font-weight: 600;">Sisa Bayar</th>
                                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; font-weight: 600;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayaranList as $pembayaran)
                                    <tr>
                                        <td style="padding: 10px; border-bottom: 1px solid #eee;">Order #{{ $pembayaran->order_id }}</td>
                                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: #2196F3;">
                                            Rp {{ number_format($pembayaran->dp) }}
                                        </td>
                                        <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                            Rp {{ number_format($pembayaran->harus_dibayar) }}
                                        </td>
                                        <td style="padding: 10px; border-bottom: 1px solid #eee; color: {{ $pembayaran->sisa_bayar > 0 ? '#FF9800' : '#4CAF50' }};">
                                            Rp {{ number_format($pembayaran->sisa_bayar) }}
                                        </td>
                                        <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                            <span style="color: {{ $pembayaran->status ? '#4CAF50' : '#FF9800' }}; font-weight: 600;">
                                                {{ $pembayaran->status ? 'LUNAS' : 'BELUM LUNAS' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background-color: #f9f9f9;">
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600;">TOTAL</td>
                                        <td style="padding: 10px; font-weight: 600; color: #2196F3;">
                                            Rp {{ number_format($totalDP) }}
                                        </td>
                                        <td style="padding: 10px; font-weight: 600;">
                                            Rp {{ number_format($totalHarusDibayar) }}
                                        </td>
                                        <td style="padding: 10px; font-weight: 600; color: {{ $totalSisaBayar > 0 ? '#FF9800' : '#4CAF50' }};">
                                            Rp {{ number_format($totalSisaBayar) }}
                                        </td>
                                        <td style="padding: 10px; font-weight: 600; {{ $statusPembayaranClass }}">
                                            {{ $statusPembayaranText }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @else
                    <div style="text-align: center; padding: 20px; color: #666;">
                        <p>Belum ada data pembayaran untuk pelanggan ini.</p>
                    </div>
                @endif