<form action="{{ route('laporankeuangan.cetakmutasikeuangan') }}" id="formMutasikeuangan" target="_blank" method="POST"
    class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        <div class="md:col-span-12 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-building-bank text-slate-400"></i>
            </div>
            <select name="kode_bank_ledger" id="kode_bank_ledger_mutasi"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodebankledger font-medium text-slate-700">
                <option value="">Mutasi Keuangan</option>
                @foreach ($bank as $d)
                    <option {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }} value="{{ $d->kode_bank }}">
                        {{ $d->nama_bank }}
                        ({{ $d->no_rekening }})
                    </option>
                @endforeach
            </select>
        </div>

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
        $(function() {
            const formMutasikeuangan = $("#formMutasikeuangan");
            const select2Kodebankledger = $(".select2Kodebankledger");
            if (select2Kodebankledger.length) {
                select2Kodebankledger.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Bank',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formMutasikeuangan.submit(function(e) {
                const formatlaporan = formMutasikeuangan.find("#formatlaporan").val();
                const dari = formMutasikeuangan.find("#dari").val();
                const sampai = formMutasikeuangan.find("#sampai").val();
                const start = new Date(dari);
                const end = new Date(sampai);
                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Jenis Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formMutasikeuangan.find("#formatlaporan").focus();
                        },
                    });
                    return false;
                } else if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formMutasikeuangan.find("#dari").focus();
                        },
                    });
                    return false;
                } else if (sampai == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Sampai Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formMutasikeuangan.find("#sampai").focus();
                        },
                    });
                    return false;
                } else if (start > end) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Tidak Valid !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formMutasikeuangan.find("#sampai").focus();
                        },
                    });
                    return false;
                }
            });

        });
    </script>
@endpush
