<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Fokusin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card text-center">
            <div class="mb-4 text-center">
                <img src="<?= base_url('assets/img/logo-fokusin.png') ?>" alt="Logo Fokusin" class="logo-fokusin mb-2" style="width: 60px; height: 60px;">
                <h3 class="fw-bold mt-2">FOKUSIN <span class="badge bg-dark">ADMIN</span></h3>
                <p class="text-muted small">Masuk ke panel manajemen sistem</p>
            </div>
            <form action="<?= base_url('auth/adminLoginProcess') ?>" method="POST">
                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold small">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" name="username" class="form-control border-start-0 rounded-end-pill" placeholder="admin / superadmin" required>
                    </div>
                </div>
                <div class="mb-4 text-start">
                    <label class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 rounded-end-pill" placeholder="Password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold py-2 shadow-sm">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </form>
        </div>
    </div>
</body>

<script>
    <?php $successFlash = session()->getFlashdata('success'); ?>
    <?php if ($successFlash !== null): ?>
        Swal.fire({
            icon: 'success',
            title: <?= json_encode($successFlash['title'] ?? 'Sukses') ?>,
            text: <?= json_encode($successFlash['message'] ?? 'Operasi Berhasil!') ?>,
            confirmButtonColor: '#00b894'
        });
    <?php endif; ?>

    <?php $errorFlash = session()->getFlashdata('error'); ?>
    <?php if ($errorFlash !== null): ?>
        Swal.fire({
            icon: 'error',
            title: <?= json_encode($errorFlash['title'] ?? 'Terjadi Kesalahan') ?>,
            text: <?= json_encode($errorFlash['message'] ?? 'Hubungi Admin!') ?>,
            confirmButtonColor: '#ff7675'
        });

        <?php if (isset($errorFlash['detail']) && ENVIRONMENT !== 'production'): ?>
            console.error(<?= json_encode($errorFlash['detail']) ?>);
        <?php endif; ?>
    <?php endif; ?>
</script>

</html>