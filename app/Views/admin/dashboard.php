<?php

/**
 * @var array $mahasiswaData
 */
?>

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
            <?php
            $colorMap = [
                'Rendah' => 'bg-success',
                'Sedang' => 'bg-warning',
                'Tinggi' => 'bg-danger',
                null => 'bg-secondary'
            ];

            $iconMap = [
                'Rendah' => 'fas fa-check-circle',
                'Sedang' => 'fas fa-exclamation-circle',
                'Tinggi' => 'fas fa-exclamation-triangle',
                null => 'fas fa-question-circle'
            ];
            ?>
            <?php foreach ($mahasiswaData as $mahasiswa) : ?>
                <tr>
                    <td class="px-4 py-3 d-flex align-items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=<?= $mahasiswa['fullname'] ?>&background=74b9ff&color=fff&size=40" class="rounded-circle shadow-sm" alt="Avatar">
                        <span class="fw-bold text-dark"><?= $mahasiswa['fullname'] ?></span>
                    </td>
                    <td class="text-muted"><?= $mahasiswa['username'] ?></td>
                    <td class="text-center">
                        <?php
                        $color = $colorMap[$mahasiswa['latest_stress_level'] ?? null];
                        $icon = $iconMap[$mahasiswa['latest_stress_level'] ?? null];
                        ?>
                        <span class="badge <?= $color ?> text-white rounded-pill px-3 py-2"><i class="fas fa-<?= $icon ?> me-1"></i> <?= $mahasiswa['latest_stress_level'] ?? 'Belum ada' ?></span>
                    </td>
                    <td class="px-4 text-center">
                        <button class="btn btn-sm btn-info text-white rounded-3 px-3 shadow-sm" onclick="lihatDetail('<?= $mahasiswa['user_id'] ?>','<?= $mahasiswa['fullname'] ?>', '<?= $mahasiswa['username'] ?>', '<?= $mahasiswa['latest_stress_level'] ?? 'Belum ada' ?>')">
                            <i class="fas fa-eye me-1"></i> Detail
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <!-- TODO: Remove these after confirmed that the final UI is as designed -->
            <!-- <tr>
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
            </tr> -->
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
                        <label class="form-label text-muted small fw-bold">Email Kampus</label>
                        <input type="email" class="form-control rounded-3" id="regEmail" name="email" placeholder="Masukkan email..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Password</label>
                        <input type="password" class="form-control rounded-3" id="inputPassword" placeholder="Masukkan password..." required>
                    </div>

                    <div class="my-4">
                        <hr class="text-muted">
                        <p class="text-muted small mb-3"><i class="fas fa-brain text-danger me-1"></i> <strong>Data Awal untuk Prediksi AI</strong></p>
                    </div>

                    <div class="mb-3">
                        <label for="regMental" style="color:#b2bec3;"><i class="fas fa-heartbeat me-2"></i>Riwayat Kesehatan Mental</label>
                        <select class="form-select" id="regMental" name="riwayat_mental" required style="border-radius:12px; border:2px solid #e9ecef; font-size: 0.9rem;">
                            <option value="" disabled selected></option>
                            <option value="0">0 - Tidak Ada Riwayat</option>
                            <option value="1">1 - Ada Riwayat</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="regAkademik" style="color:#b2bec3;"><i class="fas fa-graduation-cap me-2"></i>Akademik Performance</label>
                        <select class="form-select" id="regAkademik" name="akademik_performa" required style="border-radius:12px; border:2px solid #e9ecef; font-size: 0.9rem;;">
                            <option value="" disabled selected></option>
                            <option value="1">1 - Sangat Rendah</option>
                            <option value="2">2 - Rendah</option>
                            <option value="3">3 - Cukup</option>
                            <option value="4">4 - Tinggi</option>
                            <option value="5">5 - Sangat Tinggi</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="regSocial" style="color:#b2bec3;"><i class="fas fa-users me-2"></i>Dukungan Sosial Lingkungan</label>
                        <select class="form-select" id="regSocial" name="dukungan_sosial" required style="border-radius:12px; border:2px solid #e9ecef; font-size: 0.9rem;">
                            <option value="" disabled selected></option>
                            <option value="1">1 - Rendah</option>
                            <option value="2">2 - Cukup</option>
                            <option value="3">3 - Tinggi</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 justify-content-between">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanUser" class="btn btn-primary rounded-3 px-4" onclick="simpanUserBaru()"><i class="fas fa-save me-2"></i>Simpan Data</button>
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
                        <tbody id="riwayatBody" class="small">
                            <tr>
                                <td colspan="11" class="py-4 text-muted">Klik tombol Detail untuk memuat data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary rounded-3" onclick="gantiHalaman(-1)" id="btnPrev" disabled>
                        <i class="fas fa-chevron-left me-1"></i> Sebelumnya
                    </button>
                    <small class="text-muted mx-1" id="infoHalaman">Halaman 1 dari 1</small>
                    <button class="btn btn-sm btn-outline-secondary rounded-3" onclick="gantiHalaman(1)" id="btnNext" disabled>
                        Selanjutnya <i class="fas fa-chevron-right ms-1"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->endSection() ?>