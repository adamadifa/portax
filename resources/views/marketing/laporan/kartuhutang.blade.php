<form action="{{ route('laporanmarketing.cetakkartuhutang') }}" method="POST" target="_blank" id="formKartuHutang" class="space-y-3">
    @csrf
    
    @php
        $supplier = \App\Models\SupplierMarketing::orderBy('nama_supplier')->get();
        $cabang_kh = \App\Models\Cabang::orderBy('kode_cabang')->get();
    @endphp

    <div class="space-y-2">
        @if (auth()->user()->kode_cabang == 'PST')
        <div class="relative">
            <select name="kode_cabang" id="kode_cabang_kh" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                <option value="">Semua Cabang</option>
                @foreach ($cabang_kh as $c)
                    <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="relative">
            <select name="kode_supplier" id="kode_supplier_kh" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select select2">
                <option value="">Semua Supplier</option>
                @foreach ($supplier as $s)
                    <option value="{{ $s->kode_supplier }}">{{ $s->nama_supplier }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="relative">
                 <input type="text" name="dari" id="dari_kh" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
            </div>
    
            <div class="relative">
                 <input type="text" name="sampai" id="sampai_kh" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton_kh" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton_kh">
                <i class="ti ti-download"></i>
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
