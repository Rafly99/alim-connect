<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
</head>

<body>
    <!-- Input User Modal -->
    <div class="modal fade" id="InputUser" tabindex="-1" aria-labelledby="InputUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="btn-hijau">
                    <h5 class="modal-title" id="InputUserLabel">Input User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo site_url('admin/tambah_user'); ?>" method="post">
                        <div class=" mb-3 row">
                            <label for="nama_lengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_lengkap" placeholder="Masukan Nama"
                                    name="nama_lengkap">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" placeholder="Masukan Username"
                                    name="username">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" placeholder="Masukan Email"
                                    name="email">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="pwd" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="pwd" placeholder="Masukan Password"
                                        name="password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-fill" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="role" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select name="role" id="role" class="form-select">
                                    <option value="admin">Admin</option>
                                    <option value="ustad">Ustad</option>
                                    <option value="publik">Publik</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row d-none" id="bidangInput">
                            <label for="bidang" class="col-sm-2 col-form-label">Pakar</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bidang"
                                    placeholder="Masukan Spesialisasi anda" name="bidang">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" value="Submit" class="btn" id="btn-hijau">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="btn-hijau">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="id_user" name="id_user" value="">
                        <div class="mb-3 row">
                            <label for="nama_lengkap_m" class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_lengkap_m" placeholder="Masukan Nama"
                                    name="nama_lengkap_m">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="username_m" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username_m" placeholder="Masukan Username"
                                    name="username_m">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email_m" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email_m" placeholder="Masukan Email"
                                    name="email_m">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="password_m" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_m"
                                        placeholder="Masukan Password" name="password_m">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordEdit">
                                        <i class="bi bi-eye-fill" id="toggleIconEdit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="role_m" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select name="role_m" id="role_m" class="form-select">
                                    <option value="admin">Admin</option>
                                    <option value="ustad">Ustad</option>
                                    <option value="publik">Publik</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row" id="bidangUpdate">
                            <label for="bidang_m" class="col-sm-2 col-form-label">Pakar</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bidang_m"
                                    placeholder="Masukan Spesialisasi anda" name="bidang_m">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" value="Submit" class="btn" id="btn-hijau">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Input Artikel Modal -->
    <div class="modal fade" id="InputArtikel" tabindex="-1" aria-labelledby="InputArtikelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-white" id="btn-hijau">
                    <h4 class=" modal-title" id="InputArtikelLabel">Input Artikel</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="<?php echo site_url('admin/tambah_artikel'); ?>" method="post"
                        enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label for="banner" class="col-sm-2 col-form-label">Banner</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="file" class="form-control" id="banner" name="banner">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="judul" placeholder="Masukan Judul"
                                    name="judul">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="topik" class="col-sm-2 col-form-label">Topik</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="topik" placeholder="Masukan topik"
                                    name="topik">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="mb-2">Isi Artikel:</label>
                            <textarea rows="5" class="form-control" id="deskripsi" placeholder="Masukan artikel"
                                name="deskripsi"></textarea>
                        </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn" id="btn-hijau">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Artikel Modal -->
    <div class="modal fade" id="EditArtikel" tabindex="-1" aria-labelledby="EditArtikelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-white" id="btn-hijau">
                    <h4 class="modal-title" id="EditArtikelLabel">Edit Artikel</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="editArtikelForm">
                        <input type="hidden" id="id_artikel_m" name="id_artikel_m" value="">
                        <div class="mb-3 row">
                            <label for="banner_m" class="col-sm-2 col-form-label">Banner</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="file" class="form-control" id="banner_m" name="banner_m">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="judul_m" class="col-sm-2 col-form-label">Judul</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="judul_m" placeholder="Masukan Judul"
                                    name="judul_m">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="topik_m" class="col-sm-2 col-form-label">Topik</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="topik_m" placeholder="Masukan topik"
                                    name="topik_m">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi_m" class="mb-2">Isi Artikel:</label>
                            <textarea rows="5" class="form-control" id="deskripsi_m" placeholder="Masukan artikel"
                                name="deskripsi_m"></textarea>
                        </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn" id="btn-hijau">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CKEDITOR untuk mengubah tampilan textarea -->
    <script>
    ClassicEditor
        .create(document.querySelector('#deskripsi'))
        .catch(error => {
            console.error(error);
        });
    </script>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('assets/dist/js/script.js'); ?>"></script>

<!-- menampilkan input pakar jika role ustad -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var roleSelect = document.getElementById('role');
    var topikInput = document.getElementById('bidangInput');

    // Sembunyikan input topik saat halaman dimuat
    topikInput.classList.add('d-none');

    // Tampilkan atau sembunyikan input topik berdasarkan pilihan role
    roleSelect.addEventListener('change', function() {
        if (roleSelect.value === 'ustad') {
            topikInput.classList.remove('d-none');
        } else {
            topikInput.classList.add('d-none');
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var roleSelect = document.getElementById('role_m');
    var bidangInput = document.getElementById('bidangUpdate');

    // Tampilkan atau sembunyikan input topik berdasarkan pilihan role
    roleSelect.addEventListener('change', function() {
        if (roleSelect.value === 'ustad') {
            bidangInput.classList.remove('d-none');
        } else {
            bidangInput.classList.add('d-none');
        }
    });

});
</script>

<script>
// script untuk update data user yg ada di modals
$('#editUserForm').on('submit', function(e) {
    e.preventDefault();
    updateUserData();
});

function updateUserData() {
    var formData = {
        id_user: $('#id_user').val(),
        nama_lengkap: $('#nama_lengkap_m').val(),
        username: $('#username_m').val(),
        email: $('#email_m').val(),
        bidang: $('#bidang_m').val(),
        role: $('#role_m').val()
    };

    var password = $('#password_m').val();
    if (password) {
        formData.password = password; // Only add password to the data if it is not empty
    }

    $.ajax({
        url: '<?php echo base_url('admin/update_user'); ?>',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response === 'success') {
                $('#editUserModal').modal('hide');
                location.reload(); // Reload page to reflect changes
            } else {
                alert('Failed to update user data');
            }
        }
    });
}

// update data artikels yg ada di modals
$(document).ready(function() {
    $('#editArtikelForm').on('submit', function(e) {
        e.preventDefault();
        updateArtikelData();
    });
});

function updateArtikelData() {
    var formData = new FormData();
    formData.append('id_artikel', $('#id_artikel_m').val());
    formData.append('judul_artikel', $('#judul_m').val());
    formData.append('topik', $('#topik_m').val());
    formData.append('deskripsi', deskripsiEditor.getData()); // Menggunakan deskripsiEditor.getData() untuk CKEditor 5

    var bannerFile = $('#banner_m')[0].files[0];
    if (bannerFile) {
        formData.append('banner', bannerFile);
    }

    $.ajax({
        url: '<?php echo base_url('admin/update_artikel'); ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response === 'success') {
                $('#EditArtikel').modal('hide');
                location.reload(); // Muat ulang halaman untuk memperbarui perubahan
            } else {
                alert('Gagal memperbarui data artikel ' + response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error: ' + textStatus + ' - ' + errorThrown);
        }
    });
}
</script>

</html>