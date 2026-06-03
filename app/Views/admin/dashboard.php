<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="position-relative" style="max-width: 300px;">
        <i class="fas fa-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #b2bec3;"></i>
        <input type="text" id="searchInput" onkeyup="cariPengguna()" class="form-control rounded-pill ps-5 border-0 bg-light shadow-sm" placeholder="Cari Nama / Username...">
    </div>

    <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        <i class="fas fa-plus me-2"></i>Tambah User
    </button>
</div>

<div class="card p-0 border-0 shadow-sm overflow-hidden">
    <table class="table table-hover mb-0 align-middle">
        <thead class="bg-light">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="py-3">Username</th>
                <th class="py-3 text-center">Status AI</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-4 py-3 d-flex align-items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Salma+Pudjiati&background=74b9ff&color=fff&size=40" class="rounded-circle shadow-sm" alt="Avatar">
                    <span class="fw-bold text-dark">Salma Pudjiati</span>
                </td>
                <td class="text-muted">salma_p</td>
                <td class="text-center">
                    <span class="badge bg-danger text-white rounded-pill px-3 py-2"><i class="fas fa-exclamation-triangle me-1"></i> Tinggi</span>
                </td>
                <td class="px-4 text-center">
                    <button class="btn btn-sm btn-info text-white rounded-3 px-3 shadow-sm" onclick="lihatDetail('Salma Pudjiati', 'salma_p', 'Tinggi')">
                        <i class="fas fa-eye me-1"></i> Detail
                    </button>
                </td>
            </tr>

            <tr>
                <td class="px-4 py-3 d-flex align-items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=fdcb6e&color=fff&size=40" class="rounded-circle shadow-sm" alt="Avatar">
                    <span class="fw-bold text-dark">Budi Santoso</span>
                </td>
                <td class="text-muted">budi_s</td>
                <td class="text-center">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-exclamation-circle me-1"></i> Sedang</span>
                </td>
                <td class="px-4 text-center">
                    <button class="btn btn-sm btn-info text-white rounded-3 px-3 shadow-sm" onclick="lihatDetail('Budi Santoso', 'budi_s', 'Sedang')">
                        <i class="fas fa-eye me-1"></i> Detail
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">

            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modalTambahUserLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="formTambahUser">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control rounded-3" id="inputNama" placeholder="Masukkan nama lengkap..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Username</label>
                        <input type="text" class="form-control rounded-3" id="inputUsername" placeholder="Masukkan username..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Password Sementara</label>
                        <input type="password" class="form-control rounded-3" id="inputPassword" placeholder="Masukkan password..." required>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 justify-content-between">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-3 px-4" onclick="simpanUserBaru()"><i class="fas fa-save me-2"></i>Simpan Data</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailMahasiswa" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">

            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modalDetailLabel">Detail Riwayat Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="alert alert-light border rounded-3 mb-4 d-flex align-items-center gap-3">
                    <img id="detailAvatar" src="" class="rounded-circle shadow-sm" alt="Avatar" width="50" height="50">
                    <div>
                        <h6 class="mb-0 fw-bold" id="detailNama">Nama Mahasiswa</h6>
                        <small class="text-muted"><span id="detailUsername">username</span> | Status Saat Ini: <span id="detailBadge" class="badge">Status</span></small>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-dark m-0"><i class="fas fa-history me-2 text-primary"></i>Riwayat Prediksi AI</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
                </div>

                <div class="table-responsive border rounded-3">
                    <table class="table table-hover table-borderless align-middle text-center mb-0" style="min-width: 900px;">
                        <thead class="table-light text-muted small border-bottom">
                            <tr>
                                <th class="py-3">No</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Self Esteem</th>
                                <th class="py-3">Riwayat Mental</th>
                                <th class="py-3">Depresi</th>
                                <th class="py-3">Sakit Kepala</th>
                                <th class="py-3">Kualitas Tidur</th>
                                <th class="py-3">Performa Akademik</th>
                                <th class="py-3">Beban Belajar</th>
                                <th class="py-3">Dukungan Sosial</th>
                                <th class="py-3">Stres Level</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr class="border-bottom">
                                <td class="fw-bold text-muted py-3">1</td>
                                <td>16/05/2026</td>
                                <td>7</td>
                                <td><span class="badge bg-danger-subtle text-danger">Ada</span></td>
                                <td>24</td>
                                <td>5</td>
                                <td>1</td>
                                <td>2</td>
                                <td>5</td>
                                <td>1</td>
                                <td><span class="badge bg-danger text-white rounded-pill px-3">Tinggi</span></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-bold text-muted py-3">2</td>
                                <td>15/05/2026</td>
                                <td>12</td>
                                <td><span class="badge bg-danger-subtle text-danger">Ada</span></td>
                                <td>18</td>
                                <td>3</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>2</td>
                                <td><span class="badge bg-warning text-dark rounded-pill px-3">Sedang</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted py-3">3</td>
                                <td>14/05/2026</td>
                                <td>26</td>
                                <td><span class="badge bg-success-subtle text-success">Tidak</span></td>
                                <td>4</td>
                                <td>1</td>
                                <td>4</td>
                                <td>4</td>
                                <td>2</td>
                                <td>4</td>
                                <td><span class="badge bg-success text-white rounded-pill px-3">Rendah</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 justify-content-between">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary rounded-3 px-4" onclick="catatIntervensi()"><i class="fas fa-notes-medical me-2"></i>Catat Intervensi</button>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->endSection() ?>