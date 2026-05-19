<form action="{{ route('laporanaccounting.cetakbiaya') }}" method="POST" target="_blank" id="formBiaya" class="space-y-2">
    @csrf
    <div class="grid grid-cols-1 gap-y-2">
        <!-- Periode -->
        <div>
            <div class="grid grid-cols-2 gap-4">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                        <i class="ti ti-calendar text-lg"></i>
                    </div>
                    <input type="text" name="dari" id="dari_biaya" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
                </div>
        
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                        <i class="ti ti-calendar text-lg"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_biaya" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
                </div>
            </div>
        </div>

        <!-- Format Laporan -->
        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-settings text-lg"></i>
                </div>
                <select name="formatlaporan" id="formatlaporan_biaya" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="1">Detail</option>
                    <option value="2">Rekap</option>
                </select>
            </div>
        </div>

        <!-- Cabang -->
        @if (auth()->user()->kode_cabang == 'PST')
            <div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                        <i class="ti ti-building text-lg"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_biaya" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <input type="hidden" name="kode_cabang" value="{{ auth()->user()->kode_cabang }}">
        @endif

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" name="submitButton" 
                class="flex-1 h-12 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all duration-200 active:scale-95" 
                id="submitButtonBiaya">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
            <button type="submit" name="exportButton" 
                class="w-16 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95" 
                id="exportButtonBiaya" title="Export to Excel">
                <i class="fas fa-file-excel text-xl"></i>
            </button>
        </div>
    </div>
</form>

@push('myscript')
<script>
    $(document).ready(function() {
        $("#formBiaya").submit(function(e) {
            var dari = $(this).find('#dari_biaya').val();
            var sampai = $(this).find('#sampai_biaya').val();
            var start = new Date(dari);
            var end = new Date(sampai);

            if (dari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode Dari Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#formBiaya").find('#dari_biaya').focus();
                    },
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode Sampai Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#formBiaya").find('#sampai_biaya').focus();
                    },
                });
                return false;
            } else if (start > end) {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode Tidak Valid !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#formBiaya").find('#sampai_biaya').focus();
                    },
                });
                return false;
            }
        });
    });
</script>
@endpush
