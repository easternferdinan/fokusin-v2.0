<?= $this->extend('admin/layout_main') ?>

<?= $this->section('content') ?>
<div class="card p-0 overflow-hidden">
    <table class="table table-hover">
        <thead>
            <tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Detail</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-muted small">2023-11-25 14:30</td>
                <td>dev_master</td>
                <td><span class="badge bg-success">UPDATE</span></td>
                <td class="small">Mengubah threshold burnout</td>
            </tr>
            <tr>
                <td class="text-muted small">2023-11-25 14:15</td>
                <td>admin_fks</td>
                <td><span class="badge bg-danger">ALERT</span></td>
                <td class="small">Mengirim peringatan ke Salma</td>
            </tr>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>