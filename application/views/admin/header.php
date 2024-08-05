<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/styles_admin.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" href="<?php echo base_url('assets/image/title-icon.png'); ?>" type="image/png">
</head>


<body>
    <nav class="navbar navbar-expand-lg shadow-sm bg-light navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="<?php echo base_url('assets/image/logo.svg'); ?>" alt="alim connect" id="img-logo"
                    class="d-inline-block align-top">
            </a>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav m-0">
                    <li class="nav-item px-3">
                        <a class="nav-link" href="<?php echo site_url('admin/dashboard'); ?>">Users</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="<?php echo site_url('admin/artikel'); ?>">Artikel</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="<?php echo site_url('admin/massage'); ?>">Massage</a>
                    </li>
                </ul>
            </div>
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-danger btn-sm ms-auto">Logout</a>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url('assets/dist/js/script_admin.js'); ?>"></script>

    <script>
    // untuk mengambil data user dan menampilkannya di dalam modals
    $(document).ready(function() {
        $('.editUserBtn').on('click', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            fetchUserData(userId);
        });
    });

    function fetchUserData(userId) {
        $.ajax({
            url: '<?php echo base_url('admin/get_user_data'); ?>',
            type: 'GET',
            data: {
                id: userId
            },
            success: function(response) {
                var user = JSON.parse(response);
                $('#id_user').val(user.id_user);
                $('#nama_lengkap_m').val(user.nama_lengkap);
                $('#username_m').val(user.username);
                $('#email_m').val(user.email);
                $('#bidang_m').val(user.bidang);
                $('#role_m').val(user.role);
                $('#editUserModal').modal('show');
            }
        });
    }

    // untuk mengambil data artikels dan menampilkannya ke dalam modals
    let deskripsiEditor;

    $(document).ready(function() {
        $('.editArtikelBtn').on('click', function(e) {
            e.preventDefault();
            var artikelId = $(this).data('id');
            fetchArtikelData(artikelId);
        });

        // Gantikan <textarea id="deskripsi_m"> dengan instance CKEditor
        ClassicEditor
            .create(document.querySelector('#deskripsi_m'))
            .then(editor => {
                deskripsiEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });
    });

    function fetchArtikelData(artikelId) {
        $.ajax({
            url: '<?php echo base_url('admin/get_artikel_data'); ?>',
            type: 'GET',
            data: {
                id: artikelId
            },
            success: function(response) {
                console.log(response);
                var artikel = JSON.parse(response);
                $('#id_artikel_m').val(artikel.id_artikel);
                $('#judul_m').val(artikel.judul_artikel);
                $('#topik_m').val(artikel.topik);
                // Update CKEditor dengan isi artikel
                if (deskripsiEditor) {
                    deskripsiEditor.setData(artikel.isi_artikel);
                }
                // Gambar tidak diisi kembali karena input type="file" tidak mendukung pengisian default
                $('#EditArtikel').modal('show');
            }
        });
    }
    </script>