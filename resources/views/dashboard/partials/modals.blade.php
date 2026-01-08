<!-- MODAL TAMBAH KATEGORI -->
<div id="modalTambahKategori" class="custom-modal">
    <div class="modal-content" style="text-align:start;">
        <h3>Tambah Kategori</h3>
        <form id="formTambahKategori">
            @csrf
            <div style="margin-bottom: 15px;">
                <input
                    type="text"
                    name="nama"
                    id="namaKategoriInput"
                    class="input_form"
                    placeholder="Masukkan nama kategori"
                    required>
            </div>
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeTambahKategoriModal()">Batal</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DELETE KATEGORI -->
<div id="deleteKategoriModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus Kategori</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus kategori "<span id="kategoriNameToDelete"></span>"?
            Semua jenis spek dan detail di dalamnya juga akan terhapus.
        </p>
        <input type="hidden" id="kategoriIdToDelete">
        <div class="dashboard_popup_order_btn">
            <button id="cancelDeleteKategoriBtn">Batal</button>
            <button id="confirmDeleteKategoriBtn">Hapus</button>
        </div>
    </div>
</div>

<!-- MODAL DELETE SPEK -->
<div id="deleteSpekModal" class="custom-modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p style="margin-bottom: 20px;">
            Yakin ingin menghapus jenis spek "<span id="spekNameToDelete"></span>"?
            Semua detail di dalamnya juga akan terhapus.
        </p>
        <input type="hidden" id="spekIdToDelete">
        <input type="hidden" id="kategoriIdToDelete">
        <div class="dashboard_popup_order_btn">
            <button id="cancelDeleteSpekBtn">Batal</button>
            <button id="confirmDeleteSpekBtn">Hapus</button>
        </div>
    </div>
</div>

<!-- MODAL JOB -->
<div id="modalJob"class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeJobModal()" class="overlay_close"></div>
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2 id="modalJobTitle">Tambah Job</h2>
            <button onclick="closeJobModal()">&times;</button>
        </div>
        <form id="formJob" onsubmit="simpanJob(event)">
            @csrf
            <input type="hidden" id="jobId" value="">
            <div class="form_field_normal">
                <label>Nama Job</label>
                <input type="text" id="jobNama" class="input_form" required>
            </div>
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeJobModal()">Batal</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL JENIS SPEK -->
<div id="modalJenisSpek" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeModal()" class="overlay_close"></div>    
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2>Tambah Jenis Spek</h2>
            <button onclick="closeModal()">&times;</button>
        </div>
        <form id="formTambahSpek" method="POST">
            @csrf
            <input type="hidden" name="id_kategori_jenis_order" id="kategori_id_input">
            <input type="hidden" name="current_kategori_id" id="current_kategori_modal_input">
            <div class="form_field_normal">
                <label>Nama Jenis Spek</label>
                <input
                    type="text"
                    name="nama_jenis_spek"
                    id="nama_jenis_spek_input"
                    class="input_form"
                    placeholder="Contoh: Ukuran, Warna, Bahan..."
                    required>
            </div>
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeModal()">Batal</button>
                <button type="submit" id="btnSubmitSpek">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL JENIS SPEK DETAIL -->
<div id="modalJenisSpekDetail" class="dashboard_popup_order dashboard_popup_order_small popup_custom">
    <div onclick="closeDetailModal()" class="overlay_close"></div>    
    <div class="dashboard_popup_order_box">
        <div class="dashboard_popup_order_heading">
            <h2 id="modalDetailTitle">Tambah Jenis Spek Detail</h2>
            <button onclick="closeDetailModal()">&times;</button>
        </div>
        <form id="formDetailSpek" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_jenis_spek" id="detail_spek_id_input">
            <input type="hidden" name="current_kategori_id" id="current_kategori_id_input">
            <input type="hidden" name="current_spek_id" id="current_spek_id_input">
            <input type="hidden" name="_method" id="detail_method_input" value="POST">
            <div class="form_field_normal">
                <label>Nama Detail</label>
                <input
                    type="text"
                    name="nama_jenis_spek_detail"
                    id="detail_nama_input"
                    class="input_form"
                    placeholder="Contoh: Benzema, Brazil ..."
                    required>
            </div>
            <div class="form_field_normal form_field_normal_link">
                <label>Kategori</label>
                <div id="jenisOrderCheckboxContainer" class="spek_checkbox_style"></div>
                <a href="/dashboard/pengaturan#hargaBarang">Tambah Kategori</a>
            </div>
            <div class="form_field_normal">
                <label>Gambar (Opsional)</label>
                <input
                    type="file"
                    name="gambar"
                    id="detail_gambar_input"
                    accept="image/*"
                    class="input_form"> 
            </div>
            <div class="dashboard_popup_order_btn">
                <button type="button" onclick="closeDetailModal()">Batal</button>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL CONFIRM DELETE -->
<div id="myModal" class="custom-modal">
    <div class="modal-content">
        <p style="margin-bottom: 20px;">Yakin ingin menghapus data ini?</p>
        <div class="dashboard_popup_order_btn dashboard_popup_order_btn_center">
            <button id="cancelBtn">Batal</button>
            <button id="confirmBtn">Hapus</button>
        </div>
    </div>
</div>