<style>
    /* Styling for Select2 to match standard form-control in modal */
    .select2-container--default .select2-selection--single {
        border: 1px solid #d9dee3 !important;
        border-radius: 0.375rem !important;
        height: 38.2px !important;
        display: flex;
        align-items: center;
        background-color: #fff;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        right: 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #697a8d !important;
        padding-left: 0.875rem !important;
        padding-right: 2rem !important;
        line-height: normal !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #a1acb8 !important;
    }
    .select2-search__field {
        outline: none !important;
    }
</style>

<form action="{{ route('jurnalumum.update', Crypt::encrypt($jurnalumum->kode_ju)) }}" id="formJurnalumum" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-12">
            <x-input-with-icon label="Tanggal" name="tanggal" datepicker="flatpickr-date" icon="ti ti-calendar" :value="$jurnalumum->tanggal" />
        </div>

        <div class="col-12">
            <div class="form-group mb-3">
                <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
                    <option value="">Pilih Kode Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}" {{ $jurnalumum->kode_akun_portax == $d->kode_akun ? 'selected' : '' }}>
                            {{ $d->kode_akun }} - {{ $d->nama_akun }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12">
            <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" :value="$jurnalumum->keterangan" />
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Jumlah" name="jumlah" align="right" icon="ti ti-moneybag" :value="formatAngka($jurnalumum->jumlah)" numberFormat="true" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <select name="debet_kredit" id="debet_kredit" class="form-select">
                    <option value="">Debet / Kredit</option>
                    <option value="D" {{ $jurnalumum->debet_kredit == 'D' ? 'selected' : '' }}>Debet</option>
                    <option value="K" {{ $jurnalumum->debet_kredit == 'K' ? 'selected' : '' }}>Kredit</option>
                </select>
            </div>
        </div>

        @if (auth()->user()->kode_cabang == 'PST' || empty(auth()->user()->kode_cabang))
            <div class="col-12" id="cabang">
                <div class="form-group mb-3">
                    <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}" {{ $jurnalumum->kode_cabang == $d->kode_cabang ? 'selected' : '' }}>
                                {{ textUpperCase($d->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{ auth()->user()->kode_cabang }}">
        @endif
        
        <input type="hidden" name="kode_peruntukan" id="kode_peruntukan" value="PC">

        <div class="col-12 mt-1">
            <button class="bg-[#003d9e] hover:bg-blue-800 text-white font-bold py-2.5 px-4 rounded-lg w-full flex items-center justify-center gap-2 transition-all active:scale-95 text-sm" id="btnSimpan">
                <i class="ti ti-send text-base"></i>
                Update Jurnal
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formJurnalumum");
        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Kode Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        form.submit(function() {
            const tanggal = form.find("#tanggal").val();
            const kode_akun = form.find("#kode_akun").val();
            const keterangan = form.find("#keterangan").val();
            const jumlah = form.find("#jumlah").val();
            const debet_kredit = form.find("#debet_kredit").val();
            const kode_cabang = form.find("#kode_cabang").val();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_akun").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (debet_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Debet/Kredit harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#debet_kredit").focus();
                    },
                });
                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
