<form action="{{ route('coa_portax.store') }}" id="formCoa" method="POST" class="space-y-4">
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
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important;
        }
    </style>

    <!-- Header/Title for Modal -->
    <div class="border-b border-slate-200 pb-3 mb-3 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800">Tambah Akun</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="space-y-4">
        <!-- Kode Akun -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kode Akun</label>
            <input type="text" name="kode_akun" id="kode_akun" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Contoh: 11101">
        </div>

        <!-- Nama Akun -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Nama Akun</label>
            <input type="text" name="nama_akun" id="nama_akun" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Nama Akun">
        </div>

        <!-- Parent Account -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Parent Account (Sub Akun)</label>
            <select name="sub_akun" id="sub_akun" class="w-full select2Kodeakun">
                <option value="">Pilih Parent Account</option>
                <option value="0">0 - TOP LEVEL</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kategori -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kategori</label>
            <select name="kode_kategori" id="kode_kategori" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="">Pilih Kategori</option>
                @foreach ($kategori as $d)
                    <option value="{{ $d->kode_kategori }}">{{ $d->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end pt-3 border-t border-slate-100 mt-3 gap-2">
        <button type="button" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors" onclick="closeTailwindModal()">Batal</button>
        <button type="submit" id="btnSimpan" class="px-4 py-2 text-sm font-medium text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-sm shadow-blue-200 transition-colors flex items-center gap-2">
            <i class="fas fa-save"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formCoa");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Parent Account',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#kode_akun").mask('00000');

        form.submit(function(e) {
            let isValid = true;

            // Reset errors
            form.find(".error-message").remove();
            form.find("input, select").removeClass("!border-red-500 invalid-border").addClass("border-slate-300");

            function showError(field, message) {
                $(field).removeClass("border-slate-300").addClass("!border-red-500 invalid-border");
                let wrapper = $(field).closest('.relative');
                if (wrapper.find('.error-message').length === 0) {
                    wrapper.append(`<p class="text-red-500 text-[10px] mt-1 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                isValid = false;
            }

            const kode_akun = form.find("#kode_akun");
            const nama_akun = form.find("#nama_akun");
            const sub_akun = form.find("#sub_akun");
            const kode_kategori = form.find("#kode_kategori");

            if (kode_akun.val() == "") {
                showError(kode_akun, "Kode Akun harus diisi");
            }
            if (nama_akun.val() == "") {
                showError(nama_akun, "Nama Akun harus diisi");
            }
            if (sub_akun.val() == "") {
                showError(sub_akun, "Parent Account harus diisi");
            }
            if (kode_kategori.val() == "") {
                showError(kode_kategori, "Kategori harus diisi");
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                buttonDisable();
            }
        });

        // Remove error styling on change/input
        form.find("input, select").on('input change', function() {
            $(this).removeClass("!border-red-500 invalid-border").addClass("border-slate-300");
            $(this).closest('.relative').find('.error-message').remove();
        });
    });
</script>
