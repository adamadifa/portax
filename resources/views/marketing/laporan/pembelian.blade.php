<form action="{{ route('laporanmarketing.cetakpembelian') }}" method="POST" target="_blank" id="formPembelian" class="space-y-3">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        <!-- Jenis Transaksi -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-credit-card text-lg"></i>
                </div>
                <select name="jenis_transaksi" id="jenis_transaksi" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="">Semua Jenis Transaksi</option>
                    <option value="T">Tunai</option>
                    <option value="K">Kredit</option>
                </select>
            </div>
        </div>

        <!-- Format Laporan -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-settings text-lg"></i>
                </div>
                <select name="formatlaporan" id="formatlaporan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="1">Standar</option>
                    <option value="2">Satubaris</option>
                </select>
            </div>
        </div>

        @php
            $roles_access_all_cabang = config('global.roles_access_all_cabang');
            $cabang = \App\Models\Cabang::orderBy('kode_cabang')->get();
        @endphp
        @if (in_array(auth()->user()->roles, $roles_access_all_cabang))
        <!-- Cabang -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-building text-lg"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
        
        <!-- Periode -->
        <div class="md:col-span-12 grid grid-cols-2 gap-4">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="dari" id="dari" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
            </div>
    
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="sampai" id="sampai" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="md:col-span-12 flex items-center gap-3 pt-2">
            <button type="submit" name="submitButton" 
                class="flex-1 h-12 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all duration-200 active:scale-95" 
                id="submitButton">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
            <button type="submit" name="exportButton" 
                class="w-16 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95" 
                id="exportButton" title="Export to Excel">
                <i class="fas fa-file-excel text-xl"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formPembelian = $("#formPembelian");


            formPembelian.submit(function(e) {

                const dari = formPembelian.find('#dari').val();
                const sampai = formPembelian.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Dari Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#dari").focus();
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
                            $(this).find("#sampai").focus();
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
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
