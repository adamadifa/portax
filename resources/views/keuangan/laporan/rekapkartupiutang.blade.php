<form action="{{ route('laporankeuangan.cetakrekapkartupiutang') }}" id="formRekapkartupiutang" target="_blank" method="POST"
    class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        @hasanyrole($roles_show_cabang)
            <div class="md:col-span-12 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 transition-colors">
                    <i class="ti ti-building text-slate-400"></i>
                </div>
                <select name="kode_cabang_rekapkartupiutang" id="kode_cabang_rekapkartupiutang"
                    class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodecabangrekapkartupiutang font-medium text-slate-700">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-12 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 transition-colors">
                    <i class="ti ti-users text-slate-400"></i>
                </div>
                <select name="kode_dept_rekapkartupiutang" id="kode_dept_rekapkartupiutang"
                    class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodedeptrekapkartupiutang font-medium text-slate-700">
                    <option value="">Semua Departemen</option>
                    @foreach ($departemen as $d)
                        <option value="{{ $d->kode_dept }}">{{ textUpperCase($d->nama_dept) }}</option>
                    @endforeach
                </select>
            </div>
        @endrole
        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
            <select name="bulan" id="bulan_rekapkartupiutang"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Bulan</option>
                @foreach ($list_bulan as $d)
                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
            <select name="tahun" id="tahun_rekapkartupiutang"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Tahun</option>
                @for ($t = $start_year; $t <= date('Y'); $t++)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endfor
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
            const formRekapkartupiutang = $("#formRekapkartupiutang");
            const select2Kodecabangrekapkartupiutang = $(".select2Kodecabangrekapkartupiutang");
            if (select2Kodecabangrekapkartupiutang.length) {
                select2Kodecabangrekapkartupiutang.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodedeptrekapkartupiutang = $(".select2Kodedeptrekapkartupiutang");
            if (select2Kodedeptrekapkartupiutang.length) {
                select2Kodedeptrekapkartupiutang.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Departemen',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formRekapkartupiutang.submit(function(e) {
                const bulan = formRekapkartupiutang.find("#bulan_rekapkartupiutang").val();
                const tahun = formRekapkartupiutang.find("#tahun_rekapkartupiutang").val();
                if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Bulan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formRekapkartupiutang.find("#bulan_rekapkartupiutang").focus();
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
                            formRekapkartupiutang.find("#tahun_rekapkartupiutang").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
