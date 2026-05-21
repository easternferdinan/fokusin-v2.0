<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>
    <div class="overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>
    
    <div class="admin-wrapper active">
        <aside class="sidebar-main" id="sidebarMain">
            <div class="brand d-flex align-items-center gap-2">
                <img src="<?= base_url('assets/img/logo-fokusin.png') ?>" alt="Logo Fokusin" class="logo-fokusin">
                <span>
                    FOKUSIN 
                    <span class="role-indicator <?= ($role === 'superadmin') ? 'role-superadmin' : 'role-admin' ?>">
                        <?= strtoupper($role) ?>
                    </span>
                </span>
            </div>
            
            <div class="nav-menu">
                <?php if($role === 'superadmin'): ?>
                    <a href="<?= base_url('admin/roles') ?>" class="nav-link-admin <?= url_is('admin/roles') ? 'active' : '' ?>"><i class="fas fa-key"></i> Role Management</a>
                    <a href="<?= base_url('admin/config') ?>" class="nav-link-admin <?= url_is('admin/config') ? 'active' : '' ?>"><i class="fas fa-cogs"></i> Konfigurasi Sistem</a>
                    <a href="<?= base_url('admin/audit') ?>" class="nav-link-admin <?= url_is('admin/audit') ? 'active' : '' ?>"><i class="fas fa-history"></i> Audit Log</a>
                <?php else: ?>
                    <a href="<?= base_url('admin') ?>" class="nav-link-admin <?= url_is('admin') ? 'active' : '' ?>"><i class="fas fa-users"></i> Data Pengguna</a>
                    <a href="<?= base_url('admin/stress') ?>" class="nav-link-admin <?= url_is('admin/stress') ? 'active' : '' ?>"><i class="fas fa-chart-bar"></i> Pantau Stres</a>
                    <a href="<?= base_url('admin/alert') ?>" class="nav-link-admin <?= url_is('admin/alert') ? 'active' : '' ?>"><i class="fas fa-exclamation-triangle"></i> Tindak Lanjut Kritis</a>
                <?php endif; ?>
            </div>
            
            <div class="sidebar-footer">
                <a href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </div>
        </aside>

        <main class="content-main">
            <div class="topbar">
                <button class="btn btn-light d-md-none border-0 shadow-sm" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h5 class="m-0 fw-bold text-dark"><?= esc($title ?? 'Dashboard') ?></h5>
                </div>
            </div>

            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebarMain').classList.toggle('show');
            document.getElementById('mobileOverlay').classList.toggle('show');
        }
    </script>
    <?= $this->renderSection('js') ?>
</body>
</html>