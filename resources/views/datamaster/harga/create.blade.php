<form action="{{ route('harga.store') }}" id="formcreateHarga" method="POST" class="space-y-4">
    @csrf
    
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
        <h3 class="text-lg font-bold text-slate-800">Tambah Data Harga</h3>
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
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                placeholder="Kode Harga">
        </div>

        <!-- Produk -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Produk <span class="text-red-500">*</span></label>
            <select name="kode_produk" id="kode_produk"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="">Pilih Produk</option>
                @foreach ($produk as $p)
                    <option value="{{ $p->kode_produk }}">{{ $p->nama_produk }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
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
                    placeholder="0">
            </div>
            <!-- Pack -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-blue-50 px-1 text-[10px] font-bold text-indigo-900 z-10">Per Pack</label>
                <input type="text" name="harga_pack" id="harga_pack"
                    class="money w-full px-3 py-2.5 bg-white border border-blue-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                    placeholder="0">
            </div>
            <!-- Pcs -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-blue-50 px-1 text-[10px] font-bold text-indigo-900 z-10">Per Pcs</label>
                <input type="text" name="harga_pcs" id="harga_pcs"
                    class="money w-full px-3 py-2.5 bg-white border border-blue-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                    placeholder="0">
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
                    placeholder="0">
            </div>
            <!-- Retur Pack -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-rose-50 px-1 text-[10px] font-bold text-rose-900 z-10">Retur Pack</label>
                <input type="text" name="harga_retur_pack" id="harga_retur_pack"
                    class="money w-full px-3 py-2.5 bg-white border border-rose-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors"
                    placeholder="0">
            </div>
            <!-- Retur Pcs -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-rose-50 px-1 text-[10px] font-bold text-rose-900 z-10">Retur Pcs</label>
                <input type="text" name="harga_retur_pcs" id="harga_retur_pcs"
                    class="money w-full px-3 py-2.5 bg-white border border-rose-200 rounded-lg text-right text-sm placeholder-slate-300 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors"
                    placeholder="0">
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
                <option value="IN">INCLUDE</option>
                <option value="EX">EXCLUDE</option>
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
                <option value="1">Aktif</option>
                <option value="0">Non Aktif</option>
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
                <option value="1">Aktif</option>
                <option value="0">Non Aktif</option>
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
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] appearance-none">
                <option value="">Pilih Kategori</option>
                @foreach ($kategori_salesman as $k)
                    <option value="{{ $k->kode_kategori_salesman }}">{{ $k->nama_kategori_salesman }}</option>
                @endforeach
            </select>
             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>

        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang <span class="text-red-500">*</span></label>
            <select name="kode_cabang" id="kode_cabang"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] appearance-none">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $c)
                    <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                @endforeach
            </select>
             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-700">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end pt-3 border-t border-slate-100 mt-3 gap-2">
        <button type="button" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors" onclick="closeTailwindModal()">Batal</button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-sm shadow-blue-200 transition-colors flex items-center gap-2">
            <i class="fas fa-save"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        $(".money").maskMoney();
        $("#formcreateHarga").submit(function(e) {
            let isValid = true;

            // Reset all previous errors inside this form
            $("#formcreateHarga .error-message").remove();
            $("#formcreateHarga input, #formcreateHarga select").removeClass("!border-red-500 invalid-border").addClass("border-slate-300");

             // Restore specific border colors for themed inputs if they were reset
            $("#formcreateHarga .bg-blue-50 input").removeClass("border-slate-300").addClass("border-blue-200");
            $("#formcreateHarga .bg-rose-50 input").removeClass("border-slate-300").addClass("border-rose-200");


            // Helper function to show error
            function showError(field, message) {
                // Apply Red Border with !important to ensure visibility
                $(field).removeClass("border-slate-300 border-blue-200 border-rose-200").addClass("!border-red-500 invalid-border");
                
                // Append error INSIDE the closest relative wrapper
                let wrapper = $(field).closest('.relative');
                if (wrapper.find('.error-message').length === 0) {
                     wrapper.append(`<p class="text-red-500 text-[10px] mt-1 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                
                isValid = false;
            }

            // Validate Kode Harga
            let kode_harga = $("#formcreateHarga input[name='kode_harga']");
            if (kode_harga.val() == "") {
                showError(kode_harga, "Kode Harga harus diisi");
            }

            // Validate Produk
            let kode_produk = $("#formcreateHarga select[name='kode_produk']");
            if (kode_produk.val() == "") {
                showError(kode_produk, "Pilih Produk");
            }
            
            // Validate Harga Jual
            let harga_dus = $("#formcreateHarga input[name='harga_dus']");
            if (harga_dus.val() == "") showError(harga_dus, "Wajib diisi");
            
            let harga_pack = $("#formcreateHarga input[name='harga_pack']");
            if (harga_pack.val() == "") showError(harga_pack, "Wajib diisi");

            let harga_pcs = $("#formcreateHarga input[name='harga_pcs']");
            if (harga_pcs.val() == "") showError(harga_pcs, "Wajib diisi");

            // Validate Settings
            // PPN is optional now as per request
            // let status_ppn = $("#formcreateHarga select[name='status_ppn']");
            // if (status_ppn.val() == "") showError(status_ppn, "Pilih PPN");

            let status_promo = $("#formcreateHarga select[name='status_promo']");
            if (status_promo.val() == "") showError(status_promo, "Pilih Promo");

            let status_aktif_harga = $("#formcreateHarga select[name='status_aktif_harga']");
            if (status_aktif_harga.val() == "") showError(status_aktif_harga, "Pilih Status");

             // Validate Kategori Salesman
            let kode_kategori_salesman = $("#formcreateHarga select[name='kode_kategori_salesman']");
            if (kode_kategori_salesman.val() == "") showError(kode_kategori_salesman, "Pilih Kategori");

             // Validate Cabang
            let kode_cabang = $("#formcreateHarga select[name='kode_cabang']");
            if (kode_cabang.val() == "") showError(kode_cabang, "Pilih Cabang");

            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Remove Error on Input (Scoped to this form only)
        $("#formcreateHarga input, #formcreateHarga select").on('input change', function() {
            $(this).removeClass("!border-red-500 invalid-border").addClass("border-slate-300");
             // restore conditional borders
            if($(this).closest('.bg-blue-50').length) $(this).addClass('border-blue-200').removeClass('border-slate-300');
            if($(this).closest('.bg-rose-50').length) $(this).addClass('border-rose-200').removeClass('border-slate-300');
            
             // Remove error message INSIDE the wrapper
             $(this).closest('.relative').find('.error-message').remove();
        });
    });
</script>
