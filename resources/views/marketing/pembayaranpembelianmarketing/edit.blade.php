<form id="formBayar" method="POST" action="{{ route('pembayaranpembelianmarketing.update', Crypt::encrypt($historibayar->no_bukti)) }}">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Pembayaran" name="tanggal" datepicker="flatpickr-date" value="{{ $historibayar->tanggal }}" />
    <x-input-with-icon icon="ti ti-moneybag" label="Jumlah Bayar" name="jumlah" align="right" value="{{ formatAngka($historibayar->jumlah) }}" />
    
    <x-select label="Jenis Bayar" name="jenis_bayar" :data="[['value' => 'TN', 'text' => 'CASH'], ['value' => 'TR', 'text' => 'TRANSFER']]" key="value" textShow="text" upperCase="true" select2="select2Jenisbayar" selected="{{ $historibayar->jenis_bayar }}" />
    
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Update</button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formBayar");
        const start_periode = "{{ config('global.start_date') }}";

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        $(".flatpickr-date").flatpickr({
            enable: [{
                from: start_periode,
                to: "{{ date('Y-m-d') }}"
            }, ]
        });

        const select2Jenisbayar = $('.select2Jenisbayar');
        if (select2Jenisbayar.length) {
            select2Jenisbayar.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Jenis Bayar',
                    allowClear: false,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#jumlah").maskMoney();

        form.submit(function(e) {
            const tanggal = $(this).find("#tanggal").val();
            const jml = $(this).find("#jumlah").val();
            const jumlah = parseInt(jml.replace(/\./g, ''));
            const jenis_bayar = $(this).find("#jenis_bayar").val();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (jml === "" || jml === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (jenis_bayar == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Bayar Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jenis_bayar").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>





