<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-wrap gap-2 mb-3">
    <select id="filterLevel" class="form-select form-select-sm rounded-pill" style="width: auto;">
        <option value="">Semua Level</option>
        <option value="INFO">INFO</option>
        <option value="WARNING">WARNING</option>
        <option value="ERROR">ERROR</option>
        <option value="CRITICAL">CRITICAL</option>
        <option value="DEBUG">DEBUG</option>
    </select>
    <input type="text" id="filterEventType" class="form-control form-control-sm rounded-pill" placeholder="Tipe Aksi" style="width: 160px;">
    <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="terapkanFilter()"><i class="fas fa-search me-1"></i>Terapkan</button>
</div>

<div class="card p-0 overflow-hidden">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Username</th>
                <th>Aksi</th>
                <th>Detail</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="logBody">
            <tr class="text-center">
                <td colspan="5" class="py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Memuat data...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <button id="btnPrev" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="gantiHalaman(-1)" disabled>
        <i class="fas fa-chevron-left me-1"></i>Sebelumnya
    </button>
    <span id="infoHalaman" class="text-muted small">—</span>
    <button id="btnNext" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="gantiHalaman(1)" disabled>
        Selanjutnya<i class="fas fa-chevron-right ms-1"></i>
    </button>
</div>

<!-- Modal Extra Data -->
<div class="modal fade" id="modalExtraData" tabindex="-1" aria-labelledby="modalExtraDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modalExtraDataLabel">Detail Data Tambahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <pre id="extraDataContent" class="bg-light p-3 rounded-3 mb-0" style="max-height: 60vh; overflow-y: auto; font-size: 0.875rem;"></pre>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 justify-content-end">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    let modalExtraDataInstance = null;
    let halamanAktif = 1;
    const ukuranHalaman = 20;
    const ukuranFetch = 21;
    const _extraDataStore = [];
    let _extraDataIdx = 0;

    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('modalExtraData');
        if (el) modalExtraDataInstance = new bootstrap.Modal(el);
        fetchLogs();
    });

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    async function fetchLogs() {
        const tbody = document.getElementById('logBody');
        tbody.innerHTML = '<tr><td colspan="5" class="py-4 text-center"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Memuat data...</td></tr>';

        const level = document.getElementById('filterLevel').value;
        const eventType = document.getElementById('filterEventType').value.trim();
        const skip = (halamanAktif - 1) * ukuranHalaman;

        let url = `/admin/audit-logs?limit=${ukuranFetch}&skip=${skip}`;
        if (level) url += `&level=${encodeURIComponent(level)}`;
        if (eventType) url += `&event_type=${encodeURIComponent(eventType)}`;

        _extraDataIdx = 0;

        try {
            const res = await fetch(url);
            const json = await res.json();
            console.log(json);

            if (json.status === 200 && json.data) {
                const rows = json.data;
                const adaHalamanBerikut = rows.length === ukuranFetch;
                const ditampilkan = adaHalamanBerikut ? rows.slice(0, ukuranHalaman) : rows;

                if (ditampilkan.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="py-4 text-muted">Tidak ada data log.</td></tr>';
                } else {
                    const levelBadge = {
                        INFO: 'bg-success',
                        WARNING: 'bg-warning text-dark',
                        ERROR: 'bg-danger',
                        CRITICAL: 'bg-danger',
                        DEBUG: 'bg-secondary',
                    };

                    let html = '';
                    ditampilkan.forEach(function(log) {
                        const badgeClass = levelBadge[log.level] || 'bg-secondary';
                        const tgl = log.created_at ?
                            new Date(log.created_at).toLocaleString('id-ID', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            }) :
                            '-';
                        const hasExtra = log.extra_data && typeof log.extra_data === 'object' && Object.keys(log.extra_data).length > 0;
                        const idx = _extraDataIdx++;
                        if (hasExtra) _extraDataStore[idx] = log.extra_data;

                        html += '<tr>';
                        html += '<td class="text-muted small">' + tgl + '</td>';
                        html += '<td>' + escapeHtml(log.username || '-') + '</td>';
                        html += '<td><span class="badge ' + badgeClass + '">' + escapeHtml(log.event_type || '-') + '</span></td>';
                        html += '<td class="small">' + escapeHtml(log.message || '') + '</td>';
                        html += '<td class="text-center">';
                        if (hasExtra) {
                            html += '<button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="lihatExtraData(' + idx + ')" title="Lihat detail data"><i class="fas fa-eye"></i></button>';
                        }
                        html += '</td></tr>';
                    });
                    tbody.innerHTML = html;
                }

                document.getElementById('infoHalaman').innerText = 'Halaman ' + halamanAktif;
                document.getElementById('btnPrev').disabled = halamanAktif <= 1;
                document.getElementById('btnNext').disabled = !adaHalamanBerikut;
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="py-4 text-muted">Gagal memuat data log.</td></tr>';
            }
        } catch (e) {
            tbody.innerHTML = '<tr><td colspan="5" class="py-4 text-danger">Terjadi kesalahan saat memuat data.</td></tr>';
        }
    }

    function gantiHalaman(arah) {
        halamanAktif += arah;
        if (halamanAktif < 1) halamanAktif = 1;
        fetchLogs();
    }

    function terapkanFilter() {
        halamanAktif = 1;
        fetchLogs();
    }

    function lihatExtraData(idx) {
        const data = _extraDataStore[idx];
        const pre = document.getElementById('extraDataContent');
        if (data) {
            pre.textContent = JSON.stringify(data, null, 2);
        } else {
            pre.textContent = '—';
        }
        if (modalExtraDataInstance) modalExtraDataInstance.show();
    }
</script>
<?= $this->endSection() ?>