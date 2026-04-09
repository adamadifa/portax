<form action="{{ route('laporankeuangan.cetakrekapledger') }}" id="formRekapledger" target="_blank" method="POST"
    class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-1">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
            <input type="text" name="dari" id="dari_rekapledger"
                class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date"
                placeholder="Dari Tanggal">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
            <input type="text" name="sampai" id="sampai_rekapledger"
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
            const formRekapledger = $("#formRekapledger");

            formRekapledger.submit(function(e) {
                const dari = formRekapledger.find("#dari_rekapledger").val();
                const sampai = formRekapledger.find("#sampai_rekapledger").val();
                const start = new Date(dari);
                const end = new Date(sampai);
                if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formRekapledger.find("#dari_rekapledger").focus();
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
                            formRekapledger.find("#sampai_rekapledger").focus();
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
                            formRekapledger.find("#sampai_rekapledger").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
