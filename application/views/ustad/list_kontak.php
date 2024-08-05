<!-- tampilan list kontak chat -->


<div class="col-md-3">
    <div class="sidebar">
        <div class="input-group py-3">
            <a href="<?php echo site_url('auth/logout') ?>"
                class="btn btn-danger d-flex justify-content-center align-items-center rounded-circle mr-2"
                alt="Logout">
                <i class="bi bi-person-fill-lock"></i>
            </a>
            <div class="input-group-prepend flex-fill">
                <input type="text" class="form-control rounded-pill" id="search-input" placeholder="Search...">
                <button class="btn btn-light rounded-circle"><i class="bi bi-search"></i></button>
            </div>
        </div>

        <div id="search-results" class="list-group list-group-flush border-bottom"></div>
        <div id="contact-list">
            <?php if (!empty($jumlah_pesan_id)) : ?>
            <div class="list-group list-group-flush border-bottom">
                <?php foreach ($jumlah_pesan_id as $sender) : ?>
                <a href="<?php echo site_url('ustad/view_message/' . $sender->pengirim_id); ?>"
                    class="list-group-item list-group-item-action">
                    <img src="<?php echo base_url('assets/image/profile.png');?>" alt="Foto Profile"
                        class="rounded-circle border border-success mr-2" style="width: 30px; height: 30px;">
                    <strong id="pengirim_nama" name="pengirim_nama"><?php echo $sender->pengirim_nama; ?></strong>
                    <?php if ($sender->unread_count > 0) : ?>
                    <span class="badge badge-success rounded-circle float-right"
                        style="background-color: #4ea8a7; color: white;">
                        <?php echo $sender->unread_count; ?>
                    </span>

                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
            <p>Tidak ada pesan masuk.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi Pusher
    var pusher = new Pusher('8757e07d52d81616f1bb', {
        cluster: 'ap1',
        authEndpoint: '<?php echo base_url('ustad/auth'); ?>',
        auth: {
            headers: {
                'X-CSRF-Token': '<?php echo $this->security->get_csrf_hash(); ?>' // Jika menggunakan CSRF token
            }
        }
    });

    // Subscribe ke channel publik
    var channel = pusher.subscribe('public-chat');

    // Bind event ke channel
    channel.bind('my-event', function(data) {
        // Update daftar kontak
        loadContactList();
    });

    // Fungsi untuk memuat dan memperbarui daftar kontak
    function loadContactList() {
        $.ajax({
            url: '<?php echo site_url('ustad/update_contact_list'); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var contactList = $('#contact-list .list-group');
                contactList.empty();

                $.each(response.jumlah_pesan_id, function(index, sender) {
                    var newItem =
                        '<a href="<?php echo site_url('ustad/view_message/'); ?>' + sender
                        .pengirim_id +
                        '" class="list-group-item list-group-item-action" id="contact-' +
                        sender.pengirim_id + '">' +
                        '<img src="<?php echo base_url('assets/image/profile.png');?>" alt="Foto Profile" class="rounded-circle border border-success mr-2" style="width: 30px; height: 30px;">' +
                        '<strong id="pengirim_nama" name="pengirim_nama">' + sender
                        .pengirim_nama + '</strong>' +
                        (sender.unread_count > 0 ?
                            '<span class="badge badge-success rounded-circle float-right" style="background-color: #4ea8a7; color: white;">' +
                            sender.unread_count + '</span>' : '') +
                        '</a>';
                    contactList.append(newItem);
                });
            },
            error: function() {
                console.log('Error loading contact list');
            }
        });
    }

    // Initial load
    loadContactList();
});
</script>


<script>
$(document).ready(function() {
    $('#search-input').on('input', function() {
        var keyword = $(this).val();

        if (keyword.length > 2) {
            $.ajax({
                url: '<?php echo site_url('ustad/search'); ?>',
                type: 'POST',
                data: {
                    keyword: keyword
                },
                success: function(response) {
                    var results = JSON.parse(response);
                    var resultsHtml = '';

                    if (results.length > 0) {
                        $.each(results, function(index, result) {
                            resultsHtml +=
                                '<a href="<?php echo site_url('ustad/view_message/'); ?>' +
                                result.pengirim_id +
                                '" class="list-group-item list-group-item-action">';
                            resultsHtml +=
                                '<img src="<?php echo base_url('assets/image/profile.png');?>" alt="" class="rounded-circle mr-2" style="width: 30px; height: 30px;">';
                            resultsHtml += '<strong>' + result.pengirim_nama +
                                '</strong><br>';
                            resultsHtml += '<span>' + result.pesan +
                                '</span><br>';
                            resultsHtml += '<small>' + result.created_at +
                                '</small>';
                            resultsHtml += '</a>';
                        });
                    } else {
                        resultsHtml = '<p>No results found</p>';
                    }

                    $('#search-results').html(resultsHtml);
                }
            });
        } else {
            $('#search-results').html('');
        }
    });
});
</script>