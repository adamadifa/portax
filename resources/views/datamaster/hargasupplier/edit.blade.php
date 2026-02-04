<form action="{{ route('hargasupplier.update', Crypt::encrypt($hargasupplier->kode_produk)) }}" method="POST" id="formHargaSupplier">
    @csrf
    @method('PUT')
    
    <!-- Header -->
    <div class="border-b border-slate-200 pb-3 mb-3 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800">Edit Data Harga Supplier</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <!-- Produk (Readonly) -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Produk</label>
            <input type="text" value="{{ $hargasupplier->kode_produk }} - {{ $hargasupplier->produk->nama_produk }}"
                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-500 focus:outline-none cursor-not-allowed"
                readonly>
        </div>

        <!-- Harga -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Harga <span class="text-red-500">*</span></label>
            <input type="text" name="harga" id="harga" value="{{ formatAngka($hargasupplier->harga) }}"
                class="money w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-right text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                placeholder="0">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-500">
                <i class="ti ti-moneybag"></i>
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
        $("#harga").maskMoney();
        
        $("#formHargaSupplier").submit(function(e) {
            let isValid = true;
            $("#formHargaSupplier .error-message").remove();
            $("#formHargaSupplier input").removeClass("!border-red-500 invalid-border").addClass("border-slate-300");

            function showError(field, message) {
                $(field).removeClass("border-slate-300").addClass("!border-red-500 invalid-border");
                let wrapper = $(field).closest('.relative');
                if (wrapper.find('.error-message').length === 0) {
                     wrapper.append(`<p class="text-red-500 text-[10px] mt-1 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                isValid = false;
            }

            let harga = $("#formHargaSupplier input[name='harga']");
            if (harga.val() == "") showError(harga, "Harga harus diisi");

            if (!isValid) e.preventDefault();
        });

        $("#formHargaSupplier input").on('input change', function() {
            $(this).removeClass("!border-red-500 invalid-border").addClass("border-slate-300");
            $(this).closest('.relative').find('.error-message').remove();
        });
    });
</script>
