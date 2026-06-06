<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
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
        <tbody>
            <?php if (empty($logs)): ?>
                <tr class="text-center">
                    <td colspan="5" class="py-4 text-muted">Tidak ada data log.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <?php
                    $badgeMap = [
                        'INFO' => 'bg-success',
                        'WARNING' => 'bg-warning',
                        'ERROR' => 'bg-danger',
                        'CRITICAL'  => 'bg-danger',
                        'DEBUG'  => 'bg-info',
                    ];
                    $badgeClass = $badgeMap[$log['level']] ?? 'bg-secondary';
                    $extra = $log['extra_data'] ?? null;
                    $hasExtra = !empty($extra) && is_array($extra);
                    ?>
                    <tr>
                        <td class="text-muted small"><?= date('d M Y, H:i', strtotime($log['created_at'])) ?></td>
                        <td><?= esc($log['username']) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= esc($log['event_type']) ?></span></td>
                        <td class="small"><?= esc($log['message']) ?></td>
                        <td class="text-center">
                            <?php if ($hasExtra): ?>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill" data-extra='<?= json_encode($extra) ?>' onclick="lihatExtraData(this)" title="Lihat detail data">
                                    <i class="fas fa-eye"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
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
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('modalExtraData');
        if (el) modalExtraDataInstance = new bootstrap.Modal(el);
    });

    function lihatExtraData(button) {
        const raw = button.getAttribute('data-extra');
        const pre = document.getElementById('extraDataContent');
        try {
            const obj = JSON.parse(raw);
            pre.textContent = JSON.stringify(obj, null, 2);
        } catch {
            pre.textContent = raw;
        }
        if (modalExtraDataInstance) modalExtraDataInstance.show();
    }
</script>
<?= $this->endSection() ?>