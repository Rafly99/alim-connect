<div class="container mt-5">
    <div class="row">
        <h4 class="border-bottom pb-2 mb-5">Pilih ustadz</h4>
        <div class="form-group mb-3 col-md-5">
            <div class="row">
                <label for="topik-chat" class="form-label col-md-3 pt-1">Pilih pakar:</label>
                <div class="col-md-9">
                    <select id="topik-chat" class="form-select">
                        <option value="">- Pilih pakar -</option>
                        <?php foreach ($topik as $t): ?>
                        <option value="<?php echo $t->bidang; ?>"><?php echo $t->bidang; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group mb-3 col-md-5">
            <div class="row">
                <label for="ustad" class="form-label col-md-3 pt-1">Pilih Ustad:</label>
                <div class="col-md-9">
                    <select id="ustad" class="form-select" disabled>
                        <option value="">- Pilih Ustad -</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button id="startChat" class="btn btn-success w-100" disabled>Mulai Chat</button>
        </div>
    </div>

    <!-- <div class="row my-4">
        <div class="col">
            <h4 class="text-center">Artikel terkait</h4>
        </div>
    </div> -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('assets/dist/js/script.js'); ?>"></script>

<script>
$(document).ready(function() {
    // Event listener untuk #topik-chat menggunakan .on()
    $(document).on('change', '#topik-chat', function() {
        var topik = $(this).val();
        if (topik) {
            $.ajax({
                url: '<?php echo base_url('publik/get_ustad_by_topik/'); ?>' +
                    encodeURIComponent(topik),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var ustadSelect = $('#ustad');
                    ustadSelect.empty().append(
                        '<option value="">- Pilih Ustad -</option>');

                    if (Array.isArray(data) && data.length > 0) {
                        $.each(data, function(index, ustad) {
                            ustadSelect.append('<option value="' + ustad.id_user +
                                '">' + ustad.nama_lengkap + '</option>');
                        });
                        ustadSelect.prop('disabled', false);
                    } else {
                        ustadSelect.prop('disabled', true);
                    }
                    $('#startChat').prop('disabled', true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ', textStatus, errorThrown);
                }
            });
        } else {
            $('#ustad').empty().append('<option value="">- Pilih Ustad -</option>').prop('disabled',
                true);
            $('#startChat').prop('disabled', true);
        }
    });

    // Event listener untuk #ustad menggunakan .on()
    $(document).on('change', '#ustad', function() {
        if ($(this).val()) {
            $('#startChat').prop('disabled', false);
        } else {
            $('#startChat').prop('disabled', true);
        }
    });

    // Event listener untuk #startChat menggunakan .on()
    $(document).on('click', '#startChat', function() {
        var ustadId = $('#ustad').val();
        if (ustadId) {
            window.location.href = '<?php echo base_url('publik/chat_publik/'); ?>' +
                encodeURIComponent(ustadId);
        }
    });
});
</script>