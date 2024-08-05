<!-- Data table -->
<div class="container my-5">
    <div class="card">
        <div class="card-header text-white" id="btn-hijau">
            <h4>Data Pesan</h4>
        </div>
        <form method="post" action="<?php echo site_url('admin/hapus_pesan_terpilih'); ?>">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6 mb-2">
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete selected messages?')">Hapus
                            Terpilih</button>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search"
                            style="width: 200px;">
                    </div>
                </div>

                <div class="table-responsive">
                    <?php if (!empty($results) && is_array($results)) { ?>
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all"></th>
                                <th>No</th>
                                <th>Nama Pengirim</th>
                                <th>Pesan</th>
                                <th>Penerima</th>
                                <th>Waktu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <?php $no = 1; ?>
                            <?php foreach ($results as $pesan){ ?>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="ids[]"
                                        value="<?php echo $pesan->id_pesan; ?>"></td>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $pesan->nama_pengirim; ?></td>
                                <td><?php echo $pesan->pesan; ?></td>
                                <td><?php echo $pesan->nama_penerima; ?></td>
                                <td><?php echo $pesan->created_at; ?></td>
                                <td>
                                    <a href="<?php echo site_url('admin/hapus_pesan/'.$pesan->id_pesan); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this message?')"><i
                                            class="bi bi-trash"></i> </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="row mb-2">
                        <div class="col-12">
                            <div>Showing data <?php echo $current_count; ?> dari <?php echo $total_count; ?></div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="alert alert-info" role="alert">
                        No messages found.
                    </div>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
</div>


<footer class="footer text-center p-0">
    <div class="container p-3">
        <div>&copy; 2024 STMIK Antar Bangsa. All rights reserved.</div>
    </div>
</footer>
</body>
<script>
document.getElementById('select_all').onclick = function() {
    var checkboxes = document.querySelectorAll('.checkbox');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
}
</script>

</html>