<!DOCTYPE html>
<html>

<head>
    <title>Alim Connect - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?php echo base_url('assets/dist/css/styles_login.css'); ?>" rel="stylesheet">
    <link rel="icon" href="<?php echo base_url('assets/image/title-icon.png'); ?>" type="image/png">
</head>
<style>
#btn-green {
    background-color: #4ea8a7;
    color: white;
}

#btn-green:hover {
    background-color: #428a89;
    color: white;
}
</style>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 bg-image"></div>

            <div class="col-md-4 bg-light bg-login">
                <div class="login-form d-flex align-items-center justify-content-center h-100">
                    <div class="w-75">
                        <h3 class="text-center mb-4">Login/Daftar</h3>
                        <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                        <?php endif; ?>

                        <form method="post" action="<?php echo site_url('auth/register_action'); ?>">
                            <div class="mb-1">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap:</label>
                                <input type="text" class="form-control shadow-sm rounded-pill" id="nama_lengkap"
                                    placeholder="Masukan Nama" name="nama_lengkap">
                            </div>
                            <div class="mb-1">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control shadow-sm rounded-pill" name="username"
                                    id="username" placeholder="Masukan Username" required>
                            </div>
                            <div class="mb-1">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control shadow-sm rounded-pill" id="email"
                                    placeholder="Masukan Email" name="email">
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control shadow-sm rounded-pill" name="password"
                                    id="password" placeholder="Masukan Password" required>
                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer"
                                    onclick="togglePassword()">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                </span>
                            </div>
                            <div class="mb-3 justify-content-center text-center">
                                <button type="submit" class="btn rounded-pill shadow-sm w-100 mb-2"
                                    id="btn-green">Daftar</button>
                                sudah memiliki akun?<a href="<?php echo site_url('auth/login'); ?>">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pZfyxV4hvAN/tIAV6UdfD2Mfh0ODAI2aMzJ5ENtHjbs8asXWTxLOr5qr5XEv5aZ5" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordIcon.classList.remove('bi-eye-slash');
            togglePasswordIcon.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            togglePasswordIcon.classList.remove('bi-eye');
            togglePasswordIcon.classList.add('bi-eye-slash');
        }
    }
    </script>
</body>

</html>