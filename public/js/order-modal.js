// ============================================
// MODAL FUNCTIONS - Order Form Modal
// ============================================

// Data @json akan dimuat dari template yang menginclude partial ini
// const allJenisSpek = @json($jenisSpek);
// const allJenisSpekDetail = @json($jenisSpekDetail);
// const allJenisOrders = @json($jenisOrders);

function openModal(modalId = 'popupModal') {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId = 'popupModal') {
    document.getElementById(modalId).classList.add('hidden');
}

function hitungQty() {
    const sizes = [
        'xs', 's', 'm', 'l', 'xl',
        '2xl', '3xl', '4xl', '5xl', '6xl'
    ];

    let total = 0;

    sizes.forEach(function(size) {
        const input = document.querySelector(`[name="${size}"]`);
        const value = parseInt(input.value) || 0;

        // Jika minus, paksa jadi 0
        if (value < 0) {
            input.value = 0;
            total += 0;
        } else {
            total += value;
        }
    });

    document.getElementById('qty').value = total;
}

function openKategori(id) {
    // sembunyikan semua kategori
    document.querySelectorAll('.kategori-wrapper').forEach(el => el.classList.add('hidden'));

    // tampilkan kategori yang dipilih
    document.getElementById('kategori-' + id).classList.remove('hidden');

    // ubah style tab
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('bg-gray-800', 'text-white'));
    event.target.classList.add('bg-gray-800', 'text-white');

    // Tampilkan keterangan box berdasarkan kategori yang dipilih
    const kategoriEl = document.getElementById('kategori-' + id);
    const checkedJenis = kategoriEl.querySelector('.js-pakaian-input:checked');
    if (checkedJenis && typeof updateKeteranganByJenisOrder === 'function') {
        updateKeteranganByJenisOrder(checkedJenis.value);
    } else {
        displayKeteranganByKategori(id);
    }
}

document.addEventListener("DOMContentLoaded", function () {

    // Ambil semua jenis_order inputs & labels
    const pakaianInputs = document.querySelectorAll('.js-pakaian-input');
    const pakaianLabels = document.querySelectorAll('.js-pakaian-label');

    // Event listener pada setiap jenis_order radio
    pakaianInputs.forEach(input => {
        input.addEventListener('change', function () {
            // Reset semua label
            pakaianLabels.forEach(label => {
                label.classList.remove('bg-gray-800', 'text-white', 'shadow-md', 'border-gray-800');
                label.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-300');
            });

            // Label aktif
            const activeLabel = document.querySelector(`label[for="${this.id}"]`);
            activeLabel.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
            activeLabel.classList.add('bg-gray-800', 'text-white', 'shadow-md', 'border-gray-800');

            // Update keterangan berdasarkan jenis_order yang dipilih
            updateKeteranganByJenisOrder(this.value);
        });
    });

    // Array warna untuk kotak spek berdasarkan kategori
    const categoryColors = [
        'bg-[#00FF00]',      // Kategori 1: Hijau
        'bg-[#FF00FF]',      // Kategori 2: Magenta
        'bg-[#00FFFF]',      // Kategori 3: Cyan
        'bg-[#FFFF00]',      // Kategori 4: Kuning
        'bg-[#FF6B6B]',      // Kategori 5: Merah muda
        'bg-[#4ECDC4]',      // Kategori 6: Teal
    ];

    // Function untuk ambil warna berdasarkan kategori_jenis_order
    window.getCategoryColor = function(kategoriId) {
        const colorIndex = (kategoriId - 1) % categoryColors.length;
        return categoryColors[colorIndex];
    };

    // Function untuk filter dan update keterangan berdasarkan jenis_order yang dipilih
    window.updateKeteranganByJenisOrder = function(jenisOrderId) {
        const container = document.getElementById('keteranganContainer');
        const keteranganInput = document.getElementById('keteranganInput');
        const currentKategoriTab = document.querySelector('.tab-btn.bg-gray-800');
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
            box.className = `${boxColor} rounded-3xl py-2 px-2 mb-4`;

            // Tambah title spek
            const title = document.createElement('p');
            title.className = 'text-[10px] font-semibold mt-0 mb-2 text-center text-black rounded py-1';
            title.textContent = spekName;
            box.appendChild(title);

            // Buat grid untuk detail
            const detailGrid = document.createElement('div');
            detailGrid.className = 'grid grid-cols-3 gap-1';

            // Tambahkan setiap detail ke grid
            details.forEach((detail, index) => {
                const radioId = `detail-${detail.id}`;

                // Hidden radio input
                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.id = radioId;
                radio.name = `speks[${detail.id_jenis_spek}]`;
                radio.value = detail.id;
                radio.dataset.label = detail.nama_jenis_spek_detail;
                radio.className = 'hidden';

                // Button/Label (visual)
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'flex flex-col items-center w-full rounded cursor-pointer transition hover:opacity-80';
                
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
                span.className = 'text-[7px] bg-red text-center font-regular bg-[#009A00] text-white px-1 rounded-sm';
                span.textContent = detail.nama_jenis_spek_detail;
                button.appendChild(span);

                // Event listener pada button
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    // Toggle logic
                    if (radio.checked) {
                        radio.checked = false;
                        button.classList.remove('ring-2', 'ring-green-700');
                    } else {
                        radio.checked = true;
                        detailGrid.querySelectorAll('button').forEach(btn => {
                            btn.classList.remove('ring-2', 'ring-green-700');
                        });
                        button.classList.add('ring-2', 'ring-green-700');
                    }

                    // Update aggregated keterangan
                    updateKeteranganField();
                });

                detailGrid.appendChild(radio);
                detailGrid.appendChild(button);
            });

            box.appendChild(detailGrid);
            container.appendChild(box);
        });
    }

    // Kumpulkan pilihan detail dari setiap kotak
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
            const checked = box.querySelector(`input[name="speks[${spekId}]"]:checked`);
            if (checked) {
                const label = checked.dataset.label || checked.value;
                pairs.push(`${spekName}: ${label}`);
            }
        });

        keteranganInput.value = pairs.join(' | ');
    }

    // Function untuk menampilkan keterangan berdasarkan kategori
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
            box.className = `${boxColor} rounded-3xl py-2 px-2 mb-4`;

            // Tambah title spek
            const title = document.createElement('p');
            title.className = 'text-[10px] font-semibold mt-0 mb-2 text-center text-black rounded py-1';
            title.textContent = spekName;
            box.appendChild(title);

            // Buat grid untuk detail
            const detailGrid = document.createElement('div');
            detailGrid.className = 'grid grid-cols-3 gap-1';

            // Tambahkan setiap detail ke grid
            details.forEach((detail, index) => {
                const radioId = `detail-${detail.id}`;

                // Hidden radio input
                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.id = radioId;
                radio.name = `speks[${detail.id_jenis_spek}]`;
                radio.value = detail.id;
                radio.dataset.label = detail.nama_jenis_spek_detail;
                radio.className = 'hidden';

                // Button/Label (visual)
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'flex flex-col items-center w-full rounded cursor-pointer transition hover:opacity-80';
                
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
                span.className = 'text-[7px] text-center font-regular bg-[#009A00] text-white px-1 rounded-sm';
                span.textContent = detail.nama_jenis_spek_detail;
                button.appendChild(span);

                // Event listener pada button
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    // Toggle logic
                    if (radio.checked) {
                        radio.checked = false;
                        button.classList.remove('ring-2', 'ring-green-700');
                    } else {
                        radio.checked = true;
                        detailGrid.querySelectorAll('button').forEach(btn => {
                            btn.classList.remove('ring-2', 'ring-green-700');
                        });
                        button.classList.add('ring-2', 'ring-green-700');
                    }

                    // Update aggregated keterangan
                    updateKeteranganField();
                });

                detailGrid.appendChild(radio);
                detailGrid.appendChild(button);
            });

            box.appendChild(detailGrid);
            container.appendChild(box);
        });
    }

});
