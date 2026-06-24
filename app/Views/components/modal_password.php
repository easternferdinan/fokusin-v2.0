<div class="modal fade" id="modalPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalPasswordLabel">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <div id="oldPasswordGroup" class="mb-3">
                    <label class="form-label fw-semibold">Password Lama</label>
                    <input type="password" name="old_password" class="form-control rounded-3" id="inputOldPassword" placeholder="Masukkan password lama..." autocomplete="current-password">
                    <div class="invalid-feedback" id="errorOldPassword"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Baru</label>
                    <input type="password" name="new_password" class="form-control rounded-3" id="inputNewPassword" placeholder="Minimal 8 karakter..." autocomplete="new-password">
                    <div class="invalid-feedback" id="errorNewPassword"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="form-control rounded-3" id="inputConfirmPassword" placeholder="Ulangi password baru..." autocomplete="new-password">
                    <div class="invalid-feedback" id="errorConfirmPassword">Password tidak cocok</div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal" id="btnBatalPassword">Batal</button>
                <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" id="btnSubmitPassword" onclick="submitPasswordChange()">
                    <i class="fas fa-save me-1"></i> <span id="btnPasswordText">Ganti Password</span>
                </button>
            </div>
        </div>
    </div>
</div>
