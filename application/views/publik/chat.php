<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/styles_publik.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Alim Connect</title>
    <link rel="icon" href="<?php echo base_url('assets/image/title-icon.png'); ?>" type="image/png">
</head>

<body>

    <!-- chat -->
    <?php
$last_date = null;
?>

    <div class="container-fluid ">
        <div class="row header-chat">
            <div class="col-md p-0">
                <div class="d-flex align-items-center pe-3 py-1 border-bottom">
                    <a href="<?php echo base_url('publik/dashboard') ?>" class="btn my-0" alt="back"><i
                            class="bi bi-arrow-left-short icon-white"></i></a>
                    <img src="<?php echo base_url('assets/image/profile.png');?>" alt="Profile"
                        class="rounded-circle mr-3 img-profile" style="width: 50px; height: 50px;">
                    <div class="ps-3">
                        <strong><?php echo $sender_name; ?></strong>
                        <div class="status">
                            <?php echo ($status->status == 1) ? 'Online' : 'Offline'; ?>
                        </div>
                    </div>
                    <a href="<?php echo site_url('publik/delete_chat/'.$ustad_id); ?>"
                        onclick="return confirm('Apakah Anda yakin ingin mengakhiri percakapan?');"
                        class="btn rounded-pill ms-auto" alt="hapus chat" data-bs-toggle="tooltip"
                        title="Akhiri chat?"><i class="bi bi-trash-fill icon-white-delete"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="content" id="chat-content">
                    <?php if (!empty($messages)) : ?>
                    <?php foreach ($messages as $message) : ?>
                    <?php
                $current_date = date('Y-m-d', strtotime($message->created_at));
                if ($last_date !== $current_date) {
                    echo '<div class="date-separator">' . date('l, d M Y', strtotime($message->created_at)) . '</div>';
                    $last_date = $current_date;
                }
                ?>
                    <div
                        class="message <?php echo ($message->pengirim_id == $this->session->userdata('id_user')) ? 'message-right' : 'message-left'; ?>">
                        <div class="message-content">
                            <?php echo $message->pesan; ?>
                        </div>
                        <div class="message-time"><?php echo date('H:i', strtotime($message->created_at)); ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <div class="message">
                        <div class="message-content">
                            <p>Apa yang ingin anda tanyakan kepada <?php echo $sender_name; ?>?</p>
                            <?php if (!empty($default_messages)) : ?>
                            <ul id="default-messages">
                                <?php foreach ($default_messages as $default_message) : ?>
                                <li class="message-default" data-pesan="<?php echo $default_message->pesan_default; ?>">
                                    <?php echo $default_message->pesan_default; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-publik p-3 bg-light border-top">
        <form id="send-message-form" class="input-group">
            <input type="hidden" name="penerima_id" id="id_ustad" value="<?php echo $ustad_id; ?>">
            <input type="text" name="pesan" id="pesan" class="form-control" placeholder="Tulis pesan..." required>
            <div class="input-group-append">
                <button type="submit" class="btn btn-send" style="background-color: #4ea8a7; color: white;">
                    <i class="bi bi-send"></i></button>
            </div>
        </form>
    </div>
</body>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
// Inisialisasi Pusher
var pusher = new Pusher('8757e07d52d81616f1bb', {
    cluster: 'ap1',
    authEndpoint: '<?php echo base_url('publik/auth'); ?>',
    auth: {
        headers: {
            'X-CSRF-Token': '<?php echo $this->security->get_csrf_hash(); ?>' // Jika menggunakan CSRF token
        }
    }
});
var channel = pusher.subscribe('public-chat');

// Ambil ID pengguna aktif dari PHP
var userId = <?php echo $this->session->userdata('id_user'); ?>;

channel.bind('my-event', function(data) {
    console.log(data); // Periksa format data yang diterima
    if (data.receiver_id == userId || data.sender_id == userId) {
        var messageClass = data.sender_id == userId ?
            'message-right' : 'message-left';
        var messageHTML = '<div class="message ' + messageClass + '">' +
            '<div class="message-content">' + (data.message ? data.message : 'No message') + '</div>' +
            '<div class="message-time">' + new Date(data.created_at).toLocaleTimeString() + '</div>' +
            '</div>';
        $('#chat-content').append(messageHTML);

        // Scroll ke pesan terbaru
        $('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);
    }
});
</script>


<!-- animasi header keatas -->
<script>
window.addEventListener('scroll', function() {
    var headerChat = document.querySelector('.header-chat');
    var scrollTop = window.scrollY;

    if (scrollTop > 68) { // Ganti dengan jarak scroll yang diinginkan
        headerChat.classList.add('scrolled');
    } else {
        headerChat.classList.remove('scrolled');
    }
});
</script>


<!-- send message -->
<script>
$(document).ready(function() {
    // Fungsi untuk scroll ke bawah
    function scrollToBottom() {
        var chatContent = document.getElementById("chat-content");
        chatContent.scrollTop = chatContent.scrollHeight;
    }

    // Panggil fungsi scroll ke bawah saat halaman pertama kali dimuat
    scrollToBottom();

    // Tangkap submit form dengan ID send-message-form
    $('#send-message-form').on('submit', function(event) {
        event.preventDefault(); // Mencegah form untuk refresh

        // Ambil data dari form menggunakan serialize
        var formData = $(this).serialize();

        // Kirim data pesan menggunakan AJAX
        $.ajax({
            url: '<?php echo site_url('publik/kirim_pesan'); ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Kosongkan input pesan setelah berhasil dikirim
                $('#pesan').val('');
                $('p:contains("Tidak ada pesan yang dikirim.")').hide();

                // Scroll ke bawah setelah pesan dikirim
                scrollToBottom();
            },
            error: function() {
                alert('Terjadi kesalahan saat mengirim pesan.');
            }
        });
    });
    // Event handler untuk pesan default
    $('#default-messages').on('click', '.message-default', function() {
        var pesan = $(this).data('pesan');

        // Kirim pesan default menggunakan AJAX
        $.ajax({
            url: '<?php echo site_url('publik/kirim_pesan'); ?>',
            type: 'POST',
            data: {
                penerima_id: <?php echo $ustad_id; ?>,
                pesan: pesan
            },
            success: function(response) {
                // Kosongkan input pesan setelah berhasil dikirim
                $('#pesan').val('');
                $('p:contains("Tidak ada pesan yang dikirim.")').hide();

                // Scroll ke bawah setelah pesan dikirim
                scrollToBottom();
            },
            error: function() {
                alert('Terjadi kesalahan saat mengirim pesan.');
            }
        });
    });
});
</script>



</html>