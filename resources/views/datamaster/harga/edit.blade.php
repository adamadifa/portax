<form action="{{ route('harga.update', Crypt::encrypt($harga->kode_harga)) }}" id="formeditHarga" method="POST" class="space-y-4">
    @csrf
    @method('PUT')
    
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
    </style>

    <!-- Header -->
    <div class="border-b border-slate-200 pb-3 mb-3 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800">Edit Data Harga</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Top Section: 2 Cols -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Kode Harga -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kode Harga <span class="text-red-500">*</span></label>
            <input type="text" name="kode_harga" id="kode_harga"
                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-500 cursor-not-allowed focus:outline-none placeholder-slate-400"
                value="{{ $harga->kode_harga }}" readonly>
        </div>

        <!-- Produk -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Produk <span class="text-red-500">*</span></label>
            <select name="kode_produk" id="kode_produk"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none"
                {{ !auth()->user()->hasRole('super admin') ? 'disabled' : '' }}>
                <option value="">Pilih Produk</option>
                @foreach ($produk as $p)
                    <option value="{{ $p->kode_produk }}" {{ $harga->kode_produk == $p->kode_produk ? 'selected' : '' }}>{{ $p->nama_produk }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
            
             @if(!auth()->user()->hasRole('super admin'))
             <input type="hidden" name="kode_produk" value="{{ $harga->kode_produk }}">
             @endif
        </div>
    </div>

    <!-- Section: Harga Jual -->
    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
        <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-4 flex items-center gap-2">
            <i class="fas fa-tag bg-blue-200 p-1.5 rounded-full text-blue-800"></i> Harga Jual
        </h4>
        <div class="grid grid-cols-3 gap-4">
            <!-- Dus -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-blue-50 px-1 text-[10px] font-bold text-indigo-900 z-10">Per Dus</label>
                <input type="text" name="harga_dus" id="harga_dus"
                    class="money w-full px-3 py-2.5 bg-white border border-blue-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                    value="{{ formatRupiah($harga->harga_dus) }}">
            </div>
            <!-- Pack -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-blue-50 px-1 text-[10px] font-bold text-indigo-900 z-10">Per Pack</label>
                <input type="text" name="harga_pack" id="harga_pack"
                    class="money w-full px-3 py-2.5 bg-white border border-blue-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                    value="{{ formatRupiah($harga->harga_pack) }}">
            </div>
            <!-- Pcs -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-blue-50 px-1 text-[10px] font-bold text-indigo-900 z-10">Per Pcs</label>
                <input type="text" name="harga_pcs" id="harga_pcs"
                    class="money w-full px-3 py-2.5 bg-white border border-blue-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                    value="{{ formatRupiah($harga->harga_pcs) }}">
            </div>
        </div>
    </div>

    <!-- Section: Harga Retur -->
    <div class="bg-rose-50/50 p-4 rounded-xl border border-rose-100">
        <h4 class="text-xs font-bold text-rose-700 uppercase tracking-wider mb-4 flex items-center gap-2">
            <i class="fas fa-undo bg-rose-200 p-1.5 rounded-full text-rose-700"></i> Harga Retur
        </h4>
        <div class="grid grid-cols-3 gap-4">
            <!-- Retur Dus -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-rose-50 px-1 text-[10px] font-bold text-rose-900 z-10">Retur Dus</label>
                <input type="text" name="harga_retur_dus" id="harga_retur_dus"
                    class="money w-full px-3 py-2.5 bg-white border border-rose-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors"
                    value="{{ formatRupiah($harga->harga_retur_dus) }}">
            </div>
            <!-- Retur Pack -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-rose-50 px-1 text-[10px] font-bold text-rose-900 z-10">Retur Pack</label>
                <input type="text" name="harga_retur_pack" id="harga_retur_pack"
                    class="money w-full px-3 py-2.5 bg-white border border-rose-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors"
                    value="{{ formatRupiah($harga->harga_retur_pack) }}">
            </div>
            <!-- Retur Pcs -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-rose-50 px-1 text-[10px] font-bold text-rose-900 z-10">Retur Pcs</label>
                <input type="text" name="harga_retur_pcs" id="harga_retur_pcs"
                    class="money w-full px-3 py-2.5 bg-white border border-rose-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors"
                    value="{{ formatRupiah($harga->harga_retur_pcs) }}">
            </div>
        </div>
    </div>

    <!-- Section: Settings (3 Cols) -->
    <div class="grid grid-cols-3 gap-4">
        <!-- PPN -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">PPN</label>
            <select name="status_ppn" id="status_ppn"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] appearance-none">
                <option value="">Pilih</option>
                <option value="IN" {{ $harga->status_ppn == 'IN' ? 'selected' : '' }}>INCLUDE</option>
                <option value="EX" {{ $harga->status_ppn == 'EX' ? 'selected' : '' }}>EXCLUDE</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>
        <!-- Promo -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Promo <span class="text-red-500">*</span></label>
            <select name="status_promo" id="status_promo"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] appearance-none">
                <option value="">Pilih</option>
                <option value="1" {{ $harga->status_promo == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $harga->status_promo == '0' ? 'selected' : '' }}>Non Aktif</option>
            </select>
             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>
        <!-- Status -->
        <div class="relative">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Status <span class="text-red-500">*</span></label>
             <select name="status_aktif_harga" id="status_aktif_harga"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] appearance-none">
                <option value="">Pilih</option>
                <option value="1" {{ $harga->status_aktif_harga == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $harga->status_aktif_harga == '0' ? 'selected' : '' }}>Non Aktif</option>
            </select>
             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>
    </div>

    <!-- Section: Bottom (2 Cols) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
         <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kategori Salesman <span class="text-red-500">*</span></label>
            <select name="kode_kategori_salesman" id="kode_kategori_salesman"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] appearance-none"
                {{ !auth()->user()->hasRole('super admin') ? 'disabled' : '' }}>
                <option value="">Pilih Kategori</option>
                @foreach ($kategori_salesman as $k)
                    <option value="{{ $k->kode_kategori_salesman }}" {{ $harga->kode_kategori_salesman == $k->kode_kategori_salesman ? 'selected' : '' }}>{{ $k->nama_kategori_salesman }}</option>
                @endforeach
            </select>
             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
             @if(!auth()->user()->hasRole('super admin'))
             <input type="hidden" name="kode_kategori_salesman" value="{{ $harga->kode_kategori_salesman }}">
             @endif
        </div>

        @if(auth()->user()->hasRole('super admin'))
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang <span class="text-red-500">*</span></label>
            <select name="kode_cabang" id="kode_cabang"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] appearance-none">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $c)
                    <option value="{{ $c->kode_cabang }}" {{ $harga->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>{{ $c->nama_cabang }}</option>
                @endforeach
            </select>
             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>
        @else
         <!-- If not super admin, hidden inputs are below -->
         <input type="hidden" name="kode_cabang" value="{{ $harga->kode_cabang }}">
        @endif
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end pt-3 border-t border-slate-100 mt-3 gap-2">
        <button type="button" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors" onclick="closeTailwindModal()">Batal</button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-sm shadow-blue-200 transition-colors flex items-center gap-2">
            <i class="fas fa-save"></i>
            <span>Update</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        $(".money").maskMoney();
        $("#formeditHarga").submit(function(e) {
            let isValid = true;

             // Reset all previous errors inside this form
            $("#formeditHarga .error-message").remove();
            $("#formeditHarga input, #formeditHarga select").removeClass("!border-red-500 invalid-border").addClass("border-slate-300");

             // Restore specific border colors for themed inputs if they were reset
            $("#formeditHarga .bg-blue-50 input").removeClass("border-slate-300").addClass("border-blue-200");
            $("#formeditHarga .bg-rose-50 input").removeClass("border-slate-300").addClass("border-rose-200");

            function showError(field, message) {
                const input = $(`#formeditHarga #${field}`);
                 // Apply Red Border with !important to ensure visibility
                input.removeClass("border-slate-300 border-blue-200 border-rose-200").addClass("!border-red-500 invalid-border");
                
                 // Append error INSIDE the closest relative wrapper
                let wrapper = input.closest('.relative');
                if (wrapper.find('.error-message').length === 0) {
                     wrapper.append(`<p class="text-red-500 text-[10px] mt-1 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                
                isValid = false;
            }

            // Kode harga is readonly, skip validation

             // Validate Produk (if enabled)
            const kode_produk = $("#formeditHarga #kode_produk");
            if (kode_produk.is(':enabled') && kode_produk.val() == "") showError('kode_produk', 'Pilih Produk');

            if ($("#formeditHarga #harga_dus").val() == "") showError('harga_dus', 'Wajib diisi');
            if ($("#formeditHarga #harga_pack").val() == "") showError('harga_pack', 'Wajib diisi');
            if ($("#formeditHarga #harga_pcs").val() == "") showError('harga_pcs', 'Wajib diisi');

            // if ($("#formeditHarga #status_ppn").val() == "") showError('status_ppn', 'Pilih');
            if ($("#formeditHarga #status_promo").val() == "") showError('status_promo', 'Pilih');
            if ($("#formeditHarga #status_aktif_harga").val() == "") showError('status_aktif_harga', 'Pilih');
            
             const kat = $("#formeditHarga #kode_kategori_salesman");
            if (kat.is(':enabled') && kat.val() == "") showError('kode_kategori_salesman', 'Pilih');
            
            const cab = $("#formeditHarga #kode_cabang");
            if (cab.length > 0 && cab.val() == "") showError('kode_cabang', 'Pilih');

            if (!isValid) e.preventDefault();
        });
        
         $('#formeditHarga input, #formeditHarga select').on('input change', function() {
            $(this).removeClass('!border-red-500 invalid-border').addClass('border-slate-300');
             // restore conditional borders
            if($(this).attr('id').includes('harga_dus') || $(this).attr('id').includes('harga_pack') || $(this).attr('id').includes('harga_pcs')) {
                 // if parent is indigo bg
                 if($(this).closest('.bg-blue-50').length) $(this).addClass('border-blue-200').removeClass('border-slate-300');
                 if($(this).closest('.bg-rose-50').length) $(this).addClass('border-rose-200').removeClass('border-slate-300');
            }
            // Remove error message INSIDE the wrapper
             $(this).closest('.relative').find('.error-message').remove();
        });
    });
</script>
