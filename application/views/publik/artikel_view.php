<style>
.card-img-top {
    height: 200px;
    object-fit: cover;
}

.content-artikel img {
    width: 100%;
    height: auto;
    border-radius: 10px;
}

.content-artikel p {
    text-align: justify;
    font-family: Geneva, Verdana, sans-serif;
    color: #3b3b3b;
    font-size: 15px;
}

.content-artikel {
    margin-top: 0;
}

.content-artikel .date {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 0;
}

.related-article {
    display: flex;
    margin-bottom: 15px;
}

.related-article img {
    width: 120px;
    height: 70px;
    border-radius: 5px;
    object-fit: cover;
    margin-right: 10px;
}

.related-article p {
    margin: 0;
    color: black;
    font-size: 16px;
    font-weight: 600;
    text-decoration
}

a {
    text-decoration: none;
}
</style>


<body>
    <div class="container my-4">
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="content-artikel">
                    <h3 class="mb-2"><?php echo $artikel->judul_artikel; ?></h3>
                    <?php
                        $created_at = strtotime($artikel->created_at);
                        echo '<p class="date mb-4"><i class="bi bi-calendar-week"></i> ' . date('l, j F Y', $created_at) . '</p>';
                        ?>
                    <img src="<?php echo base_url('uploads/' . $artikel->banner); ?>" class="img-fluid" alt="Banner">
                    <p><?php echo $artikel->isi_artikel; ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <h5 class="mb-4">Artikel Terbaru</h5>
                <?php if (!empty($artikel_terkait)): ?>
                <?php foreach ($artikel_terkait as $artikel): ?>
                <a href="<?php echo site_url('publik/lihat_artikel/' . $artikel->id_artikel); ?>"
                    class="related-article">
                    <img src="<?php echo base_url('uploads/' . $artikel->banner); ?>"
                        alt="<?php echo $artikel->judul_artikel; ?>">
                    <div>
                        <p><?php echo $artikel->judul_artikel; ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
                <?php else: ?>
                <p>Tidak ada artikel terkait ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">

        </div>
    </div>