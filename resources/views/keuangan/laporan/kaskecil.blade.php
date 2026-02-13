<form action="{{ route('laporankeuangan.cetakkaskecil') }}" id="formKaskecil" target="_blank" method="POST" class="space-y-3">
    @csrf
    @php
        $role_admin_pusat = ['admin pusat'];
    @endphp
    <style>
        .select2-container .select2-selection--single {
            height: 46px !important;
            padding: 10px 12px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            background-color: #fff !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0 !important;
            color: #1e293b !important;
            font-size: 0.875rem !important;
            flex-grow: 1 !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            top: 1px !important;
            right: 8px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 0px !important;
            font-weight: bold !important;
            color: #cbd5e1 !important;
            order: 2 !important;
            margin-left: auto !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #64748b !important;
        }

        .form-select {
            border-color: #cbd5e1 !important;
            border-radius: 0.5rem !important;
        }

        .form-select:focus {
            border-color: #003d9e !important;
            box-shadow: 0 0 0 1px #003d9e !important;
        }

        .flatpickr-date,
        .form-control {
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            height: 46px !important;
            padding: 10px 12px !important;
        }

        .flatpickr-date:focus,
        .form-control:focus {
            border-color: #003d9e !important;
            box-shadow: 0 0 0 1px #003d9e !important;
            outline: none !important;
        }
    </style>

    <div class="space-y-2">
        @hasanyrole(array_merge($roles_show_cabang, $role_admin_pusat))
        <div class="relative">
            <select name="kode_cabang" id="kode_cabang_kaskecil" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangkaskecil">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        @endrole

        <div class="relative">
            <select name="formatlaporan" id="formatlaporan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                <option value="">Format Laporan</option>
                <option value="1">Detail</option>
                <option value="2">Rekap</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4" id="coakaskecil">
            <div class="relative">
                <select name="kode_akun_dari" id="kode_akun_dari_kaskecil" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodeakundarikaskecil">
                    <option value="">Dari Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="relative">
                <select name="kode_akun_sampai" id="kode_akun_sampai_kaskecil" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodeakunsampaikaskecil">
                    <option value="">Sampai Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="relative">
                <input type="text" name="dari" id="dari" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
            </div>
            <div class="relative">
                <input type="text" name="sampai" id="sampai" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formKaskecil = $("#formKaskecil");
            const select2Kodecabangkaskecil = $(".select2Kodecabangkaskecil");
            if (select2Kodecabangkaskecil.length) {
                select2Kodecabangkaskecil.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }
            const select2Kodeakundarikaskecil = $(".select2Kodeakundarikaskecil");
            if (select2Kodeakundarikaskecil.length) {
                select2Kodeakundarikaskecil.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Dari Akun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }
            const select2Kodeakunsampaikaskecil = $(".select2Kodeakunsampaikaskecil");
            if (select2Kodeakunsampaikaskecil.length) {
                select2Kodeakunsampaikaskecil.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Sampai Akun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }


            function showcoakaskecil() {
                const formatlaporan = formKaskecil.find("#formatlaporan").val();
                if (formatlaporan == '1') {
                    formKaskecil.find("#coakaskecil").show();
                } else {
                    formKaskecil.find("#coakaskecil").hide();
                }
            }
            showcoakaskecil();

            formKaskecil.find("#formatlaporan").change(function() {
                showcoakaskecil();
            });

            formKaskecil.submit(function(e) {
                const kode_cabang = formKaskecil.find("#kode_cabang_kaskecil").val();
                const formatlaporan = formKaskecil.find("#formatlaporan").val();
                const dari = formKaskecil.find('#dari').val();
                const sampai = formKaskecil.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);
                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Format Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#formatlaporan").focus();
                        },
                    })
                    return false;
                } else if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Dari Tanggal Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#dari").focus();
                        },
                    });
                    return false;
                } else if (sampai == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Sampai Tanggal Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#sampai").focus();
                        },
                    });
                    return false;
                } else if (start.getTime() > end.getTime()) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKaskecil.find("#sampai").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
