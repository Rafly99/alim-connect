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
    <nav class="navbar navbar-expand-md bg-white navbar-light shadow-sm" id="navbar">
        <div class="container-fluid">
            <a href="<?php echo base_url('publik/dashboard'); ?>" class="navbar-brand">
                <img src="<?php echo base_url('assets/image/logo.svg'); ?>" alt="alim connect" id="img-logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item px-2">
                        <a href="<?php echo base_url('publik/dashboard'); ?>" class="nav-link">Beranda</a>
                    </li>
                    <li class="nav-item px-2">
                        <a href="<?php echo base_url('publik/artikel'); ?>" class="nav-link">Artikel</a>
                    </li>
                    <li class="nav-item px-2">
                        <a href="<?php echo base_url('publik/kontak'); ?>" class="nav-link">Kontak</a>
                    </li>
                </ul>
                <div class="dropdown">
                    <button class="btn-notif position-relative" id="notifButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-chat"></i>
                        <?php if ($unread_senders_count > 0): ?>
                        <span class="badge position-absolute custom-badge-position rounded-circle">
                            <?php echo $unread_senders_count; ?>
                        </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu dropdown-menu-end" aria-labelledby="notifButton">
                        <li class="dropdown-header">Notification</li>
                        <?php foreach ($senders_list as $sender): ?>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item dropdown-item-custom"
                                href="<?php echo site_url('publik/chat_publik/'. $sender->pengirim_id); ?>"><?php echo $sender->nama_lengkap; ?>
                                <span class="badge bg-success float-end"><?php echo $sender->unread_count; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php if ($this->session->userdata('logged_in')): ?>
                <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                    title="Keluar aplikasi?">Logout</a>
                <?php else: ?>
                <a href="<?php echo site_url('auth/login'); ?>" class="btn btn-success btn-sm">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>


    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
    $(document).ready(function() {
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
            console.log('Data received from Pusher:', data);
            var senderId = data.sender_id;
            var senderName = data.sender_name; // Pastikan data sender_name ada

            // Update dropdown dengan pengirim baru atau pesan yang belum dibaca
            updateDropdown(senderId, senderName);
        });

        // Fungsi untuk memperbarui dropdown dengan pengirim unik
        function updateDropdown(senderId, senderName) {
            var existingItem = $('.dropdown-item-custom[href$="' + senderId + '"]');
            if (existingItem.length) {
                var badge = existingItem.find('.badge');
                var unreadCount = parseInt(badge.text()) + 1;
                badge.text(unreadCount);
            } else {
                var newItem = '<li><hr class="dropdown-divider"></li>' +
                    '<li><a class="dropdown-item dropdown-item-custom" href="<?php echo site_url('publik/chat_publik/'); ?>' +
                    senderId + '">' +
                    senderName + ' <span class="badge bg-success float-end">1</span></a></li>';
                $('.dropdown-menu').append(newItem);
            }

            // Update jumlah pesan belum dibaca di tombol notifikasi
            var currentCount = parseInt($('#notifButton .badge').text()) || 0;
            $('#notifButton .badge').text(currentCount + 1);
        }

        // Load dropdown pada saat halaman pertama kali dimuat
        function loadDropdown() {
            $.ajax({
                url: '<?php echo site_url('publik/get_senders_list'); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var dropdownMenu = $('.dropdown-menu');
                    dropdownMenu.empty();
                    dropdownMenu.append('<li class="dropdown-header">Notification</li>');

                    $.each(response.senders_list, function(index, sender) {
                        var newItem = '<li><hr class="dropdown-divider"></li>' +
                            '<li><a class="dropdown-item dropdown-item-custom" href="<?php echo site_url('publik/chat_publik/'); ?>' +
                            sender.pengirim_id + '">' +
                            sender.nama_lengkap +
                            ' <span class="badge bg-success float-end">' +
                            sender.unread_count + '</span></a></li>';
                        dropdownMenu.append(newItem);
                    });

                    if (response.unread_senders_count > 0) {
                        $('#notifButton .badge').text(response.unread_senders_count);
                    } else {
                        $('#notifButton .badge').remove();
                    }
                },
                error: function() {
                    console.log('Error loading dropdown data');
                }
            });
        }

        loadDropdown();
    });
    </script>