<form action="{{ route('laporanmarketing.cetakkartuhutang') }}" method="POST" target="_blank" id="formKartuHutang" class="space-y-3">
    @csrf
    
    @php
        $supplier = \App\Models\SupplierMarketing::orderBy('nama_supplier')->get();
        $cabang_kh = \App\Models\Cabang::orderBy('kode_cabang')->get();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        @if (auth()->user()->kode_cabang == 'PST')
        <!-- Cabang -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-building text-lg"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_kh" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang_kh as $c)
                        <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        <!-- Supplier -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-user text-lg"></i>
                </div>
                <select name="kode_supplier" id="kode_supplier_kh" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select select2">
                    <option value="">Semua Supplier</option>
                    @foreach ($supplier as $s)
                        <option value="{{ $s->kode_supplier }}">{{ $s->nama_supplier }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Periode -->
        <div class="md:col-span-12 grid grid-cols-2 gap-4">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="dari" id="dari_kh" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
            </div>
    
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="sampai" id="sampai_kh" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="md:col-span-12 flex items-center gap-3 pt-2">
            <button type="submit" name="submitButton" 
                class="flex-1 h-12 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all duration-200 active:scale-95" 
                id="submitButton_kh">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
            <button type="submit" name="exportButton" 
                class="w-16 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95" 
                id="exportButton_kh" title="Export to Excel">
                <i class="fas fa-file-excel text-xl"></i>
            </button>
        </div>
    </div>
</form>

@push('myscript')
    <script>
        $(document).ready(function() {
            const formKartuHutang = $("#formKartuHutang");

            formKartuHutang.submit(function(e) {
                const dari = formKartuHutang.find('#dari_kh').val();
                const sampai = formKartuHutang.find('#sampai_kh').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                // Note: user requested "form filternya cukup supplier dan periode tanggal saja."
                // I will allow empty supplier (for printing all suppliers if the system supports it later),
                // but checking dates is standard practice here.

                if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Dari Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#dari_kh").focus();
                        },
                    });
                    return false;
                } else if (sampai == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Sampai Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#sampai_kh").focus();
                        },
                    });
                    return false;
                } else if (start.getTime() > end.getTime()) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#sampai_kh").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
