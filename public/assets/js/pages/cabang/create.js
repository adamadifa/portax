$(function () {
    $("#formcreateCabang").submit(function (e) {
        let isValid = true;

        // Reset all previous errors
        $(".error-message").remove();
        $("input, textarea, select").removeClass("border-red-500").addClass("border-slate-300");

        // Helper function to show error
        function showError(field, message) {
            $(field).removeClass("border-slate-300").addClass("border-red-500");
            $(field).closest('.relative').append(`<span class="text-red-500 text-xs mt-1 block error-message">${message}</span>`);
            isValid = false;
        }

        // Validate Kode Cabang
        let kode_cabang = $("input[name='kode_cabang']");
        if (kode_cabang.val() == "") {
            showError(kode_cabang, "Kode Cabang harus diisi");
        } else if (kode_cabang.val().length != 3) {
            showError(kode_cabang, "Kode Cabang harus 3 karakter");
        }

        // Validate Nama Cabang
        let nama_cabang = $("input[name='nama_cabang']");
        if (nama_cabang.val() == "") {
            showError(nama_cabang, "Nama Cabang harus diisi");
        }

        // Validate Alamat Cabang
        let alamat_cabang = $("textarea[name='alamat_cabang']");
        if (alamat_cabang.val() == "") {
            showError(alamat_cabang, "Alamat Cabang harus diisi");
        }

        // Validate Telepon
        let telepon_cabang = $("input[name='telepon_cabang']");
        if (telepon_cabang.val() == "") {
            showError(telepon_cabang, "Telepon harus diisi");
        } else if (isNaN(telepon_cabang.val())) {
            showError(telepon_cabang, "Telepon harus berupa angka");
        } else if (telepon_cabang.val().length > 13) {
            showError(telepon_cabang, "Telepon maksimal 13 angka");
        }

        // Validate Lokasi
        let lokasi_cabang = $("input[name='lokasi_cabang']");
        if (lokasi_cabang.val() == "") {
            showError(lokasi_cabang, "Lokasi harus diisi");
        }

        // Validate Radius
        let radius_cabang = $("input[name='radius_cabang']");
        if (radius_cabang.val() == "") {
            showError(radius_cabang, "Radius harus diisi");
        }

        // Validate Regional
        let kode_regional = $("select[name='kode_regional']");
        if (kode_regional.val() == "") {
            showError(kode_regional, "Silahkan pilih regional");
        }

        // Validate Kode PT
        let kode_pt = $("input[name='kode_pt']");
        if (kode_pt.val() == "") {
            showError(kode_pt, "Kode PT harus diisi");
        } else if (kode_pt.val().length != 3) {
            showError(kode_pt, "Kode PT harus 3 karakter");
        }

        // Validate Nama PT
        let nama_pt = $("input[name='nama_pt']");
        if (nama_pt.val() == "") {
            showError(nama_pt, "Nama PT harus diisi");
        }

        // Validate Urutan
        let urutan = $("input[name='urutan']");
        if (urutan.val() == "") {
            showError(urutan, "Urutan harus diisi");
        }


        if (!isValid) {
            e.preventDefault();
        }
    });

    // Remove Error on Input
    $("input, textarea, select").on('input change', function () {
        $(this).removeClass("border-red-500").addClass("border-slate-300");
        $(this).closest('.relative').find('.error-message').remove();
    });
});
