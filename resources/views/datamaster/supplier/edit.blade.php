<form action="{{ route('supplier.update', Crypt::encrypt($supplier->kode_supplier)) }}" id="formeditSupplier" method="POST" class="space-y-4">
    @csrf
    @method('PUT')
    <!-- Header -->
    <div class="border-b border-slate-200 pb-2 mb-2 flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-800">Edit Supplier</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <div class="space-y-4 pt-1">
        
        <!-- Kode Supplier (ReadOnly) -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kode Supplier</label>
            <input type="text" name="kode_supplier" value="{{ $supplier->kode_supplier }}" disabled 
                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-500 cursor-not-allowed placeholder-slate-400">
        </div>

        <!-- Nama Supplier -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Nama Supplier <span class="text-red-500">*</span></label>
            <input type="text" name="nama_supplier" id="nama_supplier" value="{{ $supplier->nama_supplier }}"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                placeholder="Nama Supplier">
        </div>

        <!-- Alamat Supplier -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Alamat</label>
            <textarea name="alamat_supplier" id="alamat_supplier" rows="2" 
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400 resize-none" 
                placeholder="Alamat Supplier">{{ $supplier->alamat_supplier }}</textarea>
        </div>

        <!-- Contact Person & No HP -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" value="{{ $supplier->contact_person }}"
                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                    placeholder="Contact Person">
            </div>
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. HP</label>
                <input type="text" name="no_hp_supplier" id="no_hp_supplier" value="{{ $supplier->no_hp_supplier }}"
                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                    placeholder="No. HP">
            </div>
        </div>

        <!-- Email -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Email</label>
            <input type="email" name="email_supplier" id="email_supplier" value="{{ $supplier->email_supplier }}"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                placeholder="Email">
        </div>

        <!-- No Rekening -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. Rekening</label>
            <input type="text" name="no_rekening_supplier" id="no_rekening_supplier" value="{{ $supplier->no_rekening_supplier }}"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                placeholder="No. Rekening">
        </div>

    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end pt-4 border-t border-slate-100 mt-4 gap-3 sticky bottom-0 bg-white pb-1 backdrop-blur-sm">
        <button type="button" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors" onclick="closeTailwindModal()">Batal</button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-sm shadow-blue-200 transition-colors flex items-center gap-2">
            <i class="fas fa-save"></i>
            <span>Update</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        // Validation Logic
        $("#formeditSupplier").submit(function(e) {
            // Reset Error Styling
            $('#formeditSupplier .error-message').remove();
            $('#formeditSupplier input, #formeditSupplier textarea').removeClass('!border-red-500 invalid-border').addClass('border-slate-300');
            
            let isValid = true;
            const form = $(this);
            
            function showError(fieldId, message) {
                const input = form.find(`#${fieldId}`);
                if (input.length == 0) return;

                input.removeClass('border-slate-300').addClass('!border-red-500 invalid-border');
                
                let wrapper = input.closest('.relative');
                if (wrapper.length === 0) wrapper = input.parent();
                
                if (wrapper.next('.error-message').length === 0) {
                    wrapper.after(`<p class="text-red-500 text-[10px] mt-0.5 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                isValid = false;
            }

            // Required Fields
            if (form.find("#nama_supplier").val() == "") showError('nama_supplier', 'Nama Supplier wajib diisi');

            if (!isValid) e.preventDefault();
        });

        // Remove error on input logic
        $('#formeditSupplier input, #formeditSupplier textarea').on('input change', function() {
            $(this).removeClass('!border-red-500 invalid-border').addClass('border-slate-300');
            $(this).closest('.relative').next('.error-message').remove();
        });
    });
</script>
