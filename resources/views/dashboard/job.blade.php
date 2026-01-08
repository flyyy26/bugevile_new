@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<script>
    function openKategori(id) {
        // sembunyikan semua kategori
        document.querySelectorAll('.kategori-wrapper').forEach(el => el.classList.add('hidden'));

        // tampilkan kategori yang dipilih
        document.getElementById('kategori-' + id).classList.remove('hidden');

        // ubah style tab
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('background_gray', 'text-white'));
        event.target.classList.add('background_gray', 'text-white');

        // Tampilkan keterangan box berdasarkan kategori yang dipilih
        // Jika ada jenis_order yang sedang dipilih di dalam kategori ini, gunakan filter berdasarkan jenis_order
        const kategoriEl = document.getElementById('kategori-' + id);
        const checkedJenis = kategoriEl.querySelector('.js-pakaian-input:checked');
        if (checkedJenis && typeof updateKeteranganByJenisOrder === 'function') {
            updateKeteranganByJenisOrder(checkedJenis.value);
        } else {
            displayKeteranganByKategori(id);
        }
    }

    // Tidak auto-open tab pertama: biarkan user memilih kategori secara eksplisit
    // (keterangan akan tampil hanya setelah user klik kategori)
</script>

<script>
    // ============================================
    // VARIABEL & FUNGSI GLOBAL (UNTUK HTML onclick)
    // ============================================
    let paymentMode = null;
    let grandTotal = 0;
    let orders = {};

    // Fungsi untuk mendapatkan grandTotal dari orders yang aktif
    window.getGrandTotal = function() {
        let total = 0;
        if (window.orders && typeof window.orders === 'object') {
            Object.values(window.orders).forEach(order => {
                if (order && order.totalQty > 0) {
                    total += (order.totalHarga || 0);
                }
            });
        }
        window.grandTotal = total;
        return total;
    };

    // Fungsi-fungsi yang dipanggil dari HTML onclick - HARUS GLOBAL
    window.setPembayaranDP = function() {
        // Update grandTotal dulu
        const currentTotal = getGrandTotal();
        
        if (currentTotal <= 0) {
            showToast('Tambahkan order dulu', 'Tambahkan order terlebih dahulu sebelum mengatur pembayaran');
            return;
        }
        
        paymentMode = 'dp';
        showPaymentSection('DP (Down Payment)');
        
        const paymentAmountInput = document.getElementById('paymentAmount');
        if (paymentAmountInput) {
            paymentAmountInput.value = '';
            paymentAmountInput.placeholder = 'Masukkan jumlah DP';
            paymentAmountInput.focus();
        }
        
        // Reset hidden inputs
        document.getElementById('dpAmount').value = 0;
        document.getElementById('sisaBayar').value = currentTotal;
        document.getElementById('harusDibayar').value = currentTotal;
        document.getElementById('paymentStatus').value = false;
        
        hitungSisaPembayaran();
    };

    window.setPembayaranLunas = function() {
        // Update grandTotal dulu
        const currentTotal = getGrandTotal();
        
        if (currentTotal <= 0) {
            showToast('Tambahkan order dulu', 'Tambahkan order terlebih dahulu sebelum mengatur pembayaran');
            return;
        }
        
        paymentMode = 'lunas';
        showPaymentSection('Pembayaran Lunas');
        
        const paymentAmountInput = document.getElementById('paymentAmount');
        if (paymentAmountInput) {
            paymentAmountInput.value = currentTotal;
            paymentAmountInput.placeholder = 'Jumlah pembayaran';
        }
        
        // Set hidden inputs untuk lunas
        document.getElementById('dpAmount').value = currentTotal;
        document.getElementById('sisaBayar').value = 0;
        document.getElementById('harusDibayar').value = currentTotal;
        document.getElementById('paymentStatus').value = true;
        
        hitungSisaPembayaran();
    };

    window.hitungSisaPembayaran = function() {
        // Pastikan ada payment mode dan grandTotal valid
        const currentTotal = getGrandTotal();
        
        if (!paymentMode || currentTotal <= 0) {
            // Jika belum ada order, sembunyikan section
            if (paymentMode) {
                showToast('warning', 'Tambahkan order terlebih dahulu');
            }
            return;
        }
        
        const paymentInput = document.getElementById('paymentAmount');
        if (!paymentInput) return;
        
        const paymentAmount = parseFloat(paymentInput.value) || 0;
        
        if (paymentMode === 'dp') {
            const sisa = Math.max(0, currentTotal - paymentAmount);
            
            // Update hidden inputs
            document.getElementById('dpAmount').value = paymentAmount;
            document.getElementById('sisaBayar').value = sisa;
            document.getElementById('harusDibayar').value = currentTotal;
            document.getElementById('paymentStatus').value = (sisa <= 0);
            
            // Update UI
            const dpInfo = document.getElementById('dpAmountInfo');
            const sisaInfo = document.getElementById('sisaAmountInfo');
            const dpInfoContainer = document.getElementById('dpInfoContainer');
            const sisaInfoContainer = document.getElementById('sisaInfoContainer');
            const lunasInfoContainer = document.getElementById('lunasInfoContainer');
            const totalHargaInfo = document.getElementById('totalHargaInfo');
            
            if (dpInfo) dpInfo.textContent = formatRupiah(paymentAmount);
            if (sisaInfo) sisaInfo.textContent = formatRupiah(sisa);
            if (totalHargaInfo) totalHargaInfo.textContent = formatRupiah(currentTotal);
            
            // Tampilkan/sembunyikan informasi
            if (dpInfoContainer) dpInfoContainer.style.display = 'flex';
            if (sisaInfoContainer) sisaInfoContainer.style.display = sisa > 0 ? 'flex' : 'none';
            if (lunasInfoContainer) lunasInfoContainer.style.display = sisa <= 0 ? 'flex' : 'none';
            
        } else if (paymentMode === 'lunas') {
            // Untuk lunas, otomatis set pembayaran = grand total
            const finalAmount = Math.max(paymentAmount, currentTotal);
            paymentInput.value = finalAmount;
            
            // Update hidden inputs
            document.getElementById('dpAmount').value = finalAmount;
            document.getElementById('sisaBayar').value = 0;
            document.getElementById('harusDibayar').value = currentTotal;
            document.getElementById('paymentStatus').value = true;
            
            // Update UI
            const dpInfoContainer = document.getElementById('dpInfoContainer');
            const sisaInfoContainer = document.getElementById('sisaInfoContainer');
            const lunasInfoContainer = document.getElementById('lunasInfoContainer');
            const totalHargaInfo = document.getElementById('totalHargaInfo');
            const dpInfo = document.getElementById('dpAmountInfo');
            
            if (dpInfoContainer) dpInfoContainer.style.display = 'flex';
            if (sisaInfoContainer) sisaInfoContainer.style.display = 'none';
            if (lunasInfoContainer) lunasInfoContainer.style.display = 'flex';
            if (totalHargaInfo) totalHargaInfo.textContent = formatRupiah(currentTotal);
            if (dpInfo) dpInfo.textContent = formatRupiah(finalAmount);
        }
    };

    // Fungsi helper lainnya yang perlu global
    window.showPaymentSection = function(label) {
        const section = document.getElementById('paymentSection');
        const labelElement = document.getElementById('paymentLabel');
        
        if (section && labelElement) {
            section.classList.remove('hidden');
            labelElement.textContent = label;
            
            // Update info total harga
            const currentTotal = getGrandTotal();
            const totalHargaInfo = document.getElementById('totalHargaInfo');
            if (totalHargaInfo) {
                totalHargaInfo.textContent = formatRupiah(currentTotal);
            }
            
            // Update DP info juga
            const dpInfo = document.getElementById('dpAmountInfo');
            if (dpInfo && paymentMode === 'lunas') {
                dpInfo.textContent = formatRupiah(currentTotal);
            }
        }
    };

    window.formatRupiah = function(angka) {
        if (!angka && angka !== 0) angka = 0;
        return 'Rp ' + angka.toLocaleString('id-ID');
    };

    window.resetPaymentSection = function() {
        paymentMode = null;
        const section = document.getElementById('paymentSection');
        if (section) section.classList.add('hidden');
        
        const paymentAmount = document.getElementById('paymentAmount');
        if (paymentAmount) paymentAmount.value = '';
        
        const dpAmount = document.getElementById('dpAmount');
        const sisaBayar = document.getElementById('sisaBayar');
        const harusDibayar = document.getElementById('harusDibayar');
        const paymentStatus = document.getElementById('paymentStatus');
        
        if (dpAmount) dpAmount.value = 0;
        if (sisaBayar) sisaBayar.value = 0;
        if (harusDibayar) harusDibayar.value = 0;
        if (paymentStatus) paymentStatus.value = false;
    };

    function viewInvoice() {
        const pelangganId = document.getElementById('savedPelangganId').value;
        if (!pelangganId) {
            showToast('error', 'ID Pelanggan tidak ditemukan');
            return;
        }
        
        // Gunakan route yang benar
        const invoiceUrl = `/dashboard/pelanggan/${pelangganId}`;
        
        window.location.href = invoiceUrl;
        
        // Optional: Beri feedback
        showToast('info', 'Membuka invoice...');
    }

    function viewNota() {
        const pelangganId = document.getElementById('savedPelangganId').value;
        if (!pelangganId) {
            showToast('error', 'ID Pelanggan tidak ditemukan');
            return;
        }
        
        // Gunakan route yang benar
        const notaUrl = `/dashboard/nota/pelanggan/${pelangganId}`;
        
        window.location.href = notaUrl;
        
        // Optional: Beri feedback
        showToast('info', 'Membuka nota...');
    }

     function disableFormAfterSave() {
        const form = document.getElementById('multiOrderForm');
        const allInputs = form.querySelectorAll('input, select, textarea, button');
        
        allInputs.forEach(input => {
            // Hanya nonaktifkan yang bukan tombol setelah save
            if (input.id !== 'btnViewInvoice' && 
                input.id !== 'btnViewNota' && 
                input.id !== 'btnCloseAfter') {
                input.disabled = true;
                input.classList.add('disabled');
            }
        });
        
        // Nonaktifkan button kategori dan jenis order
        document.querySelectorAll('.tab-btn, .js-jenis-order-btn').forEach(btn => {
            btn.disabled = true;
            btn.classList.add('disabled');
        });
    }

     function disableFormInputs() {
        // Nonaktifkan semua input dan select di dalam form
        const form = document.getElementById('multiOrderForm');
        const inputs = form.querySelectorAll('input, select, button, textarea');
        
        inputs.forEach(input => {
            if (!input.id || (input.id !== 'btnCloseAfter' && 
                            input.id !== 'btnViewInvoice' && 
                            input.id !== 'btnViewNota')) {
                input.disabled = true;
                input.style.opacity = '0.6';
                input.style.cursor = 'not-allowed';
            }
        });
        
        // Nonaktifkan button kategori
        document.querySelectorAll('.tab-btn, .js-jenis-order-btn').forEach(btn => {
            btn.disabled = true;
            btn.style.opacity = '0.6';
            btn.style.cursor = 'not-allowed';
        });
    }

    function closeModalEnhanced() {
        // Reset tombol ke state awal
        document.getElementById('buttonsBeforeSave').classList.remove('hidden');
        document.getElementById('buttonsAfterSave').classList.add('hidden');
        
        // Aktifkan kembali semua input
        const form = document.getElementById('multiOrderForm');
        const inputs = form.querySelectorAll('input, select, button, textarea');
        
        inputs.forEach(input => {
            input.disabled = false;
            input.style.opacity = '';
            input.style.cursor = '';
            input.classList.remove('disabled');
        });
        
        // Aktifkan button kategori
        document.querySelectorAll('.tab-btn, .js-jenis-order-btn').forEach(btn => {
            btn.disabled = false;
            btn.style.opacity = '';
            btn.style.cursor = '';
            btn.classList.remove('disabled');
        });
        
        // TUTUP MODAL LANGSUNG dan REFRESH HALAMAN
        document.getElementById('popupModalOrder').classList.remove('active');
        
        // Refresh halaman setelah jeda kecil
        setTimeout(() => {
            window.location.reload();
        }, 300);
    }

    // Fungsi untuk update grand total UI (dipanggil saat order berubah)
    window.updateGrandTotalUI = function() {
        const calculatedTotal = getGrandTotal();
        
        const grandTotalDisplay = document.getElementById('grandTotalDisplay');
        const grandTotalValue = document.getElementById('grandTotalValue');
        
        if (grandTotalDisplay) {
            grandTotalDisplay.textContent = formatRupiah(calculatedTotal);
        }
        if (grandTotalValue) {
            grandTotalValue.value = calculatedTotal;
        }
        
        // Update juga di payment info jika section sedang aktif
        if (paymentMode) {
            const totalHargaInfo = document.getElementById('totalHargaInfo');
            if (totalHargaInfo) {
                totalHargaInfo.textContent = formatRupiah(calculatedTotal);
            }
            
            // Hitung ulang sisa pembayaran
            hitungSisaPembayaran();
        }
        
        return calculatedTotal;
    };

    // ============================================
    // DOM CONTENT LOADED - Inisialisasi
    // ============================================
    document.addEventListener("DOMContentLoaded", function () {
        console.log('DOM loaded - Initializing order system...');

        // Data dari Blade
        const allJenisSpek = @json($jenisSpek);
        const allJenisSpekDetail = @json($jenisSpekDetail);
        const allJenisOrders = @json($jenisOrders);

        // Variabel di dalam scope ini
        let activeJenisId = null;
        let currentKategoriId = null;

        // Initialize orders object jika belum ada
        if (!window.orders) {
            window.orders = {};
        }

        // Fungsi openKategori
        window.openKategori = function(kategoriId, buttonElement) {
            // Sembunyikan semua kategori
            document.querySelectorAll('.kategori-wrapper').forEach(el => {
                el.classList.add('hidden');
            });
            
            // Tampilkan kategori yang dipilih
            const targetKategori = document.getElementById(`kategori-${kategoriId}`);
            if (targetKategori) {
                targetKategori.classList.remove('hidden');
            }
            
            // Update style tab
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('tab-active', 'tab-inactive');
                el.classList.add('tab-inactive');
            });
            buttonElement.classList.remove('tab-inactive');
            buttonElement.classList.add('tab-active');
            
            // Simpan kategori aktif
            currentKategoriId = kategoriId;
            
            // Reset active jenis jika pindah kategori
            if (activeJenisId) {
                const activeJenis = allJenisOrders.find(j => j.id == activeJenisId);
                if (activeJenis && activeJenis.id_kategori_jenis_order != kategoriId) {
                    setActiveJenisOrder(null);
                    const activeContainer = document.getElementById('activeOrderContainer');
                    if (activeContainer) {
                        activeContainer.classList.add('hidden');
                        activeContainer.innerHTML = '';
                    }
                }
            }
            
            // Update button jenis order untuk kategori ini
            updateJenisOrderButtonsForKategori(kategoriId);
        };

        // Event listener untuk button jenis order
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('js-jenis-order-btn')) {
                const jenisId = e.target.dataset.jenisId;
                const kategoriId = e.target.dataset.kategoriId;
                const namaJenis = e.target.dataset.namaJenis;
                const hargaJual = parseFloat(e.target.dataset.hargaJual) || 0;
                
                // Set sebagai active
                setActiveJenisOrder(jenisId);
                
                // Update button styling
                updateJenisOrderButtons(jenisId);
                
                // Tampilkan/update form order
                showOrderForm(jenisId, kategoriId, namaJenis, hargaJual);
            }
        });

        // Fungsi untuk set active jenis order
        function setActiveJenisOrder(jenisId) {
            activeJenisId = jenisId;
        }

        // Fungsi update button styling untuk semua button
        function updateJenisOrderButtons(activeId) {
            document.querySelectorAll('.js-jenis-order-btn').forEach(btn => {
                const jenisId = btn.dataset.jenisId;
                const hasOrder = window.orders[jenisId] && window.orders[jenisId].totalQty > 0;
                
                // Reset semua
                btn.classList.remove('jenis-active', 'jenis-has-order');
                
                // Set active jika sama dengan yang aktif
                if (jenisId == activeId) {
                    btn.classList.add('jenis-active');
                }
                
                // Set has-order jika ada data
                if (hasOrder) {
                    btn.classList.add('jenis-has-order');
                    
                    // Update badge dengan total qty
                    const badge = btn.querySelector('.order-badge');
                    if (badge) {
                        badge.textContent = window.orders[jenisId].totalQty;
                        badge.style.display = 'flex';
                    }
                } else {
                    // Sembunyikan badge jika tidak ada order
                    const badge = btn.querySelector('.order-badge');
                    if (badge) {
                        badge.textContent = '';
                        badge.style.display = 'none';
                    }
                }
            });
        }

        // Fungsi update button styling hanya untuk kategori tertentu
        function updateJenisOrderButtonsForKategori(kategoriId) {
            document.querySelectorAll('.js-jenis-order-btn').forEach(btn => {
                if (btn.dataset.kategoriId == kategoriId) {
                    const jenisId = btn.dataset.jenisId;
                    const hasOrder = window.orders[jenisId] && window.orders[jenisId].totalQty > 0;
                    
                    // Reset kelas untuk button dalam kategori ini
                    btn.classList.remove('jenis-active', 'jenis-has-order');
                    
                    // Set has-order jika ada data
                    if (hasOrder) {
                        btn.classList.add('jenis-has-order');
                        
                        // Update badge
                        const badge = btn.querySelector('.order-badge');
                        if (badge) {
                            badge.textContent = window.orders[jenisId].totalQty;
                            badge.style.display = 'inline-block';
                        }
                    }
                }
            });
        }

        // Fungsi untuk menampilkan form order
        function showOrderForm(jenisId, kategoriId, namaJenis, hargaJual) {
            // Cek apakah order sudah ada
            let order = window.orders[jenisId];
            
            if (!order) {
                // Buat order baru jika belum ada
                order = {
                    jenisId: jenisId,
                    kategoriId: kategoriId,
                    namaJenis: namaJenis,
                    hargaSatuan: hargaJual,
                    sizes: {
                        xs: 0, s: 0, m: 0, l: 0, xl: 0,
                        '2xl': 0, '3xl': 0, '4xl': 0, '5xl': 0, '6xl': 0
                    },
                    totalQty: 0,
                    totalHarga: 0,
                    speks: {}
                };
                
                // Simpan ke orders
                window.orders[jenisId] = order;
            }
            
            // Render form
            renderOrderForm(order);
            
            // Tampilkan container
            const activeContainer = document.getElementById('activeOrderContainer');
            if (activeContainer) {
                activeContainer.classList.remove('hidden');
            }
        }

        // Fungsi untuk render form order
        function renderOrderForm(order) {
            // Hapus form yang lama jika ada
            const existingForm = document.getElementById(`order-form-${order.jenisId}`);
            if (existingForm) {
                existingForm.remove();
            }
            
            // Dapatkan template
            const template = document.getElementById('orderFormTemplate');
            if (!template) {
                console.error('Template orderFormTemplate tidak ditemukan!');
                return;
            }
            
            // Clone template content
            const orderForm = template.content.cloneNode(true);
            const formDiv = orderForm.querySelector('.active-order-form');
            
            if (!formDiv) {
                console.error('Element .active-order-form tidak ditemukan dalam template!');
                return;
            }
            
            formDiv.id = `order-form-${order.jenisId}`;
            formDiv.dataset.jenisId = order.jenisId;
            
            // Set judul
            const titleElement = formDiv.querySelector('.active-order-title');
            if (titleElement) {
                titleElement.textContent = order.namaJenis;
            }
            
            // Set harga satuan display
            const hargaSatuanDisplay = formDiv.querySelector('.harga-satuan-display');
            if (hargaSatuanDisplay) {
                hargaSatuanDisplay.textContent = formatRupiah(order.hargaSatuan);
            }
            
            // Buat size inputs dengan nilai yang sudah ada
            const sizeGrid = formDiv.querySelector('.size-inputs-grid');
            if (sizeGrid) {
                sizeGrid.innerHTML = '';
                const sizes = ['xs', 's', 'm', 'l', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl'];
                
                sizes.forEach(size => {
                    const sizeBox = document.createElement('div');
                    sizeBox.className = 'dashboard_popup_order_size_box';
                    
                    const label = document.createElement('label');
                    label.className = 'block text-xs';
                    label.textContent = size.toUpperCase();
                    
                    const input = document.createElement('input');
                    input.type = 'number';
                    input.value = order.sizes[size] || 0;
                    input.min = '0';
                    input.dataset.size = size;
                    input.dataset.jenisId = order.jenisId;
                    
                    // Event listener untuk update size
                    input.addEventListener('input', (e) => {
                        updateOrderSize(order.jenisId, size, e.target.value);
                    });
                    
                    input.addEventListener('blur', (e) => {
                        updateOrderSize(order.jenisId, size, e.target.value);
                    });
                    
                    sizeBox.appendChild(label);
                    sizeBox.appendChild(input);
                    sizeGrid.appendChild(sizeBox);
                });
            }
            
            // Tambah form baru ke container
            const container = document.getElementById('activeOrderContainer');
            if (container) {
                container.innerHTML = '';
                container.appendChild(formDiv);
                
                // Update totals setelah form dibuat
                updateOrderTotalsUI(order);
                
                // Load keterangan
                loadKeteranganForOrder(order);
            }
        }

        // Fungsi untuk update size order
        function updateOrderSize(jenisId, size, value) {
            const order = window.orders[jenisId];
            if (!order) return;
            
            // Update nilai
            const numValue = parseInt(value) || 0;
            order.sizes[size] = numValue;
            
            // Hitung ulang total
            calculateOrderTotal(order);
            
            // Update UI untuk order ini
            updateOrderTotalsUI(order);
            
            // Update global totals dan UI
            updateGrandTotalUI();
            updateSubmitButton();
            
            // Update button badge
            updateJenisOrderButtons(activeJenisId);
        }

        // Fungsi hitung total order
        function calculateOrderTotal(order) {
            let totalQty = 0;
            
            // Hitung total qty dari semua sizes
            Object.values(order.sizes).forEach(qty => {
                totalQty += qty;
            });
            
            order.totalQty = totalQty;
            order.totalHarga = order.hargaSatuan * totalQty;
            
            return totalQty;
        }

        // Fungsi update UI totals untuk order tertentu
        function updateOrderTotalsUI(order) {
            const formDiv = document.getElementById(`order-form-${order.jenisId}`);
            if (!formDiv) return;
            
            // Pastikan total sudah dihitung
            calculateOrderTotal(order);
            
            // Update total qty display
            const totalQtyDisplay = formDiv.querySelector('#totalQtyDisplay');
            const finalTotalQty = formDiv.querySelector('.final-total-qty');
            if (totalQtyDisplay) totalQtyDisplay.textContent = order.totalQty;
            if (finalTotalQty) finalTotalQty.textContent = order.totalQty;
            
            // Update total harga
            const hargaTotalDisplay = formDiv.querySelector('.harga-total-display');
            if (hargaTotalDisplay) {
                hargaTotalDisplay.textContent = formatRupiah(order.totalHarga);
            }
        }

        // Fungsi untuk load keterangan
        function loadKeteranganForOrder(order) {
            const formDiv = document.getElementById(`order-form-${order.jenisId}`);
            if (!formDiv) return;
            
            const container = formDiv.querySelector('.keterangan-container');
            const keteranganInput = formDiv.querySelector('.keterangan-input');
            
            if (!container || !keteranganInput) return;
            
            // Set nilai keterangan dari data yang sudah ada
            if (order.speks && Object.keys(order.speks).length > 0) {
                const pairs = [];
                Object.keys(order.speks).forEach(spekId => {
                    const spek = allJenisSpek.find(s => s.id == spekId);
                    const spekName = spek ? spek.nama_jenis_spek : "Spek {$spekId}";
                    const detailIds = order.speks[spekId];
                    
                    const detailLabels = detailIds.map(id => {
                        const detail = allJenisSpekDetail.find(d => d.id == id);
                        return detail ? detail.nama_jenis_spek_detail : '';
                    }).filter(label => label);
                    
                    if (detailLabels.length > 0) {
                        pairs.push(`${spekName}: ` + detailLabels.join(', '));
                    }
                });
                
                keteranganInput.value = pairs.join(' | ');
            }
            
            // Filter jenis_spek_detail berdasarkan kategori dan jenis_order
            const filteredDetails = allJenisSpekDetail.filter(detail => {
                const spek = allJenisSpek.find(s => s.id == detail.id_jenis_spek);
                if (!spek || spek.id_kategori_jenis_order != order.kategoriId) {
                    return false;
                }
                return detail.jenis_order && detail.jenis_order.some(jo => jo.id == parseInt(order.jenisId));
            });
            
            if (filteredDetails.length === 0) {
                container.style.display = 'none';
                return;
            }
            
            // Tampilkan container
            container.style.display = 'grid';
            container.innerHTML = '';
            
            // Kelompokkan detail
            const groupedBySpek = {};
            filteredDetails.forEach(detail => {
                const spekId = detail.id_jenis_spek;
                if (!groupedBySpek[spekId]) {
                    groupedBySpek[spekId] = [];
                }
                groupedBySpek[spekId].push(detail);
            });
            
            // Warna berdasarkan kategori
            const boxColor = getCategoryColor(order.kategoriId);
            
            // Buat UI untuk keterangan
            Object.keys(groupedBySpek).forEach(spekId => {
                const spek = allJenisSpek.find(s => s.id == spekId);
                const spekName = spek ? spek.nama_jenis_spek : 'Spek ' + spekId;
                const details = groupedBySpek[spekId];
                
                const box = document.createElement('div');
                box.className = `${boxColor} box_kategori_spek`;
                box.dataset.spekId = spekId;
                box.dataset.jenisId = order.jenisId;
                
                const title = document.createElement('p');
                title.className = 'box_kategori_spek_title';
                title.textContent = spekName;
                box.appendChild(title);
                
                const detailGrid = document.createElement('div');
                detailGrid.className = 'box_kategori_spek_grid';
                
                details.forEach(detail => {
                    const checkboxId = `detail-${order.jenisId}-${spekId}-${detail.id}`;
                    
                    // Check apakah sudah dipilih sebelumnya
                    const isChecked = order.speks[spekId] && 
                                    order.speks[spekId].includes(detail.id.toString());
                    
                    // Checkbox
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = checkboxId;
                    checkbox.className = 'hidden';
                    checkbox.dataset.spekId = spekId;
                    checkbox.dataset.detailId = detail.id;
                    checkbox.dataset.label = detail.nama_jenis_spek_detail;
                    checkbox.dataset.jenisId = order.jenisId;
                    checkbox.checked = isChecked;
                    checkbox.addEventListener('change', () => updateKeteranganForOrder(order.jenisId));
                    
                    // Button
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'box_kategori_spek_btn';
                    button.dataset.for = checkboxId;
                    
                    if (isChecked) {
                        button.classList.add('ring-2', 'ring-green-700');
                    }
                    
                    if (detail.gambar) {
                        const img = document.createElement('img');
                        img.src = '/storage/' + detail.gambar;
                        img.className = 'w-12 h-12 object-cover rounded-full mb-1';
                        button.appendChild(img);
                    } else {
                        const noImg = document.createElement('div');
                        noImg.className = 'w-12 h-12 flex items-center justify-center rounded-full bg-white text-[#009A00] text-xs mb-1';
                        noImg.textContent = 'No Img';
                        button.appendChild(noImg);
                    }
                    
                    const span = document.createElement('span');
                    span.className = 'box_kategori_spek_span';
                    span.textContent = detail.nama_jenis_spek_detail;
                    button.appendChild(span);
                    
                    // Event listener
                    button.addEventListener('click', function() {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                        
                        if (checkbox.checked) {
                            button.classList.add('ring-2', 'ring-green-700');
                        } else {
                            button.classList.remove('ring-2', 'ring-green-700');
                        }
                    });
                    
                    detailGrid.appendChild(checkbox);
                    detailGrid.appendChild(button);
                });
                
                box.appendChild(detailGrid);
                container.appendChild(box);
            });
        }

        // Fungsi update keterangan untuk order
        function updateKeteranganForOrder(jenisId) {
            const order = window.orders[jenisId];
            if (!order) return;
            
            const formDiv = document.getElementById(`order-form-${jenisId}`);
            if (!formDiv) return;
            
            const container = formDiv.querySelector('.keterangan-container');
            const keteranganInput = formDiv.querySelector('.keterangan-input');
            
            if (!container || !keteranganInput) return;
            
            const boxes = container.querySelectorAll('div[data-spek-id]');
            const pairs = [];
            order.speks = {};
            
            boxes.forEach(box => {
                const spekId = box.dataset.spekId;
                const spekName = box.querySelector('.box_kategori_spek_title').textContent.trim();
                
                const checkedBoxes = box.querySelectorAll('input[type="checkbox"]:checked');
                if (checkedBoxes.length > 0) {
                    const selectedLabels = Array.from(checkedBoxes).map(cb => cb.dataset.label);
                    const selectedIds = Array.from(checkedBoxes).map(cb => cb.dataset.detailId);
                    
                    pairs.push(`${spekName}: ` + selectedLabels.join(', '));
                    order.speks[spekId] = selectedIds;
                }
            });
            
            const keteranganText = pairs.join(' | ');
            keteranganInput.value = keteranganText;
        }

        // Fungsi update submit button
        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            
            // Cek apakah ada order dengan qty > 0
            const hasValidOrders = Object.values(window.orders).some(order => order.totalQty > 0);
            
            if (hasValidOrders) {
                submitBtn.disabled = false;
                submitBtn.classList.add('btn-active');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.remove('btn-active');
            }
        }

        // =============================================
        // FUNGSI: Prepare All Data Before Submit
        // =============================================
        function prepareAllData() {
            const form = document.getElementById('multiOrderForm');
            
            // Ambil data pembayaran
            const dpAmount = parseFloat(document.getElementById('dpAmount').value) || 0;
            const sisaBayar = parseFloat(document.getElementById('sisaBayar').value) || 0;
            const harusDibayar = parseFloat(document.getElementById('harusDibayar').value) || 0;
            const paymentStatus = document.getElementById('paymentStatus').value === 'true';
            
            const data = {
                '_token': document.querySelector('input[name="_token"]').value,
                'nama_konsumen': document.getElementById('select2-nama-konsumen').value,
                'nama_job': document.getElementById('select2-nama-job').value,
                'affiliator_kode': document.getElementById('affiliator_kode_input').value,
                'grand_total': document.getElementById('grandTotalValue').value,
                
                // Data pembayaran
                'dp_amount': dpAmount,
                'sisa_bayar': sisaBayar,
                'harus_dibayar': harusDibayar,
                'payment_status': paymentStatus,
                
                'jenis_order_id': [],
                'kategori_id': [],
                'nama_jenis': [],
                'harga_jual_satuan': [],
                'harga_jual_total': [],
                'speks': [],
                'qty': {},
                'sizes': {}
            };

            // Loop melalui semua orders yang ada data
            let orderIndex = 0;
            Object.values(window.orders).forEach(order => {
                if (order.totalQty > 0) {
                    // Add to arrays
                    data['jenis_order_id'][orderIndex] = order.jenisId;
                    data['kategori_id'][orderIndex] = order.kategoriId;
                    data['nama_jenis'][orderIndex] = order.namaJenis;
                    data['harga_jual_satuan'][orderIndex] = order.hargaSatuan;
                    data['harga_jual_total'][orderIndex] = order.totalHarga;
                    
                    // Convert speks to JSON string
                    const speksJson = JSON.stringify(order.speks || {});
                    data['speks'][orderIndex] = speksJson;
                    
                    // Add to objects
                    data['qty'][order.jenisId] = order.totalQty;
                    data['sizes'][order.jenisId] = order.sizes;
                    
                    orderIndex++;
                }
            });
            
            console.log('Payment data to send:', {
                dp_amount: data.dp_amount,
                sisa_bayar: data.sisa_bayar,
                harus_dibayar: data.harus_dibayar,
                payment_status: data.payment_status
            });
            
            return data;
        }

        // Form submission handler
        document.getElementById('multiOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi
            const hasValidOrders = Object.values(window.orders).some(order => order.totalQty > 0);
            if (!hasValidOrders) {
                showToast('warning', 'Isi minimal satu order dengan qty > 0');
                return;
            }
            
            const namaKonsumen = document.getElementById('select2-nama-konsumen').value;
            const namaJob = document.getElementById('select2-nama-job').value;
            if (!namaKonsumen || !namaJob) {
                showToast('warning', 'Isi Nama Konsumen dan Nama Job');
                return;
            }

            const selectPelanggan = document.getElementById('select2-nama-konsumen');
            const selectedOption = selectPelanggan.options[selectPelanggan.selectedIndex];
            const pelangganId = selectedOption.getAttribute('data-pelanggan-id') || selectedOption.value;
            
            if (!pelangganId) {
                showToast('error', 'Pelanggan tidak valid');
                return;
            }

            // Siapkan data
            const formData = prepareAllData();

            if (!formData.jenis_order_id || formData.jenis_order_id.length === 0) {
                showToast('error', 'Tidak ada data order yang valid');
                return;
            }
            
            // Tampilkan loading
            const submitBtn = document.querySelector('#submitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Menyimpan...';
            submitBtn.disabled = true;
            
            // Kirim ke server
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': formData['_token'],
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    showToast('success', data.message || 'Data berhasil disimpan!');
                    
                    document.getElementById('savedPelangganId').value = data.pelanggan_id || pelangganId;
                    
                    document.getElementById('buttonsBeforeSave').classList.add('hidden');
                    document.getElementById('buttonsAfterSave').classList.remove('hidden');
                    
                    disableFormAfterSave();
                    
                } else {
                    showToast('error', data.message || 'Gagal menyimpan');
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan: ' . error.message);
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // Event listeners untuk tombol lainnya
        const btnCloseAfter = document.getElementById('btnCloseAfter');
        if (btnCloseAfter) {
            btnCloseAfter.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('popupModalOrder').classList.remove('active');
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            });
        }
        
        const btnCancelBefore = document.getElementById('btnCancelBefore');
        if (btnCancelBefore) {
            btnCancelBefore.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('popupModalOrder').classList.remove('active');
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            });
        }

        const btnViewInvoice = document.getElementById('btnViewInvoice');
        if (btnViewInvoice) {
            btnViewInvoice.addEventListener('click', function(e) {
                e.preventDefault();
                viewInvoice();
            });
        }

        const btnViewNota = document.getElementById('btnViewNota');
        if (btnViewNota) {
            btnViewNota.addEventListener('click', function(e) {
                e.preventDefault();
                viewNota();
            });
        }

        const originalCloseModal = window.closeModal;
        window.closeModal = function() {
            // Reset tombol ke state awal
            document.getElementById('buttonsBeforeSave').classList.remove('hidden');
            document.getElementById('buttonsAfterSave').classList.add('hidden');
            
            // Aktifkan kembali semua input
            const form = document.getElementById('multiOrderForm');
            const inputs = form.querySelectorAll('input, select, button, textarea');
            
            inputs.forEach(input => {
                input.disabled = false;
                input.style.opacity = '';
                input.style.cursor = '';
            });
            
            // Aktifkan button kategori
            document.querySelectorAll('.tab-btn, .js-jenis-order-btn').forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '';
                btn.style.cursor = '';
            });
            
            // Reset form jika modal ditutup
            if (confirm('Tutup modal? Semua data yang belum disimpan akan hilang.')) {
                // Reset data
                orders = {};
                document.getElementById('multiOrderForm').reset();
                document.getElementById('activeOrderContainer').innerHTML = '';
                document.getElementById('activeOrderContainer').classList.add('hidden');
                
                // Update UI
                updateGrandTotalUI();
                updateSubmitButton();
                updateJenisOrderButtons(null);
                
                // Panggil fungsi close modal asli
                originalCloseModal();
            }
        };

        // Fungsi untuk ambil warna berdasarkan kategori
        function getCategoryColor(kategoriId) {
            const categoryColors = [
                'bg_green_category',
                'bg_magenta_category',
                'bg_cyan_category',
                'bg_yellow_category',
                'bg_pink_category',
                'bg_teal_category',
            ];
            const colorIndex = (kategoriId - 1) % categoryColors.length;
            return categoryColors[colorIndex];
        }

        // Inisialisasi: buka kategori pertama jika ada
        const firstTab = document.querySelector('.tab-btn');
        if (firstTab) {
            const kategoriId = firstTab.dataset.kategoriId;
            openKategori(kategoriId, firstTab);
        }

        const pakaianInputs = document.querySelectorAll('.js-pakaian-input');
        const pakaianLabels = document.querySelectorAll('.js-pakaian-label');

        pakaianInputs.forEach(input => {
            input.addEventListener('change', function () {
                // Reset semua label
                pakaianLabels.forEach(label => {
                    label.classList.remove('background_gray', 'text-white', 'border_gray');
                    label.classList.add('background_gray_young', 'text-gray-700', 'border_gray_young');
                });

                // Label aktif
                const activeLabel = document.querySelector(`label[for="${this.id}"]`);
                activeLabel.classList.remove('background_gray_young', 'text-gray-700', 'border_gray_young');
                activeLabel.classList.add('background_gray', 'text-white', 'border_gray');

                // Update keterangan berdasarkan jenis_order yang dipilih
                updateKeteranganByJenisOrder(this.value);
            });
        });

        const categoryColors = [
            'bg_green_category',      // Kategori 1: Hijau
            'bg_magenta_category',      // Kategori 2: Magenta
            'bg_cyan_category',      // Kategori 3: Cyan
            'bg_yellow_category',      // Kategori 4: Kuning
            'bg_pink_category',      // Kategori 5: Merah muda
            'bg_teal_category',      // Kategori 6: Teal
        ];

        // Function untuk ambil warna berdasarkan kategori_jenis_order
        window.getCategoryColor = function(kategoriId) {
            const colorIndex = (kategoriId - 1) % categoryColors.length;
            return categoryColors[colorIndex];
        };

        window.updateKeteranganByJenisOrder = function(jenisOrderId) {
            const container = document.getElementById('keteranganContainer');
            const keteranganInput = document.getElementById('keteranganInput');
            const currentKategoriTab = document.querySelector('.tab-btn.background_gray');
            if (!currentKategoriTab) return;

            const kategoriId = currentKategoriTab.getAttribute('onclick').match(/\d+/)[0];
            const boxColor = getCategoryColor(kategoriId);

            // Filter jenis_spek berdasarkan kategori
            const jenisspekForKategori = allJenisSpek.filter(spek => spek.id_kategori_jenis_order == kategoriId);

            // Filter jenis_spek_detail HANYA yang punya relasi dengan jenis_order yang dipilih
            const filteredDetails = allJenisSpekDetail.filter(detail => {
                // Cek apakah detail ada di kategori ini
                if (!jenisspekForKategori.some(spek => spek.id == detail.id_jenis_spek)) {
                    return false;
                }
                // Cek apakah detail punya relasi dengan jenis_order yang dipilih
                return detail.jenis_order && detail.jenis_order.some(jo => jo.id == jenisOrderId);
            });

            if (filteredDetails.length === 0) {
                container.style.display = 'none';
                if (keteranganInput) keteranganInput.value = '';
                return;
            }

            // Tampilkan container
            container.style.display = 'grid';
            container.innerHTML = '';

            // Kelompokkan detail berdasarkan id_jenis_spek
            const groupedBySpek = {};
            filteredDetails.forEach(detail => {
                const spekId = detail.id_jenis_spek;
                if (!groupedBySpek[spekId]) {
                    groupedBySpek[spekId] = [];
                }
                groupedBySpek[spekId].push(detail);
            });

            // Cari nama dan info jenis_spek dari allJenisSpek
            const spekMap = {};
            allJenisSpek.forEach(spek => {
                spekMap[spek.id] = spek;
            });

            // Generate kotak untuk setiap jenis_spek
            Object.keys(groupedBySpek).forEach((spekId) => {
                const spek = spekMap[spekId];
                const spekName = spek ? spek.nama_jenis_spek : 'Spek ' + spekId;
                const details = groupedBySpek[spekId];

                // Buat div kotak dengan warna berdasarkan kategori
                const box = document.createElement('div');
                box.dataset.spekId = spekId;
                box.className = `${boxColor} box_kategori_spek`;

                // Tambah title spek
                const title = document.createElement('p');
                title.className = 'box_kategori_spek_title';
                title.textContent = spekName;
                box.appendChild(title);

                // Buat grid untuk detail
                const detailGrid = document.createElement('div');
                detailGrid.className = 'box_kategori_spek_grid';

                // Tambahkan setiap detail ke grid
                details.forEach((detail) => {
                    const checkboxId = `detail-${detail.id}`;

                    // Hidden checkbox input
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = checkboxId;
                    // Untuk checkbox, gunakan array notation: speks[<jenis_spek_id>][]
                    checkbox.name = `speks[${detail.id_jenis_spek}][]`;
                    checkbox.value = detail.id;
                    checkbox.dataset.label = detail.nama_jenis_spek_detail;
                    checkbox.className = 'hidden';

                    // Button/Label (visual)
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'box_kategori_spek_btn';
                    
                    // Konten button
                    if (detail.gambar) {
                        const img = document.createElement('img');
                        img.src = '/storage/' + detail.gambar;
                        img.className = 'w-12 h-12 object-cover rounded-full mb-1';
                        button.appendChild(img);
                    } else {
                        const noImg = document.createElement('div');
                        noImg.className = 'w-12 h-12 flex items-center justify-center rounded-full bg-white text-[#009A00] text-xs mb-1';
                        noImg.textContent = 'No Img';
                        button.appendChild(noImg);
                    }

                    const span = document.createElement('span');
                    span.className = 'box_kategori_spek_span';
                    span.textContent = detail.nama_jenis_spek_detail;
                    button.appendChild(span);

                    // Event listener pada button
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        
                        // Toggle checkbox
                        checkbox.checked = !checkbox.checked;
                        
                        // Update styling
                        if (checkbox.checked) {
                            button.classList.add('ring-2', 'ring-green-700');
                        } else {
                            button.classList.remove('ring-2', 'ring-green-700');
                        }

                        // Update aggregated keterangan per spek
                        updateKeteranganField();
                    });

                    detailGrid.appendChild(checkbox);
                    detailGrid.appendChild(button);
                });

                box.appendChild(detailGrid);
                container.appendChild(box);
            });
        }
        window.updateKeteranganField = function() {
            const container = document.getElementById('keteranganContainer');
            const keteranganInput = document.getElementById('keteranganInput');
            if (!container || !keteranganInput) return;

            const boxes = Array.from(container.querySelectorAll('div[data-spek-id]'));
            const pairs = [];

            boxes.forEach(box => {
                const spekId = box.dataset.spekId;
                const spekTitleEl = box.querySelector('p');
                const spekName = spekTitleEl ? spekTitleEl.textContent.trim() : `Spek ${spekId}`;
                
                // Ambil SEMUA checkbox yang tercentang untuk spek ini
                const checkedBoxes = box.querySelectorAll(`input[name="speks[${spekId}][]"]:checked`);
                
                if (checkedBoxes.length > 0) {
                    const selectedLabels = Array.from(checkedBoxes).map(cb => 
                        cb.dataset.label || cb.value
                    );
                    pairs.push(`${spekName}: ${selectedLabels.join(', ')}`);
                }
            });

            keteranganInput.value = pairs.join(' | ');
        }

        // Function untuk menampilkan keterangan berdasarkan kategori (tanpa filter jenis_order)
        window.displayKeteranganByKategori = function(kategoriId) {
            const container = document.getElementById('keteranganContainer');
            const keteranganInput = document.getElementById('keteranganInput');
            const boxColor = getCategoryColor(kategoriId);

            // Filter jenis_spek berdasarkan kategori_jenis_order
            const jenisspekForKategori = allJenisSpek.filter(spek => spek.id_kategori_jenis_order == kategoriId);

            // Filter jenis_spek_detail yang ada di kategori ini
            const filteredDetails = allJenisSpekDetail.filter(detail => {
                return jenisspekForKategori.some(spek => spek.id == detail.id_jenis_spek);
            });

            if (filteredDetails.length === 0) {
                container.style.display = 'none';
                if (keteranganInput) keteranganInput.value = '';
                return;
            }

            // Tampilkan container
            container.style.display = 'grid';
            container.innerHTML = '';

            // Kelompokkan detail berdasarkan id_jenis_spek
            const groupedBySpek = {};
            filteredDetails.forEach(detail => {
                const spekId = detail.id_jenis_spek;
                if (!groupedBySpek[spekId]) {
                    groupedBySpek[spekId] = [];
                }
                groupedBySpek[spekId].push(detail);
            });

            // Cari nama dan info jenis_spek dari allJenisSpek
            const spekMap = {};
            allJenisSpek.forEach(spek => {
                spekMap[spek.id] = spek;
            });

            // Generate kotak untuk setiap jenis_spek
            Object.keys(groupedBySpek).forEach((spekId) => {
                const spek = spekMap[spekId];
                const spekName = spek ? spek.nama_jenis_spek : 'Spek ' + spekId;
                const details = groupedBySpek[spekId];

                // Buat div kotak dengan warna berdasarkan kategori
                const box = document.createElement('div');
                box.dataset.spekId = spekId;
                box.className = `${boxColor} box_kategori_spek`;

                // Tambah title spek
                const title = document.createElement('p');
                title.className = 'box_kategori_spek_title';
                title.textContent = spekName;
                box.appendChild(title);

                // Buat grid untuk detail
                const detailGrid = document.createElement('div');
                detailGrid.className = 'box_kategori_spek_grid';

                // Tambahkan setiap detail ke grid
                details.forEach((detail) => {
                    const checkboxId = `detail-${detail.id}`;

                    // Hidden checkbox input
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = checkboxId;
                    checkbox.name = `speks[${detail.id_jenis_spek}][]`;
                    checkbox.value = detail.id;
                    checkbox.dataset.label = detail.nama_jenis_spek_detail;
                    checkbox.className = 'hidden';

                    // Button/Label (visual)
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'box_kategori_spek_btn';
                    
                    // Konten button
                    if (detail.gambar) {
                        const img = document.createElement('img');
                        img.src = '/storage/' + detail.gambar;
                        img.className = 'w-12 h-12 object-cover rounded-full mb-1';
                        button.appendChild(img);
                    } else {
                        const noImg = document.createElement('div');
                        noImg.className = 'w-12 h-12 flex items-center justify-center rounded-full bg-white text-[#009A00] text-xs mb-1';
                        noImg.textContent = 'No Img';
                        button.appendChild(noImg);
                    }

                    const span = document.createElement('span');
                    span.className = 'box_kategori_spek_span';
                    span.textContent = detail.nama_jenis_spek_detail;
                    button.appendChild(span);

                    // Event listener pada button
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        
                        // Toggle checkbox
                        checkbox.checked = !checkbox.checked;
                        
                        // Update styling
                        if (checkbox.checked) {
                            button.classList.add('ring-2', 'ring-green-700');
                        } else {
                            button.classList.remove('ring-2', 'ring-green-700');
                        }

                        // Update aggregated keterangan per spek
                        updateKeteranganField();
                    });

                    detailGrid.appendChild(checkbox);
                    detailGrid.appendChild(button);
                });

                box.appendChild(detailGrid);
                container.appendChild(box);
            });
        }

        console.log('Order system initialized successfully');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPelanggan = document.getElementById('select2-nama-konsumen');
        const inputAffiliator = document.getElementById('affiliator_kode_input');
        
        if (selectPelanggan && inputAffiliator) {
            selectPelanggan.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const affiliateKode = selectedOption.getAttribute('data-affiliate-kode');
                const affiliateNama = selectedOption.getAttribute('data-affiliate-nama');
                const hasAffiliate = selectedOption.getAttribute('data-has-affiliate') === 'true';
                
                if (hasAffiliate && affiliateKode) {
                    // Pelanggan sudah punya affiliate
                    // 1. Isi dengan kode affiliate
                    inputAffiliator.value = affiliateKode;
                    
                    // 2. Buat READONLY dan styling khusus
                    inputAffiliator.readOnly = true;
                    inputAffiliator.style.backgroundColor = '#f0f0f0';
                    inputAffiliator.style.cursor = 'not-allowed';
                    inputAffiliator.style.borderColor = '#ccc';
                    inputAffiliator.title = "Kode Sales sudah terhubung dengan pelanggan ini";
                    
                } else {
                    // Pelanggan TIDAK punya affiliate
                    // 1. Kosongkan input
                    inputAffiliator.value = '';
                    
                    // 2. Buat EDITABLE dan styling normal
                    inputAffiliator.readOnly = false;
                    inputAffiliator.style.backgroundColor = '';
                    inputAffiliator.style.cursor = '';
                    inputAffiliator.style.borderColor = '';
                    inputAffiliator.title = "";
                    inputAffiliator.placeholder = "Kode Sales (Opsional)";
                }
            });
            
            // Optional: Tambahkan icon lock untuk visual feedback
            const style = document.createElement('style');
            style.textContent = `
                .affiliate-input[readonly] {
                    background-color: #f0f0f0 !important;
                    cursor: not-allowed !important;
                    border-color: #ccc !important;
                    color: #555 !important;
                    position: relative;
                    padding-right: 35px;
                }
                .affiliate-input[readonly]::after {
                    content: "🔒";
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    font-size: 14px;
                    opacity: 0.6;
                }
            `;
            document.head.appendChild(style);
        }
        
        // Select2 version dengan styling lebih baik
        if (typeof $.fn.select2 !== 'undefined' && $('#select2-nama-konsumen').length) {
            $(document).ready(function() {
                $('#select2-nama-konsumen').select2({
                    placeholder: "Pilih Nama Konsumen...",
                    allowClear: true,
                    width: '100%',
                    tags: true,
                });
                
                // Styling untuk input readonly
                $('<style>').text(`
                    .affiliate-locked {
                        background-color: #f5f5f5 !important;
                        border-color: #ddd !important;
                        color: #666 !important;
                        cursor: not-allowed !important;
                        position: relative;
                        padding-right: 35px;
                    }
                    .affiliate-locked::before {
                        content: "🔒";
                        position: absolute;
                        right: 10px;
                        top: 50%;
                        transform: translateY(-50%);
                        font-size: 14px;
                        opacity: 0.6;
                    }
                    .affiliate-info {
                        font-size: 11px;
                        color: #666;
                        margin-top: 3px;
                        display: flex;
                        align-items: center;
                        gap: 5px;
                    }
                    .affiliate-info span {
                        background: #e9ecef;
                        padding: 1px 6px;
                        border-radius: 3px;
                        font-size: 10px;
                    }
                `).appendTo('head');
                
                $('#select2-nama-konsumen').on('change', function() {
                    const selectedOption = $(this).find('option:selected');
                    const affiliateKode = selectedOption.data('affiliate-kode');
                    const affiliateNama = selectedOption.data('affiliate-nama');
                    const hasAffiliate = selectedOption.data('has-affiliate');
                    const $input = $('#affiliator_kode_input');
                    const $container = $input.parent();
                    
                    // Hapus info sebelumnya
                    $container.find('.affiliate-info').remove();
                    
                    if (hasAffiliate && affiliateKode) {
                        // READONLY - pelanggan sudah punya affiliate
                        $input.val(affiliateKode)
                            .addClass('affiliate-locked')
                            .prop('readonly', true)
                            .attr('title', `Kode affiliate terikat dengan pelanggan ini`);
                        
                    } else {
                        // EDITABLE - pelanggan tidak punya affiliate
                        $input.val('')
                            .removeClass('affiliate-locked')
                            .prop('readonly', false)
                            .attr('title', '')
                            .attr('placeholder', 'Kode Sales (Opsional)');
                    }
                });
                
                // Optional: Prevent user from editing readonly field
                $('#affiliator_kode_input').on('keydown', function(e) {
                    if ($(this).prop('readonly')) {
                        e.preventDefault();
                        return false;
                    }
                });
            });
        }
    });
</script>

<script>
    function openOrderModal() {
        console.log('Opening order modal...');
        
        // Tampilkan modal terlebih dahulu
        document.getElementById('popupModalOrder').classList.add('active');
        
        // Tunggu sejenak baru reset (agar DOM sudah siap)
        setTimeout(() => {
            resetOrderModal();
        }, 100);
        
        // Inisialisasi Select2 jika belum
        if (typeof $.fn.select2 !== 'undefined') {
            $('#select2-nama-konsumen').select2({
                placeholder: "Ketik atau pilih nama konsumen",
                tags: true,
                allowClear: true,
                width: '100%'
            });
            
            $('#select2-nama-job').select2({
                placeholder: "Ketik atau pilih nama job",
                tags: true,
                allowClear: true,
                width: '100%'
            });
        }
    }

    // Fungsi untuk reset modal ke state awal
    function resetOrderModal() {
        console.log('Resetting modal state...');
        
        // 1. Reset tampilan tombol - PERIKSA ELEMEN ADA
        const buttonsBeforeSave = document.getElementById('buttonsBeforeSave');
        const buttonsAfterSave = document.getElementById('buttonsAfterSave');
        
        if (buttonsBeforeSave) {
            buttonsBeforeSave.classList.remove('hidden');
        }
        if (buttonsAfterSave) {
            buttonsAfterSave.classList.add('hidden');
        }
        
        // 2. Reset hidden pelanggan ID - PERIKSA DULU
        const savedPelangganId = document.getElementById('savedPelangganId');
        if (savedPelangganId) {
            savedPelangganId.value = '';
        }
        
        // 3. Reset form - PERIKSA DULU
        const form = document.getElementById('multiOrderForm');
        if (form) {
            form.reset();
        }
        
        // 4. Reset data order
        if (typeof window.orders !== 'undefined') {
            window.orders = {};
        }
        
        // 5. Clear container aktif
        const activeContainer = document.getElementById('activeOrderContainer');
        if (activeContainer) {
            activeContainer.innerHTML = '';
            activeContainer.classList.add('hidden');
        }
        
        // 6. Reset UI - PERIKSA FUNGSI ADA
        if (typeof updateGrandTotalUI === 'function') {
            updateGrandTotalUI();
        }
        
        if (typeof updateSubmitButton === 'function') {
            updateSubmitButton();
        }
        
        if (typeof updateJenisOrderButtons === 'function') {
            updateJenisOrderButtons(null);
        }
        
        // 7. Reset payment section
        const paymentSection = document.getElementById('paymentSection');
        if (paymentSection) {
            paymentSection.classList.add('hidden');
        }
        
        const paymentInput = document.getElementById('paymentAmount');
        if (paymentInput) {
            paymentInput.value = '';
        }
        
        // 8. Reset payment hidden inputs
        const dpAmount = document.getElementById('dpAmount');
        const sisaBayar = document.getElementById('sisaBayar');
        const harusDibayar = document.getElementById('harusDibayar');
        const paymentStatus = document.getElementById('paymentStatus');
        
        if (dpAmount) dpAmount.value = '0';
        if (sisaBayar) sisaBayar.value = '0';
        if (harusDibayar) harusDibayar.value = '0';
        if (paymentStatus) paymentStatus.value = 'false';
        
        // 9. Reset payment mode
        if (typeof window.paymentMode !== 'undefined') {
            window.paymentMode = null;
        }
        
        // 10. Aktifkan semua input
        const inputs = document.querySelectorAll('#multiOrderForm input, #multiOrderForm select, #multiOrderForm button, #multiOrderForm textarea');
        inputs.forEach(input => {
            if (input.id !== 'btnCloseAfter' && 
                input.id !== 'btnViewInvoice' && 
                input.id !== 'btnViewNota') {
                input.disabled = false;
                input.style.opacity = '';
                input.style.cursor = '';
                input.classList.remove('disabled');
            }
        });
        
        // 11. Aktifkan button kategori
        const kategoriButtons = document.querySelectorAll('.tab-btn, .js-jenis-order-btn');
        kategoriButtons.forEach(btn => {
            btn.disabled = false;
            btn.style.opacity = '';
            btn.style.cursor = '';
            btn.classList.remove('disabled');
        });
        
        // 12. Reset kategori tab ke awal
        const firstTab = document.querySelector('.tab-btn');
        if (firstTab && typeof openKategori === 'function') {
            const kategoriId = firstTab.dataset.kategoriId || firstTab.getAttribute('data-kategori-id');
            if (kategoriId) {
                openKategori(kategoriId, firstTab);
            }
        }
        
        console.log('Modal reset complete');
    }

    function closeOrderModal() {
        const modal = document.getElementById('popupModalOrder');
        if (modal) {
            modal.classList.remove('active');
        }
        
        // Refresh halaman setelah modal tertutup
        setTimeout(() => {
            window.location.reload();
        }, 300);
    }

    // Pindahkan event listener untuk tombol close di dalam modal
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing order modal...');
        
        // Pastikan tombol buka modal bekerja
        const openModalBtn = document.querySelector('[onclick*="openOrderModal"]');
        if (openModalBtn) {
            openModalBtn.onclick = openOrderModal;
        }
        
        // Tombol close (X) di modal
        const closeBtn = document.querySelector('#popupModalOrder .dashboard_popup_order_heading button');
        if (closeBtn) {
            closeBtn.onclick = closeOrderModal;
        }
        
        // Tombol batal sebelum save
        const cancelBtn = document.getElementById('btnCancelBefore');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeOrderModal();
            });
        }
        
        // Tombol tutup setelah save
        const closeAfterBtn = document.getElementById('btnCloseAfter');
        if (closeAfterBtn) {
            closeAfterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeOrderModal();
            });
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Debug: cek apakah modal ada
        console.log('Modal element:', document.getElementById('deleteOrderModal'));
        
        // Modal untuk hapus order
        const deleteOrderModal = document.getElementById('deleteOrderModal');
        const confirmDeleteOrderBtn = document.getElementById('confirmDeleteOrderBtn');
        const cancelDeleteOrderBtn = document.getElementById('cancelDeleteOrderBtn');
        let currentOrderId = null;
        let currentOrderElement = null;

        // Event listener untuk tombol hapus order - DELEGATION
        document.addEventListener("click", function(e) {
            if (e.target.closest('.btn-delete')) {
                e.preventDefault();
                e.stopPropagation();
                
                const button = e.target.closest('.btn-delete');
                currentOrderId = button.dataset.id;
                currentOrderElement = button.closest('tr');
                
                // Tampilkan nama order di modal jika ada
                const orderName = button.dataset.nama || '';
                const nameElement = document.getElementById('orderNameToDelete');
                if (nameElement && orderName) {
                    nameElement.textContent = orderName;
                }
                
                // Tampilkan modal
                if (deleteOrderModal) {
                    deleteOrderModal.style.display = "block";
                    console.log('Modal ditampilkan, Order ID:', currentOrderId);
                } else {
                    console.error('Modal tidak ditemukan!');
                }
            }
        });

        // Event listener untuk tombol batal di modal
        if (cancelDeleteOrderBtn) {
            cancelDeleteOrderBtn.onclick = function(e) {
                e.preventDefault();
                deleteOrderModal.style.display = "none";
                currentOrderId = null;
                currentOrderElement = null;
                console.log('Modal ditutup (batal)');
            }
        }

        // Event listener untuk tombol hapus di modal
        if (confirmDeleteOrderBtn) {
            confirmDeleteOrderBtn.onclick = function(e) {
                e.preventDefault();
                
                if (!currentOrderId) {
                    console.error('Tidak ada order ID!');
                    return;
                }
                
                // Tutup modal
                deleteOrderModal.style.display = "none";
                console.log('Menghapus order ID:', currentOrderId);
                
                // Kirim request DELETE
                fetch(`/dashboard/orders/${currentOrderId}`, {
                    method: "DELETE", // Gunakan DELETE langsung
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    }
                })
                .then(res => {
                    console.log('Response status:', res.status);
                    
                    // Periksa jika response OK
                    if (!res.ok) {
                        return res.json().then(err => {
                            throw new Error(err.message || "Gagal menghapus order");
                        });
                    }
                    
                    return res.json(); // Selalu parse sebagai JSON
                })
                .then(data => {
                    console.log('Delete success:', data);
                    
                    // 1. Hapus baris dari tabel jika ada
                    if (currentOrderElement && currentOrderElement.parentNode) {
                        currentOrderElement.remove();
                        console.log('Baris dihapus dari tabel');
                    }
                    
                    // 2. Update nomor urut
                    updateIterationNumbers();
                    
                    // 3. Update totals dengan data dari server
                    if (data && data.totals) {
                        console.log('Updating totals with:', data.totals);
                        updateTotals(data.totals);
                    } else {
                        console.warn('No totals data in response');
                        // Fallback: reload halaman jika data tidak ada
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                    
                    // 4. Tampilkan notifikasi sukses
                    showToast(data.message || "Order berhasil dihapus!", 'success');
                })
                .catch(err => {
                    console.error("Detail error:", err);
                    // Tampilkan notifikasi error
                    showToast(err.message || "Terjadi kesalahan saat menghapus order!", 'error');
                    
                    // Jika error, reload halaman untuk sinkronisasi
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                })
                .finally(() => {
                    currentOrderId = null;
                    currentOrderElement = null;
                });
            };
        }

        // Fungsi untuk update nomor urut
        function updateIterationNumbers() {
            const rows = document.querySelectorAll('#orders-table-body tr');
            
            rows.forEach((row, index) => {
                const iterationCell = row.querySelector('.iteration-number');
                if (iterationCell) {
                    iterationCell.textContent = index + 1;
                }
            });
            
            console.log('Iteration numbers updated, total rows:', rows.length);
        }

        // Fungsi untuk update totals
        function updateTotals(totals) {
            console.log('Updating totals:', totals);
            
            // Mapping untuk elemen HTML
            const mappings = {
                'total_qty': '#total_qty b',
                'total_hari': '#total_hari b',
                'total_deadline': '#total_deadline b',
                'total_setting': '#total_setting b',
                'total_sisa_setting': '#total_sisa_setting b', // Tambahkan jika ada
                'total_print': '#total_print b',
                'total_sisa_print': '#total_sisa_print b',
                'total_press': '#total_press b',
                'total_sisa_press': '#total_sisa_press b',
                'total_cutting': '#total_cutting b',
                'total_sisa_cutting': '#total_sisa_cutting b',
                'total_jahit': '#total_jahit b',
                'total_sisa_jahit': '#total_sisa_jahit b',
                'total_finishing': '#total_finishing b',
                'total_sisa_finishing': '#total_sisa_finishing b',
                'total_packing': '#total_packing b',
                'total_sisa_packing': '#total_sisa_packing b'
            };
            
            // Update setiap elemen
            Object.entries(mappings).forEach(([key, selector]) => {
                const element = document.querySelector(selector);
                if (element) {
                    // Format angka jika perlu
                    let value = totals[key] || 0;
                    
                    // Untuk nilai float (hari/deadline), tampilkan 1 desimal
                    if (key === 'total_hari' || key === 'total_deadline') {
                        value = parseFloat(value).toFixed(1);
                    }
                    
                    element.textContent = value;
                    console.log(`Updated ${selector}: ${value}`);
                } else {
                    console.warn(`Element not found: ${selector}`);
                }
            });
        }

        // Jika klik di luar modal, tutup modal
        window.addEventListener('click', function(event) {
            if (event.target == deleteOrderModal) {
                deleteOrderModal.style.display = "none";
                currentOrderId = null;
                currentOrderElement = null;
                console.log('Modal ditutup (klik luar)');
            }
        });

        // Fungsi toast notification
        function showToast(message, type = 'success', duration = 3000) {
            // Hapus toast sebelumnya
            const existingToasts = document.querySelectorAll('.notification-toast');
            existingToasts.forEach(toast => {
                toast.remove();
            });
            
            // Buat elemen toast
            const toast = document.createElement('div');
            toast.className = `notification-toast toast-${type}`;
            
            // Icon berdasarkan type
            const icons = {
                success: '✓',
                error: '✗',
                warning: '⚠',
                info: 'ℹ'
            };
            
            toast.innerHTML = `
                <div class="toast-content ${type}">
                    <span class="toast-icon">${icons[type] || icons.success}</span>
                    <span class="toast-message">${message}</span>
                    <button class="toast-close" onclick="this.closest('.notification-toast').remove()">&times;</button>
                </div>
            `;
            
            // Tambahkan styling inline untuk toast
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            
            document.body.appendChild(toast);
            
            // Tampilkan dengan animasi
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            }, 10);
            
            // Auto remove setelah duration
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, duration);
        }
        
        // Tambahkan CSS untuk toast
        const toastStyle = document.createElement('style');
        toastStyle.textContent = `
            .toast-content {
                background: white;
                padding: 12px 16px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                min-width: 250px;
                border-left: 4px solid;
            }
            .toast-success .toast-content {
                border-left-color: #10b981;
                background-color: #f0fdf4;
                color: #065f46;
            }
            .toast-error .toast-content {
                border-left-color: #ef4444;
                background-color: #fef2f2;
                color: #991b1b;
            }
            .toast-warning .toast-content {
                border-left-color: #f59e0b;
                background-color: #fffbeb;
                color: #92400e;
            }
            .toast-info .toast-content {
                border-left-color: #3b82f6;
                background-color: #eff6ff;
                color: #1e40af;
            }
            .toast-icon {
                font-weight: bold;
                margin-right: 10px;
                font-size: 16px;
            }
            .toast-message {
                flex: 1;
                font-size: 14px;
            }
            .toast-close {
                background: none;
                border: none;
                font-size: 20px;
                cursor: pointer;
                margin-left: 10px;
                color: inherit;
                opacity: 0.7;
                line-height: 1;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .toast-close:hover {
                opacity: 1;
            }
        `;
        document.head.appendChild(toastStyle);

        // Export ke global scope jika perlu
        window.showToast = showToast;
    });
</script>
<style>
    .tab-btn {
        padding: .4vw .66vw;
        border: .12vw solid rgb(153, 153, 153);
        background: #f3f4f6;
        color: #6b7280;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: .4vw;
        position: relative;
    }
    .active-order-form {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    #activeOrderContainer {
        background: white;
        border-radius: 0.75rem;
    }

    .active-order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom:.4vw;
        border-bottom: .1vw solid rgb(153, 153, 153);
    }

    .active-order-title {
        font-weight: 700;
        color: #1e40af;
        margin: 0;
        font-size: 1.5rem;
    }

    .order-status-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #dbeafe;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        color: #1e40af;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #3b82f6;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .dashboard_popup_order_size_box {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .dashboard_popup_order_size_box label {
        margin-bottom: 0.25rem;
        font-weight: 600;
        color: #4b5563;
    }

    .dashboard_popup_order_size_box input {
        width: 100%;
        text-align: center;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .tab-btn:hover {
        background: #e5e7eb;
        color: #4b5563;
    }

    .tab-btn.tab-active {
        background: #3b82f6 !important;
        color: white !important;
        border-color: #3b82f6 !important;
        position: relative;
        z-index: 10;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
    }

    .tab-btn.tab-inactive {
        background: #f9fafb;
        color: #9ca3af;
        border-bottom: 1px solid rgb(153, 153, 153);
    }

    /* ===== STYLING UNTUK BUTTON JENIS ORDER ===== */
    .js-jenis-order-btn {
        position: relative;
        padding: .55vw .66vw;
        border: .12vw solid #919294ff;
        background: #f9fafb;
        color: #4b5563;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: .6vw;
        text-align: center;
    }

    .js-jenis-order-btn:hover {
        background: #f3f4f6;
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Button jenis order aktif (sedang diedit) */
    .js-jenis-order-btn.jenis-active {
        background: #3b82f6 !important;
        color: white !important;
        border-color: #3b82f6 !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px -3px rgba(59, 130, 246, 0.4);
    }

    /* Button jenis order yang sudah ada data */
    .js-jenis-order-btn.jenis-has-order {
        background: transparent !important;
        color: black !important;
        border: .12vw solid #919294ff;
    }

    .js-jenis-order-btn.jenis-has-order:hover {
        border-color: #3b82f6 !important;
    }
    .js-jenis-order-btn.jenis-active.jenis-has-order{
        background: #3b82f6 !important;
        color: white !important;
        border-color: #3b82f6 !important;
    }

    /* Badge untuk menunjukkan jumlah qty */
    .order-badge {
        position: absolute;
        top: -.7vw;
        right: -.7vw;
        background: white;
        color: #10b981;
        width:max-content;
        height:1.8vw;
        border-radius: 100vw;
        font-size: .8vw;
        font-weight: 700;
        padding:0 .3vw;
        display: none;
        align-items: center;
        justify-content: center;
        border: .12vw solid #10b981;
        z-index: 10;
    }

    /* ===== CONTAINER UNTUK FORM ORDER AKTIF ===== */
    #activeOrderContainer {
        background: white;
        border-radius: 0.75rem;
    }

    .active-order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: .3vw;
        margin-bottom: .3vw;
        border-bottom: .12vw solid #e5e7eb;
    }

    .active-order-title {
        font-weight: 700;
        color: #1e40af;
        margin: 0;
        font-size: 1.5rem;
    }

    .order-status-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #dbeafe;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        color: #1e40af;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #3b82f6;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .dashboard_popup_order_size_box {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .dashboard_popup_order_size_box label {
        margin-bottom: 0.25rem;
        font-weight: 600;
        color: #4b5563;
    }

    .dashboard_popup_order_size_box input {
        width: 100%;
        text-align: center;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .dashboard_popup_order_size_box input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .total-qty-display {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction:column;
        border-radius: .23vw;
        padding: .3vw .7vw;
        background-color: rgb(31 41 55);
        border: .1vw solid rgb(168, 168, 168);
        font-weight: 600;
        color: #ffffffff;
    }

    .total-qty-display span {
        font-size: .85vw;
        color: #ffffffff;
    }

    /* ===== TOTAL HARGA ORDER INI ===== */
    .total-harga-order {
        background: #f8fafc;
        display:flex;
        gap:1vw;
    }

    .harga-row {
        display: flex;
        align-items: center;
        justify-content:flex-start;
        gap:.4vw;
        border:.12vw solid rgb(153, 153, 153);
        border-radius:.4vw;
        padding: .6vw 1vw;
        width:100%;
        margin-bottom:1vw;
    }
    .harga-row span{
        font-size:.95vw;
    }

    .harga-satuan-display {
        color: #059669;
        font-weight: 600;
    }

    .harga-total-display {
        color: #1d4ed8;
        font-weight: 700;
    }

    #grandTotalDisplay{
        color: #05f519ff;
        font-weight:800;
    }

    .grand-total-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .grand-total-header h4 {
        margin: 0;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .total-icon {
        font-size: 1.5em;
    }

    .grand-total-amount {
        text-align: right;
    }

    .grand-total-amount span {
        font-size: 2rem;
        font-weight: 800;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .total-order-count {
        text-align: right;
        font-size: 0.875rem;
        opacity: 0.9;
    }

    /* ===== SUBMIT BUTTON ===== */
    #submitBtn {
        background: #6b7280;
        cursor: not-allowed;
    }

    #submitBtn.btn-active {
        background: #10b981;
        cursor: pointer;
    }

    #submitBtn.btn-active:hover {
        background: #059669;
    }
    #buttonsAfterSave {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Styling untuk tombol disabled */
    input:disabled, 
    select:disabled, 
    button:disabled:not(.success-buttons) {
        background-color: #f5f5f5 !important;
        border-color: #ddd !important;
        color: #999 !important;
        cursor: not-allowed !important;
    }

    /* Badge untuk tombol sukses */
    .success-buttons {
        position: relative;
    }

    .success-buttons::after {
        content: "✓";
        position: absolute;
        right: -8px;
        top: -8px;
        background: #10b981;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    /* Highlight untuk tombol invoice dan nota */
    #btnViewInvoice:hover, #btnViewNota:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease;
    }
    /* ===== RESPONSIF ===== */
    @media (max-width: 768px) {
        
        .grand-total-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .grand-total-amount {
            text-align: left;
            width: 100%;
        }
        
        .js-jenis-order-btn {
            min-width: 100px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
    }
</style>

<style>
    .payment-section {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .payment-label {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .payment-input-container {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        position: relative;
    }

    .payment-currency {
        position: absolute;
        left: 12px;
        font-weight: 600;
        color: #666;
        z-index: 1;
    }

    .payment-input {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .payment-input:focus {
        border-color: #4CAF50;
        outline: none;
        box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
    }

    .payment-info {
        background-color: white;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
    }

    .payment-info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .payment-info-row:last-child {
        border-bottom: none;
    }

    .payment-dp {
        color: #2196F3;
        font-weight: 600;
    }

    .payment-sisa {
        color: #FF9800;
        font-weight: 600;
    }

    .payment-lunas {
        color: #4CAF50;
        font-weight: 600;
    }

    .payment-buttons {
        display: flex;
        justify-content:flex-end;
        align-items:center;
        gap:.8vw;
    }

    .payment-btn {
        padding: .44vw 1vw !important;
        border: none;
        border-radius: .6vw !important;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
        color:white !important;
        font-size:.9vw !important; 
    }

    .payment-btn-lunas {
        background-color: #4CAF50 !important;
        color: white;
    }

    .payment-btn-lunas:hover {
        background-color: #45a049 !important;
    }

    .payment-btn-dp {
        background-color: #2196F3 !important;
        color: white;
    }

    .payment-btn-dp:hover {
        background-color: #0b7dda !important;
    }

    .popup_order_pay{
        display:flex;
        flex-direction:column;
        align-items: self-start;
        justify-content: flex-start;
    }

    .hidden {
        display: none !important;
    }
</style>

<div class="dashboard_wrapper progress_custom">
    <div class="dashboard_banner">
        <img src="{{ asset('images/logo_bugevile_2.png') }}" alt="Logo">

        <div class="dashboard_banner_btn">
            <button onclick="openOrderModal()">
                Tambah Pesanan
            </button>
            <div class="dashboard_banner_select">
                <label>Pilih Job Lain</label>
                <select id="selectJob">
                    <option value="Progress Keseluruhan">Progress Keseluruhan</option>
                    @foreach ($orders as $o)
                        @php
                            $sisaPacking = $o->sisa_packing ?? 0;
                            $statusText = $sisaPacking == 0 ? '✅' : '❌';
                        @endphp
                        <option value="{{ $o->slug }}" {{ $o->slug == $job->slug ? 'selected' : '' }}>{{ $o->nama_job }} {{ optional($o->jenisOrder)->nama_jenis ?? '' }}  - {{ $o->nama_konsumen }} {{ $statusText }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="dashboard_content">
        <div class="dashboard_top_progress">
            <div class="dashboard_top_progress_card">
                <h2 id="nama_job">{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }}</h2>
                <p id="qty">{{ $job->qty }}</p>
            </div>

            <div class="dashboard_top_progress_card">
                <h2>Lilana Migawean</h2>
                <p id="hari">{{ (float) $job->hari }} Poe</p>
            </div>

            <div class="dashboard_top_progress_card">
                <h2>
                    {{ $job->sisa_packing == 0 ? 'Pagawean Beres' : 'Kudu Beresna' }}
                </h2>

                <p id="deadline">
                    @if ($job->sisa_packing == 0)
                        BERES
                    @elseif ($job->sisa_jahit == 0)
                        {{ number_format($job->qty / 25, 1) }} POE DEUI
                    @else
                        {{ (float) $job->deadline }} POE DEUI
                    @endif
                </p>
            </div>



        </div>

        <div class="dashboard_bottom_progress">
            <div class="dashboard_bottom_progress_layout">

            <!-- Total Setting -->
            <div class="dashboard_bottom_progress_card">
                <button onclick="openModalSetting()" class="dashboard_bottom_progress_btn dashboard_setting_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                <button onclick="openHistory('Setting', this, {{ $job->id }})" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_setting_card">
                    <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                </button>
                <h2>Setting</h2>

                <p id="setting">
                    @if ($job->setting == 1)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        <img src="{{ asset('icons/close-icon.svg') }}" alt="Icon">
                    @endif
                </p>

                <span class="dashboard_setting_card">
                    {{ $job->setting == 1 ? 'Selesai' : 'Belum' }}
                </span>

                <h5>
                    {{ $job->latestSettingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                $isComplete = $job->setting == 1;
                
                $jsAction = $isComplete 
                    ? 'openModal()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Print -->
            <div class="dashboard_bottom_progress_card">
                <button onclick="{{ $jsAction }}" class="dashboard_bottom_progress_btn dashboard_print_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                <button onclick="openHistory('Print', this, {{ $job->id }})" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_print_card">
                    <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                </button>
                <h2>Print</h2>
                <p id="print">
                    @if ($job->sisa_print == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->print }}
                    @endif
                </p>
                <span 
                    class="dashboard_print_card"
                    id="sisa_print"
                >
                    {{ $job->sisa_print == 0 ? 'Selesai' : 'Proses'}}
                </span>

                <h5>
                    {{ $job->latestPrintHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionPress = $isComplete 
                    ? 'openModalPress()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Press -->
            <div class="dashboard_bottom_progress_card">
                <button onclick="{{ $jsActionPress }}" class="dashboard_bottom_progress_btn dashboard_press_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                <button onclick="openHistory('Press', this, {{ $job->id }})" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_press_card">
                    <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                </button>
                <h2>Press</h2>
                <p id="press">
                    @if ($job->sisa_press == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->press }}
                    @endif
                </p>
                <span class="dashboard_press_card" id="sisa_press">{{ $job->sisa_press == 0 ? 'Selesai' : 'Proses'}}</span>
                <h5>
                    {{ $job->latestPressHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionCutting = $isComplete 
                    ? 'openModalCutting()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Cutting -->
            <div class="dashboard_bottom_progress_card">
                <button onclick="{{ $jsActionCutting }}" class="dashboard_bottom_progress_btn dashboard_cutting_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                <button onclick="openHistory('Cutting', this, {{ $job->id }})" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_cutting_card">
                    <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                </button>
                <h2>Cutting</h2>
                <p id="cutting">
                    @if ($job->sisa_cutting == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->cutting }}
                    @endif
                </p>
                <span class="dashboard_cutting_card" id="sisa_cutting">{{ $job->sisa_cutting == 0 ? 'Selesai' : 'Proses' }}</span>
                <h5>
                    {{ $job->latestCuttingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionJahit = $isComplete 
                    ? 'openModalJahit()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Jahit -->
            <div class="dashboard_bottom_progress_card">
                <button onclick="{{ $jsActionJahit }}" class="dashboard_bottom_progress_btn dashboard_jahit_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                <button onclick="openHistory('Jahit', this, {{ $job->id }})" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_jahit_card">
                    <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                </button>
                <h2>Jahit</h2>
                <p id="jahit">
                    @if ($job->sisa_jahit == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->jahit }}
                    @endif
                </p>
                <span class="dashboard_jahit_card" id="sisa_jahit">{{ $job->sisa_jahit == 0 ? 'Selesai' : 'Proses'}}</span>
                <h5>
                    {{ $job->latestJahitHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionFinishing = $isComplete 
                    ? 'openModalFinishing()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Finishing -->
            <div class="dashboard_bottom_progress_card">
                <button onclick="{{ $jsActionFinishing }}" class="dashboard_bottom_progress_btn dashboard_cutting_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                <button onclick="openHistory('Finishing', this, {{ $job->id }})" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_cutting_card">
                    <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                </button>
                <h2>Finishing</h2>
                <p id="finishing">
                    @if ($job->sisa_finishing == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->finishing }}
                    @endif
                </p>
                <span class="dashboard_cutting_card" id="sisa_finishing">{{ $job->sisa_finishing == 0 ? 'Selesai' : 'Proses'}}</span>
                <h5>
                    {{ $job->latestFinishingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>

            @php
                // Tentukan apakah Job Setting sudah selesai (1)
                $isComplete = $job->setting == 1;
                
                // Tentukan aksi JavaScript dan style
                $jsActionPacking = $isComplete 
                    ? 'openModalPacking()' // Jika selesai (1), buka modal
                    : "alert('Setting belum selesai')"; // Jika belum (0), tampilkan alert
            @endphp
            <!-- Total Packing -->
            <div class="dashboard_bottom_progress_card">
                <button onclick="{{ $jsActionPacking }}" class="dashboard_bottom_progress_btn dashboard_jahit_card"><img src="{{ asset('icons/plus-icon.svg') }}" alt="Icon"></button>
                <button onclick="openHistory('Packing', this, {{ $job->id }})" class="dashboard_bottom_progress_btn dashboard_bottom_progress_btn_history dashboard_jahit_card">
                    <img src="{{ asset('icons/history-icon.svg') }}" alt="Icon">
                </button>
                <h2>Packing</h2>
                <p id="packing">
                    @if ($job->sisa_packing == 0)
                        <img src="{{ asset('icons/check-icon.svg') }}" alt="Icon">
                    @else
                        {{ $job->packing }}
                    @endif
                </p>
                <span class="dashboard_jahit_card" id="sisa_packing">{{ $job->sisa_packing == 0 ? 'Selesai' : 'Proses' }}</span>
                <h5>
                    {{ $job->latestPackingHistory->pegawai->nama ?? 'Belum' }}
                </h5>
            </div>
</div>
</div>
    </div>
</div>

<div id="popupModalOrder" class="dashboard_popup_order popup_custom">
    <div class="dashboard_popup_order_wrapper">
        <div class="dashboard_popup_order_box">
            <!-- FORM -->
            <form method="POST" action="{{ route('orders.store') }}" id="multiOrderForm">
                @csrf

                <!-- Nama Konsumen --> 
                <div class="form_field">
                    <div class="dashboard_popup_order_heading">
                        <label>Nama Konsumen</label>
                        <button onclick="closeModal()" type="button"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
                    </div>
                    <div class="dashboard_popup_order_konsumen">
                        <select id="select2-nama-konsumen" name="nama_konsumen" required>
                            <option value="" selected disabled>Pilih Nama Konsumen...</option>
                            @foreach ($pelanggans as $pelanggan)
                                @php
                                    $affiliateKode = $pelanggan->affiliate ? $pelanggan->affiliate->kode : null;
                                    $affiliateNama = $pelanggan->affiliate ? $pelanggan->affiliate->nama : null;
                                @endphp
                                <option value="{{ $pelanggan->id }}" 
                                    data-pelanggan-id="{{ $pelanggan->id }}"
                                    @if($affiliateKode)
                                        data-affiliate-kode="{{ $affiliateKode }}"
                                        data-affiliate-nama="{{ $affiliateNama }}"
                                        data-has-affiliate="true"
                                    @else
                                        data-has-affiliate="false"
                                    @endif>
                                    {{ $pelanggan->nama }}
                                    @if($affiliateNama)
                                        (Sales : {{ $affiliateNama }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div></div>
                        
                        <input type="text" name="affiliator_kode" id="affiliator_kode_input" 
                            class="input_form affiliate-input" 
                            placeholder="Kode Sales (Opsional)" value="">
                    </div>
                </div>

                <!-- Nama Job -->
                <div class="form_field">
                    <select id="select2-nama-job" name="nama_job" required>
                        <option value="" selected disabled>Pilih Nama Job...</option>
                        @foreach ($jobs as $a)
                            <option value="{{ $a->nama_job }}">{{ $a->nama_job }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- TAB KATEGORI -->
                <div class="form_field">
                    <div class="dashboard_popup_order_kategori">
                        @foreach ($kategoriList as $kategori)
                            <button type="button"
                                onclick="openKategori({{ $kategori->id }}, this)"
                                class="tab-btn dashboard_popup_order_kategori_btn"
                                data-kategori-id="{{ $kategori->id }}">
                                {{ $kategori->nama }}
                            </button>
                        @endforeach
                    </div>
                    
                    <!-- JENIS ORDER PER KATEGORI -->
                    @foreach ($kategoriList as $kategori)
                        <div class="kategori-wrapper hidden" id="kategori-{{ $kategori->id }}">
                            <div class="dashboard_popup_order_kategori_layout">
                                @foreach ($jenisOrders->where('id_kategori_jenis_order', $kategori->id) as $jenis)
                                    <button type="button"
                                        class="js-jenis-order-btn btn_kategori_order background_gray_young"
                                        data-jenis-id="{{ $jenis->id }}"
                                        data-kategori-id="{{ $kategori->id }}"
                                        data-nama-jenis="{{ $jenis->nama_jenis }}"
                                        data-harga-jual="{{ $jenis->harga_jual }}">
                                        {{ $jenis->nama_jenis }}
                                        <span class="order-badge" id="badge-{{ $jenis->id }}"></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- CONTAINER UNTUK FORM ORDER YANG AKTIF -->
                <div id="activeOrderContainer" class="hidden">
                    <!-- Hanya satu form order aktif yang akan ditampilkan di sini -->
                </div>

                <!-- Template untuk form order aktif -->
                <template id="orderFormTemplate">
                    <div class="active-order-form" data-jenis-id="" data-order-id="">
                        <!-- Header dengan nama jenis order -->
                        <div class="active-order-header">
                            <h3 class="active-order-title"></h3>
                        </div>

                        <!-- Qty Inputs -->
                        <div class="dashboard_popup_order_size form_field">
                            <div class="dashboard_popup_order_size size-inputs-grid">
                                <!-- Size inputs akan dibuat oleh JavaScript -->
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="form_field">
                            <div class="dashboard_popup_order_heading">
                                <label>Pilih Spek Barang</label>
                            </div>
                            <div class="keterangan_option keterangan-container" style="display: none;"></div>
                            <input type="text" class="keterangan-input input_form" placeholder="Pilih spek barang dari kotak hijau..." readonly>
                        </div>

                        <!-- Total Harga Order Ini -->
                        <div class="total-harga-order">
                            <div class="harga-row">
                                <span>Harga Satuan:</span>
                                <span class="harga-satuan-display">Rp 0</span>
                            </div>
                            <div class="harga-row">
                                <span>Total Qty:</span>
                                <span class="final-total-qty">0</span>
                            </div>
                            <div class="harga-row">
                                <span>Total Harga:</span>
                                <span class="harga-total-display">Rp 0</span>
                            </div>
                            
                        </div>
                    </div>
                </template>
                <div class="harga-row">
                    <span>Total Harga Keseluruhan:</span>
                    <span id="grandTotalDisplay">Rp 0</span>
                    <input type="hidden" id="grandTotalValue" name="grand_total" value="0">
                </div>

                <div class="form_field_normal">
                    <div class="popup_order_pay" style="margin-bottom: 12px;">
                        <label style="font-size: 16px; font-weight: 600;">Pembayaran</label>
                        <div class="payment-buttons">
                            <button type="button" id="btnBayarLunas" class="payment-btn payment-btn-lunas" onclick="setPembayaranLunas()">
                                Bayar Lunas
                            </button>
                            <button type="button" id="btnBayarDP" class="payment-btn payment-btn-dp" onclick="setPembayaranDP()">
                                Bayar DP
                            </button>
                        </div>
                    </div>
                    
                    <!-- Input Pembayaran -->
                    <div id="paymentSection" class="payment-section hidden">
                        <!-- Label dinamis -->
                        <div id="paymentLabel" class="payment-label"></div>
                        
                        <!-- Input Jumlah -->
                        <div class="payment-input-container">
                            <span class="payment-currency">Rp</span>
                            <input type="number" 
                                id="paymentAmount" 
                                class="payment-input"
                                placeholder="Masukkan jumlah pembayaran"
                                min="0"
                                step="1000"
                                oninput="hitungSisaPembayaran()">
                        </div>

                        <!-- Informasi Pembayaran -->
                        <div id="paymentInfo" class="payment-info">
                            <div class="payment-info-row">
                                <span>Total Harga:</span>
                                <span id="totalHargaInfo">Rp 0</span>
                            </div>
                            <div class="payment-info-row" id="dpInfoContainer">
                                <span>DP:</span>
                                <span id="dpAmountInfo" class="payment-dp">Rp 0</span>
                            </div>
                            <div class="payment-info-row" id="sisaInfoContainer">
                                <span>Sisa Pembayaran:</span>
                                <span id="sisaAmountInfo" class="payment-sisa">Rp 0</span>
                            </div>
                            <div class="payment-info-row" id="lunasInfoContainer" style="display: none;">
                                <span>Status:</span>
                                <span class="payment-lunas">LUNAS</span>
                            </div>
                        </div>

                        <!-- Hidden inputs untuk dikirim ke server -->
                        <input type="hidden" id="dpAmount" name="dp_amount" value="0">
                        <input type="hidden" id="sisaBayar" name="sisa_bayar" value="0">
                        <input type="hidden" id="harusDibayar" name="harus_dibayar" value="0">
                        <input type="hidden" id="paymentStatus" name="payment_status" value="false">
                    </div>
                </div>

                <div class="dashboard_popup_order_btn">
                    <!-- Tombol normal (sebelum simpan) -->
                    <div id="buttonsBeforeSave" class="dashboard_popup_order_btn">
                        <button type="button" id="btnCancelBefore" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Batal
                        </button>
                        <button type="submit" id="submitBtn" disabled class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Simpan Semua Order
                        </button>
                    </div>
                    
                    <!-- Tombol setelah sukses simpan (hidden awal) -->
                    <div id="buttonsAfterSave" class="hidden dashboard_popup_order_btn">
                        <button type="button" id="btnCloseAfter" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Tutup
                        </button>
                        <button type="button" id="btnViewInvoice"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Lihat Invoice
                        </button>
                        <button type="button" id="btnViewNota"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Lihat Nota
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<input type="hidden" id="savedPelangganId" value="">

<div id="historyPane">
    <div class="history_pane_box">
        
        <h3 id="historyTitle">Riwayat Pekerjaan: Print</h3>

        <div class="history_pane_box_header">
            <h2 id="selectedJobNameDisplay">
                Semua Job
            </h2>
        </div>

        <div class="history_pane_box_table">
            <table>
                <thead>
                    <tr>
                        <th>Pegawai</th>
                        <th>Nama Job</th>
                        <th>Nama Konsumen</th>
                        <th>Jenis</th>
                        <th>Jumlah (Qty)</th>
                        <th>Keterangan</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody" class="bg-white divide-y divide-gray-800">
                    <tr><td colspan="7" class="text-center py-4 text-white">Pilih Job untuk melihat riwayat.</td></tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Setting -->
<div id="popupModalSetting" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalSetting()"></div>
    <div class="dashboard_popup_progress_layout">

        <!-- HEADER -->
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Setting Baru</h2>
            <button onclick="closeModalSetting()"><img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon"></button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('progress.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="setting">

            {{-- PILIH PEGAWAI --}}
            <div class="form_field_normal">
                <label>Nama Pegawai</label>
                <select 
                    id="pilihPegawaiSetting"
                    name="pegawai_id"
                    class="select2-custom"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php $kategori = strtolower("setting"); @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)
                        @php
                            $lastHistory = $p->latestHistory;
                            $lastJobType = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty  = $lastHistory?->qty;
                            $lastOrder   = $lastHistory?->order;
                            $jobName     = $lastOrder?->nama_job;
                            $customerName= $lastOrder?->nama_konsumen;
                            $lastJobInfo = $lastOrder
                                ? "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}"
                                : "Belum ada input";
                            $jobDisplay  = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-setting="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }}" readonly
                    class="input_form">
            </div>

            <div class="form_field_normal">
                <label>Jumlah Setting</label>

                <div class="dashboard_popup_order_btn_setting">
                    <button 
                        type="button" 
                        id="settingBelumBtn"
                        data-value="0">
                        Belum
                    </button>

                    <button 
                        type="button" 
                        id="settingSelesaiBtn"
                        data-value="1">
                        Selesai
                    </button>
                </div>

                <input type="hidden" name="qty" id="qtySettingInput" value="0">

                <span id="errorPesanSetting" class="text-sm text-red-600 mt-1 font-bold hidden">
                    Input hanya boleh 0 atau 1.
                </span>
            </div>

            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" class="input_form"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button 
                    type="button" 
                    onclick="closeModalSetting()">
                    Batal
                </button>

                <button 
                    type="submit" 
                    id="submitButtonSetting"
                    disabled>
                    Simpan
                </button>
            </div>
        </form>


    </div>
</div>

<!-- PRINT -->
<div id="popupModal" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModal()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Print</h2>
            <button onclick="closeModal()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPrint">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="print">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>
                <select 
                    id="pilihPegawai"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("print");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-print="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa print : {{ $job->sisa_print }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Print</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="qtyTersedia">
                            Prineun: {{ $job->qty }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPrint"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPrint">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="printSelesai">
                            Print selesai: {{ $job->print }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaPrint">
                            Sisa Print: {{ $job->sisa_print }}
                        </p>
                    </div>
                </div>
                <span id="warningPrint" class="red-note hidden">
                    MELEBIHI PRINEUN !!!
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModal()">
                    Batal
                </button>
                <button id="submitBtnPrint" type="button">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- PRESS -->
<div id="popupModalPress" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalPress()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Press</h2>
            <button onclick="closeModalPress()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPress">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="press">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>

                <select 
                    id="pilihPegawaiPress"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("press");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-press="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa press : {{ $job->sisa_press }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Press</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="printTersedia">
                            Presseun: {{ $job->print - $job->press }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPress"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPress">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="pressSelesai">
                            Press selesai: {{ $job->press }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaPress">
                            Sisa Press: {{ $job->sisa_press }}
                        </p>
                    </div>
                </div>
                <span id="warningPress" class="red-note hidden">
                    MELEBIHI PRESSEUN !!!
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalPress()"
                    >
                    Batal
                </button>
                <button id="submitBtnPress" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- CUTTING -->
<div id="popupModalCutting" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalCutting()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Cutting</h2>
            <button onclick="closeModalCutting()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formCutting">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="cutting">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>
                <select 
                    id="pilihPegawaiCutting"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("cutting");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-cutting="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa cutting : {{ $job->sisa_cutting }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Cutting</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="pressTersedia">
                            Cuttingeun: {{ $job->press - $job->cutting }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyCutting"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanCutting">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="cuttingSelesai">
                           Cutting selesai: {{ $job->cutting }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaCutting">
                            Sisa Cutting: {{ $job->sisa_cutting }}
                        </p>
                    </div>
                </div>
                <span id="warningCutting" class="red-note hidden">
                    MELEBIHI CUTTINGEUN !!!
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalCutting()"
                    >
                    Batal
                </button>
                <button id="submitBtnCutting" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- JAHIT -->
<div id="popupModalJahit" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalJahit()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Jahit</h2>
            <button onclick="closeModalJahit()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formJahit">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="jahit">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>

                <select 
                    id="pilihPegawaiJahit"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("jahit");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-jahit="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa jahit : {{ $job->sisa_jahit }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Jahit</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="cuttingTersedia">
                            Jahiteun: {{ $job->cutting - $job->jahit }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyJahit"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanJahit">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="jahitSelesai">
                            Cutting selesai: {{ $job->jahit }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaJahit">
                            Sisa Cutting: {{ $job->sisa_jahit }}
                        </p>
                    </div>
                </div>
                <span id="warningJahit" class="red-note hidden">
                    MELEBIHI JAHITEUN !!!
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalJahit()"
                    >
                    Batal
                </button>
                <button id="submitBtnJahit" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- FINISHING -->
<div id="popupModalFinishing" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalFinishing()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Finishing</h2>
            <button onclick="closeModalFinishing()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formFinishing">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="finishing">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>

                <select 
                    id="pilihPegawaiFinishing"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("finishing");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-finishing="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa finishing : {{ $job->sisa_finishing }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Finishing</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="jahitTersedia">
                            Pinisingeun: {{ $job->jahit - $job->finishing }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyFinishing"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanFinishing">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="finishingSelesai">
                           Finishing selesai: {{ $job->finishing }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisaFinishing">
                            Sisa Finishing: {{ $job->sisa_finishing }}
                        </p>
                    </div>
                </div>
                <span id="warningFinishing" class="red-note hidden">
                    MELEBIHI PINISINGEUN !!!
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalFinishing()"
                    >
                    Batal
                </button>
                <button id="submitBtnFinishing" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- PACKING -->
<div id="popupModalPacking" class="dashboard_popup_progress popup_custom">
    <div class="overlay_close" onclick="closeModalPacking()"></div>
    <div class="dashboard_popup_progress_layout">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Progress Packing</h2>
            <button onclick="closeModalPacking()">
                <img src="{{ asset('icons/close-popup-icon.svg') }}" alt="Icon">
            </button>
        </div>

        <form method="POST" action="{{ route('progress.store') }}" id="formPacking">
            @csrf

            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <input type="hidden" name="kategori" value="packing">

            <div class="form_field_normal">
                <label>Nama Pegawai</label>
                <select 
                    id="pilihPegawaiPacking"
                    name="pegawai_id"
                    required
                >
                    <option value="">Pilih Pegawai</option>

                    @php
                        $kategori = strtolower("packing");
                    @endphp

                    @foreach ($pegawais->filter(fn($p) => strtolower($p->posisi) == $kategori) as $p)

                        @php
                            $lastHistory   = $p->latestHistory;
                            $lastJobType   = $lastHistory?->jenis_pekerjaan;
                            $lastJobQty    = $lastHistory?->qty;

                            // Relasi ke orders
                            $lastOrder     = $lastHistory?->order;
                            $jobName       = $lastOrder?->nama_job;
                            $customerName  = $lastOrder?->nama_konsumen;

                            // Info pekerjaan terakhir
                            if ($lastOrder) {
                                $lastJobInfo = "{$lastJobType}: {$lastJobQty} | {$jobName} - {$customerName}";
                            } else {
                                $lastJobInfo = "Belum ada input";
                            }

                            $jobDisplay = "{$p->nama} - (Pekerjaan Terakhir {$lastJobInfo})";
                        @endphp

                        <option value="{{ $p->id }}" data-last-job-id-packing="{{ $lastOrder?->id }}">
                            {{ $jobDisplay }}
                        </option>

                    @endforeach

                </select>
            </div>

            <!-- Nama Job -->
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" value="{{ $job->nama_job }} {{ optional($job->jenisOrder)->nama_jenis ?? '' }} - {{ $job->nama_konsumen }} (sisa packing : {{ $job->sisa_packing }})" readonly
                    class="input_form">
            </div>

            <!-- Qty -->
            <div class="form_field_normal">
                <label>Jumlah Packing</label>
                <div class="dashboard_popup_progress_box">
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_gray">
                        <p id="finishingTersedia">
                            Pekingeun: {{ $job->finishing - $job->packing }}
                        </p>
                    </div>

                    <input
                        type="number"
                        name="qty"
                        id="qtyPacking"
                        value="0"
                        min="0"
                        placeholder="Masukkan Jumlah Progress"
                        class="input_form input_form_dashboard"
                        required
                    />

                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="totalQtySpanPacking">
                            Qty: {{ $job->qty }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="packingSelesai">
                            Packing selesai: {{ $job->packing }}
                        </p>
                    </div>
                    <div class="dashboard_popup_progress_tersedia dashboard_popup_progress_tersedia_green">
                        <p id="sisa_packing">
                            Sisa Packing: {{ $job->sisa_packing }}
                        </p>
                    </div>
                </div>
                <span id="warningPacking" class="red-note hidden">
                    MELEBIHI PEKINGEUN !!!
                </span>
            </div>

            <!-- Keterangan -->
            <div class="form_field_normal">
                <label>Keterangan</label>
                <textarea name="keterangan" class="input_form" placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModalPacking()"
                    >
                    Batal
                </button>
                <button id="submitBtnPacking" type="button"
                    >
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    // Pastikan jQuery dan library Select2 sudah di-load di layout Anda.
    
    $(document).ready(function() {
        // Definisi selector yang menggabungkan semua ID
        $('#pilihPegawaiSetting, #pilihPegawai, #pilihPegawaiPress, #pilihPegawaiCutting, #pilihPegawaiJahit, #pilihPegawaiFinishing, #pilihPegawaiPacking').select2({
            
            // 1. Fitur Utama: Mengaktifkan scrollbar dan pencarian
            placeholder: "Pilih Pegawai",
            allowClear: true, 
            
            // 2. Perbaikan Layout: Memastikan lebar 100%
            width: '100%', 

            // 3. Template
            templateResult: function (data) {
                if (!data.id) { return data.text; }
                return data.text; 
            },
        });
    });
    
    // Catatan: Jika Anda tidak menggunakan jQuery, Anda harus mengganti $(document).ready
    // dengan event listener DOMContentLoaded dan menggunakan syntax Select2 murni.
</script>

<script>
    // Data dari Laravel
    const allHistories = @json($allHistories); // Dapatkan semua riwayat
    const allPegawais = @json($pegawais);   
    
    const jobData = @json($orders);

    const dateOptions = {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        timeZone: 'Asia/Jakarta'
    };
    
    // Konversi array pegawai menjadi objek map ID => Nama untuk lookup cepat
    const pegawaiMap = {};
    allPegawais.forEach(p => {
        pegawaiMap[p.id] = p.nama;
    });

    let currentJobId = null; // ID Job yang sedang aktif di filter
    let currentKategori = null; // Kategori yang sedang aktif (Print/Press/etc)

    function openHistory(kategori, buttonElement, jobId) { 
        const pane = document.getElementById('historyPane');
        const title = document.getElementById('historyTitle');
        
        // 1. Periksa apakah sudah terbuka (Toggle)
        if (pane.classList.contains('active') && currentKategori === kategori) {
            pane.classList.remove('active');
            return;
        }

        // 2. Set Global State & Tampilan
        currentKategori = kategori;
        currentJobId = jobId;
        
        pane.classList.add('active');

        // 3. Muat Data Default (untuk kategori ini)
        filterAndDisplayHistory();
    }

    function filterAndDisplayHistory() {
        const tableBody = document.getElementById('historyTableBody');
        const selectedJobNameDisplay = document.getElementById('selectedJobNameDisplay');
        const title = document.getElementById('historyTitle'); // Ambil title element

        // 1. Temukan Nama Job untuk Display (Menggunakan jobData yang dimuat di awal)
        const selectedJob = jobData.find(job => job.id === currentJobId);
        let jobName = selectedJob ? selectedJob.nama_job : 'Unknown Job';
        let customerName = '';
        let jenisOrderName = '';

        if (selectedJob) {
            jobName = selectedJob.nama_job;
            customerName = selectedJob.nama_konsumen;
            // Ambil nama jenis order jika relasi tersedia
            jenisOrderName = selectedJob.jenis_order?.nama_jenis || '';
        }
    
        // Update Judul & Nama Job Display
        title.textContent = `Riwayat Pekerjaan: ${currentKategori}`;
        if (selectedJobNameDisplay) {
            // Tampilkan 'Nama Job - Jenis Order - Nama Konsumen'
            let displayText = jobName;
            if (jenisOrderName) {
                displayText += ` ${jenisOrderName}`;
            }
            displayText += ` - ${customerName}`;
            selectedJobNameDisplay.textContent = displayText; 
        }
        
        // 2. Filter Data (Hanya berdasarkan Job ID dan Kategori saat ini)
        let filteredHistories = allHistories.filter(history => {
            const matchesCategory = history.jenis_pekerjaan === currentKategori;
            // Filter diaktifkan hanya jika Job ID sudah diset di openHistory
            const matchesJob = currentJobId ? history.order_id === currentJobId : true; 
            return matchesCategory && matchesJob;
        });

        // Urutkan berdasarkan waktu terbaru
        filteredHistories.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        // 3. Render Tabel
        // ... (Logika rendering tabel tetap sama, menggunakan dateOptions dan pegawaiMap) ...

        tableBody.innerHTML = '';
        
        if (filteredHistories.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-800 font-medium">Tidak ada riwayat untuk Job ${jobName} di tahap ${currentKategori}.</td></tr>`;
            return;
        }

        filteredHistories.forEach(history => {
            const pegawaiName = pegawaiMap[history.pegawai_id] || 'Unknown';

            const formattedTimestamp = new Date(history.created_at).toLocaleString('id-ID', dateOptions);
            
            const row = `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${pegawaiName}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${history.jenis_pekerjaan}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${customerName}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${jenisOrderName}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium text-center"><div class="w-28">${history.qty}</div></td>
                    <td class="px-3 py-2 font-medium">${history.keterangan || '-'}</td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">${formattedTimestamp}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // 3. Tambahkan Event Listener (untuk filter Job di dalam pane)
    document.addEventListener('DOMContentLoaded', () => {
        const jobSelect = document.getElementById('historyJobSelect');
        if (jobSelect) {
            jobSelect.addEventListener('change', filterAndDisplayHistory);
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // --- 1. AMBIL SEMUA ELEMEN DI SCOPE DOMContentLoaded ---
    const inputSetting = document.getElementById("qtySettingInput");
    const warningSetting = document.getElementById("errorPesanSetting");
    const submitBtnSetting = document.getElementById("submitButtonSetting"); // Tombol yang bermasalah
    const form = document.getElementById("formSetting"); // Asumsi ID form Anda
    
    const settingBelumBtn = document.getElementById("settingBelumBtn");
    const settingSelesaiBtn = document.getElementById("settingSelesaiBtn");


    // --- FUNGSI HELPER: SET NILAI INPUT & STATUS VISUAL ---
    function setSettingValueAndActive(button, value) {
        // Fungsi ini harus didefinisikan di luar atau menggunakan variabel dari scope luar
        
        // Reset Visual Semua Tombol (Logika Warna)
        [settingBelumBtn, settingSelesaiBtn].forEach(btn => {
            btn.classList.remove("green_bg", "red_bg", "text-white");
            btn.classList.add("background_gray_young", "text-gray-700");
        });

        // 2. Tentukan Warna Aktif
        const activeColor = (value === "1") ? "green_bg" : "red_bg";
        
        // 3. Set Visual Tombol Aktif
        button.classList.remove("background_gray_young", "text-gray-700");
        button.classList.add(activeColor, "text-white");

        // 4. Update Nilai Input Tersembunyi
        inputSetting.value = value;
        
        // 5. KONTROL KRITIS: Update Status Tombol Submit
        // Aktifkan jika nilai = "1" (Selesai), Nonaktifkan jika "0" (Belum)
        submitBtnSetting.disabled = (value !== "1"); 
        warningSetting.classList.add("hidden"); 
    }
    
    // --- LISTENER PENTING: TOMBOL BELUM/SELESAI (PENGGANTI INPUT) ---
    if (settingBelumBtn && settingSelesaiBtn) {
        settingBelumBtn.addEventListener("click", () => {
            setSettingValueAndActive(settingBelumBtn, settingBelumBtn.dataset.value); 
        });

        settingSelesaiBtn.addEventListener("click", () => {
            setSettingValueAndActive(settingSelesaiBtn, settingSelesaiBtn.dataset.value); 
        });
    }
    
    // --- LISTENER SUBMIT (Tetap Sama) ---
    submitBtnSetting.addEventListener("click", function (event) {
        if (inputSetting.value !== "1") {
            warningSetting.textContent = "Status harus diatur Selesai (1) untuk menyimpan.";
            warningSetting.classList.remove("hidden");
            event.preventDefault();
            return;
        }
        
        if (!form.checkValidity() || submitBtnSetting.disabled) {
            event.preventDefault(); 
            return;
        }

        form.submit();
    });

    // --- PENTING: INISIALISASI AWAL ---
    // Atur status awal tombol berdasarkan nilai dari database
    const initialSetting = String({{ $job->setting }});
    if (settingBelumBtn && settingSelesaiBtn) {
        if (initialSetting === "1") {
            setSettingValueAndActive(settingSelesaiBtn, "1");
        } else {
            setSettingValueAndActive(settingBelumBtn, "0");
        }
    }
});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let qtyPrint = {{ $job->qty }};
    let printSelesai = {{ $job->print }};
    let sisaPrint = qtyPrint - printSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputPrint = document.getElementById("qtyPrint");
    let warningPrint = document.getElementById("warningPrint");
    let submitBtnPrint = document.getElementById("submitBtnPrint");
    
    // Asumsi: form memiliki ID formPrint
    let form = document.getElementById("formPrint"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputPrint.max = sisaPrint; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputPrint.addEventListener("input", function() {
        let qtyPrint = parseFloat(inputPrint.value);

        // 1. Cek apakah inputPrint valid dan positif
        if (isNaN(qtyPrint) || qtyPrint <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningPrint.classList.add("hidden"); 
            submitBtnPrint.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyPrint) && inputPrint.value !== "") {
                 inputPrint.classList.add("border-red-500");
            } else {
                 inputPrint.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah inputPrint melebihi Sisa Stok Nyata
        if (qtyPrint > sisaPrint) {
            // ERROR STATE: Melebihi batas
            warningPrint.textContent = "MELEBIHI PRINEUN !!!"; // Pesan yang diminta
            warningPrint.classList.remove("hidden");
            
            // Tambahkan visual error
            inputPrint.classList.add("border-red-500");
            inputPrint.classList.remove("border-gray-300");
             
            submitBtnPrint.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: InputPrint aman (positif dan dalam batas)
            warningPrint.classList.add("hidden");
            
            // Hapus visual error
            inputPrint.classList.remove("border-red-500");
            inputPrint.classList.add("border-gray-300");
            
            submitBtnPrint.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnPrint.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!form.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'inputPrint', cegah submit
        if (submitBtnPrint.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningPrint.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        form.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let printSelesai = {{ $job->print }};
    let pressSelesai = {{ $job->press }};
    let sisaPress = printSelesai - pressSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let input = document.getElementById("qtyPress");
    let warningPress = document.getElementById("warningPress");
    let submitBtnPress = document.getElementById("submitBtnPress");
    
    // Asumsi: form memiliki ID formPress
    let form = document.getElementById("formPress"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    input.max = sisaPress; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    input.addEventListener("input", function() {
        let qtyPress = parseFloat(input.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyPress) || qtyPress <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningPress.classList.add("hidden"); 
            submitBtnPress.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyPress) && input.value !== "") {
                 input.classList.add("border-red-500");
            } else {
                 input.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyPress > sisaPress) {
            // ERROR STATE: Melebihi batas
            warningPress.textContent = "MELEBIHI PRESSEUN !!!"; // Pesan yang diminta
            warningPress.classList.remove("hidden");
            
            // Tambahkan visual error
            input.classList.add("border-red-500");
            input.classList.remove("border-gray-300");
            
            submitBtnPress.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningPress.classList.add("hidden");
            
            // Hapus visual error
            input.classList.remove("border-red-500");
            input.classList.add("border-gray-300");
            
            submitBtnPress.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnPress.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!form.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnPress.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningPress.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        form.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let pressSelesai = {{ $job->press }};
    let cuttingSelesai = {{ $job->cutting }};
    let sisaCutting = pressSelesai - cuttingSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputCutting = document.getElementById("qtyCutting");
    let warningCutting = document.getElementById("warningCutting");
    let submitBtnCutting = document.getElementById("submitBtnCutting");
    
    // Asumsi: form memiliki ID formPress
    let formCutting = document.getElementById("formCutting"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputCutting.max = sisaCutting; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputCutting.addEventListener("input", function() {
        let qtyCutting = parseFloat(inputCutting.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyCutting) || qtyCutting <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningCutting.classList.add("hidden"); 
            submitBtnCutting.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyCutting) && inputCutting.value !== "") {
                 inputCutting.classList.add("border-red-500");
            } else {
                 inputCutting.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyCutting > sisaCutting) {
            // ERROR STATE: Melebihi batas
            warningCutting.textContent = "MELEBIHI CUTTINGEUN !!!"; // Pesan yang diminta
            warningCutting.classList.remove("hidden");
            
            // Tambahkan visual error
            inputCutting.classList.add("border-red-500");
            inputCutting.classList.remove("border-gray-300");
            
            submitBtnCutting.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningCutting.classList.add("hidden");
            
            // Hapus visual error
            inputCutting.classList.remove("border-red-500");
            inputCutting.classList.add("border-gray-300");
            
            submitBtnCutting.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnCutting.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!formCutting.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnCutting.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningCutting.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        formCutting.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let cuttingSelesai = {{ $job->cutting }};
    let jahitSelesai = {{ $job->jahit }};
    let sisaJahit = cuttingSelesai - jahitSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputJahit = document.getElementById("qtyJahit");
    let warningJahit = document.getElementById("warningJahit");
    let submitBtnJahit = document.getElementById("submitBtnJahit");
    
    // Asumsi: form memiliki ID formPress
    let formJahit = document.getElementById("formJahit"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputJahit.max = sisaJahit; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputJahit.addEventListener("input", function() {
        let qtyJahit = parseFloat(inputJahit.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyJahit) || qtyJahit <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningJahit.classList.add("hidden"); 
            submitBtnJahit.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyJahit) && inputJahit.value !== "") {
                 inputJahit.classList.add("border-red-500");
            } else {
                 inputJahit.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyJahit > sisaJahit) {
            // ERROR STATE: Melebihi batas
            warningJahit.textContent = "MELEBIHI JAHITEUN !!!"; // Pesan yang diminta
            warningJahit.classList.remove("hidden");
            
            // Tambahkan visual error
            inputJahit.classList.add("border-red-500");
            inputJahit.classList.remove("border-gray-300");
            
            submitBtnJahit.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningJahit.classList.add("hidden");
            
            // Hapus visual error
            inputJahit.classList.remove("border-red-500");
            inputJahit.classList.add("border-gray-300");
            
            submitBtnJahit.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnJahit.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!formJahit.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnJahit.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningJahit.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        formJahit.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let jahitSelesai = {{ $job->jahit }};
    let finishingSelesai = {{ $job->finishing }};
    let sisaFinishing = jahitSelesai - finishingSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputFinishing = document.getElementById("qtyFinishing");
    let warningFinishing = document.getElementById("warningFinishing");
    let submitBtnFinishing = document.getElementById("submitBtnFinishing");
    
    // Asumsi: form memiliki ID formPress
    let formFinishing = document.getElementById("formFinishing"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputFinishing.max = sisaFinishing; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputFinishing.addEventListener("input", function() {
        let qtyFinishing = parseFloat(inputFinishing.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyFinishing) || qtyFinishing <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningFinishing.classList.add("hidden"); 
            submitBtnFinishing.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyFinishing) && inputFinishing.value !== "") {
                 inputFinishing.classList.add("border-red-500");
            } else {
                 inputFinishing.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyFinishing > sisaFinishing) {
            // ERROR STATE: Melebihi batas
            warningFinishing.textContent = "MELEBIHI PINISINGEUN !!!"; // Pesan yang diminta
            warningFinishing.classList.remove("hidden");
            
            // Tambahkan visual error
            inputFinishing.classList.add("border-red-500");
            inputFinishing.classList.remove("border-gray-300");
            
            submitBtnFinishing.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningFinishing.classList.add("hidden");
            
            // Hapus visual error
            inputFinishing.classList.remove("border-red-500");
            inputFinishing.classList.add("border-gray-300");
            
            submitBtnFinishing.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnFinishing.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!formFinishing.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnFinishing.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningFinishing.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        formFinishing.submit();
    });

});
document.addEventListener("DOMContentLoaded", function () {

    // 1. Ambil Nilai Stok Nyata (Sudah diset dari Blade)
    let finishingSelesai = {{ $job->finishing }};
    let packingSelesai = {{ $job->packing }};
    let sisaPacking = finishingSelesai - packingSelesai; // SISA NYATA YANG BOLEH DI-INPUT
    
    let inputPacking = document.getElementById("qtyPacking");
    let warningPacking = document.getElementById("warningPacking");
    let submitBtnPacking = document.getElementById("submitBtnPacking");
    
    // Asumsi: form memiliki ID formPress
    let formPacking = document.getElementById("formPacking"); 

    // Pastikan batas maksimum browser diset ke SISA NYATA
    inputPacking.max = sisaPacking; 
    
    // --- LISTENER PENTING: VALIDASI REAL-TIME ---
    inputPacking.addEventListener("input", function() {
        let qtyPacking = parseFloat(inputPacking.value);

        // 1. Cek apakah input valid dan positif
        if (isNaN(qtyPacking) || qtyPacking <= 0) {
            // Jika nol atau negatif, nonaktifkan tombol (tapi jangan tampilkan error Melebihi)
            warningPacking.classList.add("hidden"); 
            submitBtnPacking.disabled = true;
            
            // Tambahkan visual error jika NaN
            if (isNaN(qtyPacking) && inputPacking.value !== "") {
                 inputPacking.classList.add("border-red-500");
            } else {
                 inputPacking.classList.remove("border-red-500");
            }
            return;
        }

        // 2. Cek apakah input melebihi Sisa Stok Nyata
        if (qtyPacking > sisaPacking) {
            // ERROR STATE: Melebihi batas
            warningPacking.textContent = "MELEBIHI PEKINGEUN !!!"; // Pesan yang diminta
            warningPacking.classList.remove("hidden");
            
            // Tambahkan visual error
            inputPacking.classList.add("border-red-500");
            inputPacking.classList.remove("border-gray-300");
            
            submitBtnPacking.disabled = true; // Nonaktifkan submit button
        } else {
            // VALID STATE: Input aman (positif dan dalam batas)
            warningPacking.classList.add("hidden");
            
            // Hapus visual error
            inputPacking.classList.remove("border-red-500");
            inputPacking.classList.add("border-gray-300");
            
            submitBtnPacking.disabled = false; // Aktifkan submit button
        }
    });
    // ------------------------------------------
    
    // --- LISTENER SUBMIT (Disederhanakan) ---
    // Listener ini sekarang hanya bertugas men-trigger submit jika tombol tidak disabled
    submitBtnPacking.addEventListener("click", function (event) {
        // Cek validasi bawaan browser (untuk required, min, max)
        if (!formPacking.checkValidity()) {
             // Biarkan browser menampilkan error bawaan (jika ada)
             return;
        }

        // Jika tombol dinonaktifkan oleh listener 'input', cegah submit
        if (submitBtnPacking.disabled) {
            event.preventDefault(); 
            // Opsional: Tampilkan pesan peringatan lagi jika tombol dinonaktifkan
            // warningPacking.classList.remove("hidden"); 
            return;
        }

        // Jika tidak disabled, submit form
        formPacking.submit();
    });

});
</script>

<script>
    function openModal() {
        document.getElementById('popupModal').classList.add('active');
    }
    function openModalSetting() {
        document.getElementById('popupModalSetting').classList.add('active');
    }
    function openModalPress() {
        document.getElementById('popupModalPress').classList.add('active');
    }
    function openModalCutting() {
        document.getElementById('popupModalCutting').classList.add('active');
    }
    function openModalJahit() {
        document.getElementById('popupModalJahit').classList.add('active');
    }
    function openModalFinishing() {
        document.getElementById('popupModalFinishing').classList.add('active');
    }
    function openModalPacking() {
        document.getElementById('popupModalPacking').classList.add('active');
    }
    function closeModal() {
        document.getElementById('popupModal').classList.remove('active');
    }
    function closeModalSetting() {
        document.getElementById('popupModalSetting').classList.remove('active');
    }
    function closeModalPress() {
        document.getElementById('popupModalPress').classList.remove('active');
    }
    function closeModalCutting() {
        document.getElementById('popupModalCutting').classList.remove('active');
    }
    function closeModalJahit() {
        document.getElementById('popupModalJahit').classList.remove('active');
    }
    function closeModalFinishing() {
        document.getElementById('popupModalFinishing').classList.remove('active');
    }
    function closeModalPacking() {
        document.getElementById('popupModalPacking').classList.remove('active');
    }
</script>

<script>
    $(document).ready(function() {
        // --- INISIALISASI SELECT2 ---
        $('#selectJob').select2({
            placeholder: "Pilih Job Lain...", 
            allowClear: true, 
            width: '100%',
        });

        // --- LISTENER PENGALIHAN (REDIRECTION) ---
        // Gunakan event Select2:select untuk kepastian
        $('#selectJob').on('select2:select', function (e) {
            // Nilai (slug) diambil dari data.id
            let slug = e.params.data.id; 

            if (slug === 'Progress Keseluruhan') {
                window.location.href = '/dashboard';
            } else {
                // Pastikan slug tidak kosong
                if (slug) {
                    window.location.href = '/dashboard/' + slug;
                }
            }
        });
        
        // --- LISTENER UNTUK ALLOW CLEAR (PENTING) ---
        // Tambahkan listener ini jika Anda ingin menangani klik tombol Hapus (Clear)
        $('#selectJob').on('select2:clear', function (e) {
             window.location.href = '/dashboard';
        });
        
    });
</script>

@endsection
