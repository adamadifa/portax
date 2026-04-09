<form action="{{ route('laporankeuangan.cetakkaskecil') }}" id="formKaskecil" target="_blank" method="POST"
    class="space-y-3">
    @csrf
    @php
        $role_admin_pusat = ['admin pusat'];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        @hasanyrole(array_merge($roles_show_cabang, $role_admin_pusat))
            <div class="md:col-span-12 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 transition-colors">
                    <i class="ti ti-building text-slate-400 group-focus-within:text-[#003d9e]"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_kaskecil"
                    class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodecabangkaskecil font-medium text-slate-700">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        @endrole

        <div class="md:col-span-12 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-layout-grid text-slate-400"></i>
            </div>
            <select name="formatlaporan" id="formatlaporan"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Format Laporan</option>
                <option value="1">Detail</option>
                <option value="2">Rekap</option>
            </select>
        </div>

        <div class="md:col-span-12 grid grid-cols-2 gap-4" id="coakaskecil">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="ti ti-list text-slate-400"></i>
                </div>
                <select name="kode_akun_dari" id="kode_akun_dari_kaskecil"
                    class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodeakundarikaskecil font-medium text-slate-700">
                    <option value="">Dari Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="ti ti-list text-slate-400"></i>
                </div>
                <select name="kode_akun_sampai" id="kode_akun_sampai_kaskecil"
                    class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodeakunsampaikaskecil font-medium text-slate-700">
                    <option value="">Sampai Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
            <input type="text" name="dari" id="dari"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date"
                placeholder="Dari Tanggal">
        </div>

        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
            <input type="text" name="sampai" id="sampai"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date"
                placeholder="Sampai Tanggal">
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 mt-4">
        <div class="flex-grow">
            <button type="submit" name="submitButton"
                class="w-full h-12 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all duration-200 active:scale-95"
                id="submitButton">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
        </div>
        <div class="w-full sm:w-16">
            <button type="submit" name="exportButton"
                class="w-full h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95"
                id="exportButton" title="Export Excel">
                <i class="fas fa-file-excel text-lg"></i>
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
