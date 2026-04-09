<form action="{{ route('laporankeuangan.cetaklpu') }}" id="formLPU" target="_blank" method="POST" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-1 gap-x-4 gap-y-1">
        @hasanyrole($roles_show_cabang)
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 transition-colors">
                    <i class="ti ti-building text-slate-400"></i>
                </div>
                <select name="kode_cabang_lpu" id="kode_cabang_lpu"
                    class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodecabanglpu font-medium text-slate-700">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        @endrole

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
            <select name="bulan" id="bulan_lpu"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Bulan</option>
                @foreach ($list_bulan as $d)
                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
            <select name="tahun" id="tahun_lpu"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Tahun</option>
                @for ($t = $start_year; $t <= date('Y'); $t++)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endfor
            </select>
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-layout-grid text-slate-400"></i>
            </div>
            <select name="formatlaporan" id="formatlaporan_lpu"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Format Laporan</option>
                <option value="1">LHP - Setoran Penjualan</option>
                <option value="2">LHP - Setoran Pusat</option>
            </select>
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
        $(function() {
            const formLPU = $("#formLPU");
            const select2Kodecabanglpu = $(".select2Kodecabanglpu");
            if (select2Kodecabanglpu.length) {
                select2Kodecabanglpu.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formLPU.submit(function(e) {
                const kode_cabang = formLPU.find("#kode_cabang_lpu").val();
                const bulan = formLPU.find("#bulan_lpu").val();
                const tahun = formLPU.find("#tahun_lpu").val();
                const formatlaporan = formLPU.find("#formatlaporan_lpu").val();
                if (kode_cabang == "" && formLPU.find("#kode_cabang_lpu").length > 0) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Cabang Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formLPU.find("#kode_cabang_lpu").focus();
                        },
                    });
                    return false;
                } else if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Bulan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formLPU.find("#bulan_lpu").focus();
                        },
                    });
                    return false;
                } else if (tahun == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Tahun Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formLPU.find("#tahun_lpu").focus();
                        },
                    });
                    return false;
                } else if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Format Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formLPU.find("#formatlaporan_lpu").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
