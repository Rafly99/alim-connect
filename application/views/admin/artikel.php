<!-- Data table -->
<div class="container my-5">
    <div class="card">
        <div class="card-header text-white" id="btn-hijau">
            <h4>Data Artikel</h4>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6 mb-2">
                    <button type="button" class="btn btn-sm" id="btn-hijau" data-bs-toggle="modal"
                        data-bs-target="#InputArtikel">Tambah Artikel</button>
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
                            <th>Banner</th>
                            <th>Judul Artikel</th>
                            <th>Topik</th>
                            <th>Deskripsi</th>
                            <th>Tgl Post</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        <?php $no = 1; ?>
                        <?php foreach ($results as $artikel): ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td>
                                <img src="<?php echo base_url('uploads/' . $artikel->banner); ?>" alt="Banner"
                                    style="width: 100px; height: auto;">
                            </td>
                            <td><?php echo $artikel->judul_artikel; ?></td>
                            <td><?php echo $artikel->topik; ?></td>
                            <td><?php echo substr($artikel->isi_artikel, 0, 100) . (strlen($artikel->isi_artikel) > 100 ? '...' : ''); ?>
                            </td>
                            <td><?php echo $artikel->created_at; ?></td>
                            <td>
                                <a class="editArtikelBtn btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-id="<?php echo $artikel->id_artikel; ?>" data-bs-target="#EditArtikel">Edit</a>
                                <a class="btn btn-danger btn-sm mt-1"
                                    href="<?php echo site_url('admin/hapus_artikel/'.$artikel->id_artikel); ?>"
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
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/modal_view'); ?>

<div class="footer text-center p-0">
    <div class="container p-3">
        <div>&copy; 2024 STMIK Antar Bangsa. All rights reserved.</div>
    </div>
</div>
</body>

</html>