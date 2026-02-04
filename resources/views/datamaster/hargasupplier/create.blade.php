<form action="{{ route('hargasupplier.store') }}" method="POST" id="formHargaSupplier">
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
        <h3 class="text-lg font-bold text-slate-800">Tambah Data Harga Supplier</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <!-- Produk -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Produk <span class="text-red-500">*</span></label>
            <select name="kode_produk" id="kode_produk"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2">
                <option value="">Pilih Produk</option>
                @foreach ($produk as $d)
                    <option value="{{ $d->kode_produk }}">{{ $d->kode_produk }} - {{ $d->nama_produk }}</option>
                @endforeach
            </select>
        </div>

        <!-- Harga -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Harga <span class="text-red-500">*</span></label>
            <input type="text" name="harga" id="harga"
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
        $(".select2").select2({
            dropdownParent: $('#tailwindModal')
        });
        $("#harga").maskMoney();
        
        $("#formHargaSupplier").submit(function(e) {
            let isValid = true;
            // Reset errors
            $("#formHargaSupplier .error-message").remove();
            $("#formHargaSupplier input, #formHargaSupplier select").removeClass("!border-red-500 invalid-border").addClass("border-slate-300");

            function showError(field, message) {
                $(field).removeClass("border-slate-300").addClass("!border-red-500 invalid-border");
                let wrapper = $(field).closest('.relative');
                if (wrapper.find('.error-message').length === 0) {
                     wrapper.append(`<p class="text-red-500 text-[10px] mt-1 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                isValid = false;
            }

            let kode_produk = $("#formHargaSupplier select[name='kode_produk']");
            if (kode_produk.val() == "") showError(kode_produk, "Pilih Produk");

            let harga = $("#formHargaSupplier input[name='harga']");
            if (harga.val() == "") showError(harga, "Harga harus diisi");

            if (!isValid) e.preventDefault();
        });

        $("#formHargaSupplier input, #formHargaSupplier select").on('input change', function() {
            $(this).removeClass("!border-red-500 invalid-border").addClass("border-slate-300");
            $(this).closest('.relative').find('.error-message').remove();
        });
    });
</script>
