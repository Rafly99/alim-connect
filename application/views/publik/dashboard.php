<!--  -->
<style>
.background-top {
    padding: 120px;
    background-image: url("<?php echo base_url('assets/image/homepage.svg'); ?>");
    background-size: cover;
    background-repeat: no-repeat;
}

@media only screen and (max-width: 600px) {
    .background-top {
        padding: 10px;
    }
}

@media only screen and (max-width: 768px) {
    .background-top {
        padding: 25px;
    }
}
</style>


<!-- bagian tagline aplikasi  -->
<div class="justify-content-center background-top">
    <h2 class="tagline">Perluas Wawasan Agama<br>Untuk Bekal Akhirat</h2>
    <p class="sub-judul">Bertanya kepada para asatidz yang telah teruji keilmuannya</p>
    <a href="<?php echo site_url('publik/select_ustad') ?>" class="btn-konsultasi"><span>Konsultasi sekarang
        </span></a>
</div>

<!-- topik -->
<div class="container">
    <div class="title-content">Topik</div>
    <div class="topik-content horizontal-scroll">
        <?php 
        $topics = [
            ["icon-aqidah.svg", "Aqidah"],
            ["icon-akhlaq.svg", "Akhlaq"],
            ["icon-fiqh.svg", "Fiqih <br>Ibadah"],
            ["icon-quran.svg", "Al-Qur'an"],
            ["icon-hadist.svg", "Hadits"],
            ["icon-ibadah.svg", "Ibadah"],
            ["icon-sejarah.svg", "Sejarah<br>Islam"],
            ["icon-ekonomi-islam.svg", "Ekonomi <br> Islam"],
            ["icon-pemerintahan-islam.svg", "Pemerintahan <br> Islam"],
            ["icon-tasawuf.svg", "Tasawuf"],
            ["icon-pemikiran-islam.svg", "Pemikiran <br> Islam"]
        ];

        foreach ($topics as $topic) { ?>
        <div class="text-center">
            <img src="<?php echo base_url('assets/image/'.$topic[0]); ?>" alt="" id="img-icon" class="icon">
            <div>
                <p class="topik-text text-secondary"><?php echo $topic[1]; ?></p>
            </div>
        </div>
        <?php } ?>
    </div>
</div>



<!-- artikel -->
<div class="container">
    <div class="title-content">Artikel Terbaru</div>
    <br>
    <div class="row">
        <?php if (!empty($results)): ?>
        <?php foreach ($results as $artikel): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <div class="card">
                <img src="<?php echo base_url('uploads/' . $artikel->banner); ?>" class="card-img-top"
                    alt="<?php echo  $artikel->judul_artikel; ?>">
                <div class="card-body">
                    <h2 class="card-title"><?php echo $artikel->judul_artikel; ?></h2>
                    <p class="card-jarak"><?php echo substr($artikel->isi_artikel, 0, 100); ?>...</p>
                    <a href="<?php echo site_url('publik/lihat_artikel/' . $artikel->id_artikel); ?>"
                        class="card-button">Baca Selengkapnya..</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p>Tidak ada artikel ditemukan.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Pertanyaan yg sering ditanyankan -->
<div class="container py-3 mb-5">
    <h4 class="title-content py-3">Pertanyaan yg sering ditanyakan</h4>
    <div class="list-group list-group-flush border-bottom">
        <a href="javascript:void(0);" class="list-group-item list-group-item-action"
            data-query="Ngaji aqidah sampai kapan?">Ngaji aqidah sampai kapan?</a>
        <a href="javascript:void(0);" class="list-group-item list-group-item-action"
            data-query="Faidah Tentang Keutamaan Ilmu">Faidah Tentang Keutamaan Ilmu</a>
        <a href="javascript:void(0);" class="list-group-item list-group-item-action"
            data-query="Hukum Asal Ibadah Adalah">Hukum Asal Ibadah Adalah</a>
        <a href="javascript:void(0);" class="list-group-item list-group-item-action"
            data-query="Keutamaan Membaca Al-Qur’an Setiap Hari">Keutamaan Membaca Al-Qur’an Setiap Hari</a>
        <a href="javascript:void(0);" class="list-group-item list-group-item-action"
            data-query="keutamaan zakat">keutamaan zakat</a>
    </div>
</div>
<br>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const listItems = document.querySelectorAll('.list-group-item-action');

    listItems.forEach(function(item) {
        item.addEventListener('click', function(event) {
            event.preventDefault();
            const query = item.getAttribute('data-query');
            window.location.href =
                `<?php echo site_url('publik/artikel'); ?>?search=${encodeURIComponent(query)}`;
        });
    });
});
</script>