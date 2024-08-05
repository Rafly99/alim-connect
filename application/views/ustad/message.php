<body>
    <?php
$last_date = null;
?> <div class="col-md-9">
        <!-- kolom header -->
        <?php if (!empty($messages) && is_array($messages)) : ?>
        <div class="header d-flex align-items-center p-3 border-bottom">
            <img src="<?php echo base_url('assets/image/profile.png');?>" alt="Profile"
                class="rounded-circle border border-success mr-3" style="width: 50px; height: 50px;">
            <div>
                <div><?php echo $messages[0]->pengirim_nama ?></div>
                <div class="status">
                    <?php echo ($status->status == 1) ? 'Online' : 'Offline'; ?>
                </div>
            </div>
            <a href="<?php echo site_url('ustad/delete_chat/' . $messages[0]->pengirim_id); ?>"
                class="btn btn-danger btn-sm rounded-circle ml-auto" alt="hapus chat">
                <i class="bi bi-trash"></i>
            </a>
        </div>

        <!-- kolom chat -->
        <div class="content" id="chat-content">
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
        </div>

        <!-- kolom kirim pesan -->
        <div class="footer p-3 border-top">
            <form id="send-message-form" class="input-group">
                <input type="hidden" name="receiver_id" id="id_user" value="<?php echo $messages[0]->pengirim_id; ?>">
                <input type="text" name="pesan" id="pesan" class="form-control rounded-pill"
                    placeholder="Tulis pesan..." required>
                <button type="submit" class="btn btn-send rounded-circle ms-1"
                    style="background-color: #4ea8a7; color: white;">
                    <i class="bi bi-send"></i>
                </button>
            </form>
        </div>
        <?php else : ?>
        <p>Pesan tidak ditemukan atau telah dibaca.</p>
        <?php endif; ?>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
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
    // Subscribe ke channel dan event
    // Ambil ID pengguna dari sesi atau variabel lain
    var channel = pusher.subscribe('public-chat');

    // Ambil ID pengguna aktif dari PHP
    var userId = <?php echo $this->session->userdata('id_user'); ?>;
    channel.bind('my-event', function(data) {
        // Debug: Periksa data yang diterima
        console.log(data);
        if (data.receiver_id == userId || data.sender_id == userId) {
            // Menambahkan pesan baru ke tampilan chat
            var messageClass = data.sender_id == '<?php echo $this->session->userdata('id_user'); ?>' ?
                'message-right' : 'message-left';
            var messageHTML = '<div class="message ' + messageClass + '">' +
                '<div class="message-content">' + (data.message || 'No message content') + '</div>' +
                '<div class="message-time">' + new Date(data.created_at).toLocaleTimeString() + '</div>' +
                '</div>';

            // Tambahkan pesan ke konten chat
            $('#chat-content').append(messageHTML);

            // Scroll ke pesan terbaru
            $('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);
        }
    });
    </script>


    <script>
    function scrollToBottom() {
        var chatContent = document.getElementById("chat-content");
        chatContent.scrollTop = chatContent.scrollHeight;
    }

    $(document).ready(function() {
        scrollToBottom(); // Scroll ke bawah saat halaman pertama kali dimuat

        $('#send-message-form').on('submit', function(event) {
            event.preventDefault(); // Mencegah halaman refresh

            var formData = $(this).serialize();

            $.ajax({
                url: '<?php echo site_url('ustad/send_message'); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Mengosongkan input pesan
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

</body>