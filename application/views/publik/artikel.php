<style>
.list-group-item {
    cursor: pointer;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.list-group-item h5 {
    font-size: 16px;
}

.list-group-item img {
    height: 80px;
    width: 138px;
    object-fit: cover;
    margin-right: 15px;
}

.row {
    margin-bottom: 30px;
}
</style>

<div class="container my-5">
    <div class="row">
        <div class="col-12 col-md-9">
            <h3 class="text-center mb-4">Artikel Terbaru</h3>
            <form class="mb-4" action="<?php echo site_url('publik/search'); ?>" method="get">
                <div class="input-group">
                    <input type="text" class="form-control rounded-pill" name="query" id="search-input"
                        placeholder="Cari artikel berdasarkan judul, topik" value="<?php echo set_value('query'); ?>">
                    <button class="btn rounded-pill ms-1" id="btn-search" type="submit">
                        <i class="bi bi-search"></i></button>
                </div>
            </form>
            <div class="list-group list-group-flush">
                <?php if (!empty($results)): ?>
                <?php foreach ($results as $artikel): ?>
                <div class="list-group-item d-flex align-items-center mx-0"
                    onclick="location.href='<?php echo site_url('publik/lihat_artikel/' . $artikel->id_artikel); ?>'">
                    <img src="<?php echo base_url('uploads/' . $artikel->banner); ?>"
                        alt="<?php echo htmlspecialchars($artikel->judul_artikel, ENT_QUOTES, 'UTF-8'); ?>"
                        class="rounded">
                    <div>
                        <h5 class="mb-1"><?php echo htmlspecialchars($artikel->judul_artikel, ENT_QUOTES, 'UTF-8'); ?>
                        </h5>
                        <p class="mb-1">
                            <?php echo substr($artikel->isi_artikel, 0, 75); ?>...</p>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p class="text-center">Tidak ada artikel ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-12 col-md-9">
            <?php echo $pagination; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('search');

    if (searchQuery) {
        const searchInput = document.getElementById('search-input');
        searchInput.value = searchQuery;
    }
});
</script>