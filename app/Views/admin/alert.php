<?php

/**
 * @var int $stressThreshold
 * @var int $stressThresholdFrequency
 */
?>

<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="card border-0 p-4 mb-3" style="border-left: 5px solid #fdcb6e !important; background: #fffef5;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h6 class="fw-bold mb-1" style="color: #e17055;">
                <i class="fas fa-sliders-h me-2"></i>Parameter Peringatan
            </h6>
            <p class="text-muted small mb-0">Ambang batas deteksi stres mahasiswa</p>
        </div>
        <div class="d-flex gap-4">
            <div class="text-center px-3 py-2 rounded-3" style="background: rgba(253, 203, 110, 0.3); min-width: 90px;">
                <div class="fw-bold" style="font-size: 1.5rem; color: #e17055; line-height: 1.2;"><?= $stressThreshold ?></div>
                <small class="text-muted" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Threshold</small>
            </div>
            <div class="text-center px-3 py-2 rounded-3" style="background: rgba(116, 185, 255, 0.15); min-width: 90px;">
                <div class="fw-bold" style="font-size: 1.5rem; color: #0984e3; line-height: 1.2;"><?= $stressThresholdFrequency ?><small class="ms-1" style="font-size: 1rem; font-weight: 400;">hari</small></div>
                <small class="text-muted" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Frequency</small>
            </div>
        </div>
    </div>
</div>

<?php if (empty($alertData)): ?>
    <div class="alert alert-success" role="alert">
        Belum ada mahasiswa yang memiliki tingkat stress di atas <?= $stressThreshold ?>.
    </div>
<?php else: ?>
    <?php foreach ($alertData as $alert) : ?>
        <div class="card border-0 p-4 mb-3" style="border-left: 5px solid #ff7675 !important; background: #fff5f5;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="fw-bold text-danger"><i class="fas fa-user-injured me-2"></i><?= $alert['fullname'] ?></h6>
                    <p class="text-muted small mb-2">Tingkat stres <strong>"<?= strtoupper($alert['latest_stress_level']) ?>"</strong> selama <strong><?= $alert['consecutive_stress_days'] ?> hari</strong> berturut-turut.</p>
                    <button class="btn btn-sm btn-danger rounded-pill px-3" onclick="kirimAlert('<?= $alert['user_id'] ?>', '<?= $stressThreshold ?>', '<?= $stressThresholdFrequency ?>', this)"><i class="fas fa-bell me-1"></i>Kirim Peringatan</button>
                </div>
                <span class="badge bg-danger"><i class="fas fa-bell"></i></span>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->endSection() ?>