<div class="container my-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" id="btn-hijau">
            <h4>Data User</h4>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6 mb-2">
                    <button type="button" class="btn btn-sm" id="btn-hijau" data-bs-toggle="modal"
                        data-bs-target="#InputUser">Tambah User</button>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search" style="width: 200px;">
                </div>
            </div>

            <div class="table-responsive">
                <?php if ($results): ?>
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        <?php $no = 1; ?>
                        <?php foreach ($results as $users): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $users->nama_lengkap; ?></td>
                            <td><?php echo $users->username; ?></td>
                            <td><?php echo $users->email; ?></td>
                            <td><?php echo $users->role; ?></td>
                            <td>
                                <a href="" class="btn btn-warning btn-sm editUserBtn" data-bs-toggle="modal"
                                    data-id="<?php echo $users->id_user; ?>" data-bs-target="#editUserModal">Edit</a>
                                <a class="btn btn-danger btn-sm mt-1"
                                    href="<?php echo site_url('admin/hapus_user/'.$users->id_user); ?>"
                                    onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
                <div class="row mb-2">
                    <div class="col-6">
                        <div>Showing data <?php echo $current_count; ?> dari <?php echo $total_count; ?></div>
                    </div>
                    <div class="col-6">
                        <div><?php echo $pagination; ?> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('admin/modal_view'); ?>

<footer class="footer p-0">
    <div class="container p-3">
        <div>&copy; 2024 STMIK Antar Bangsa. All rights reserved.</div>
    </div>
</footer>

<script src="<?php echo base_url('assets/dist/js/script_admin.js'); ?>"></script>
</body>

</html>