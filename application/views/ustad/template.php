<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alim Connect - Ustad</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/chats.css'); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="<?php echo base_url('assets/image/title-icon.png'); ?>" type="image/png">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>


<body>
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('ustad/list_kontak', array('jumlah_pesan_id' => $jumlah_pesan_id)); ?>
            <?php if (!empty($message_view)) : ?>
            <?php $this->load->view($message_view); ?>
            <?php else : ?>
            <?php $this->load->view('ustad/dashboard'); ?>
            <?php endif; ?>
        </div>
    </div>



</body>


</html>