<form action="{{ route('sakasbesar.store') }}" method="POST" id="formSaldoawalkasbesar">
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
        <h3 class="text-lg font-bold text-slate-800">Buat Saldo Awal Kas Besar</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <!-- Cabang (Conditional) -->
        @hasanyrole($roles_show_cabang)
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang <span class="text-red-500">*</span></label>
            <select name="kode_cabang" id="kode_cabang" class="w-full select2 transition-all">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        @endhasanyrole

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Bulan -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Bulan <span class="text-red-500">*</span></label>
                <select name="bulan" id="bulan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                    <option value="">Pilih Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Tahun <span class="text-red-500">*</span></label>
                <select name="tahun" id="tahun" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <!-- Jumlah Saldo -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jumlah Saldo <span class="text-red-500">*</span></label>
            <input type="text" name="jumlah_saldo" id="jumlah_saldo"
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
            <span>Simpan Data</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        $(".select2").select2({
            dropdownParent: $('#tailwindModal'),
            placeholder: 'Pilih Cabang',
            allowClear: true
        });
        $(".money").maskMoney();
        
        $("#formSaldoawalkasbesar").submit(function(e) {
            let isValid = true;
            // Reset errors
            $("#formSaldoawalkasbesar .error-message").remove();
            $("#formSaldoawalkasbesar input, #formSaldoawalkasbesar select").removeClass("!border-red-500 invalid-border").addClass("border-slate-300");

            function showError(field, message) {
                $(field).removeClass("border-slate-300").addClass("!border-red-500 invalid-border");
                let wrapper = $(field).closest('.relative');
                if (wrapper.find('.error-message').length === 0) {
                     wrapper.append(`<p class="text-red-500 text-[10px] mt-1 error-message font-medium"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                isValid = false;
            }

            @hasanyrole($roles_show_cabang)
            let kode_cabang = $("#formSaldoawalkasbesar select[name='kode_cabang']");
            if (kode_cabang.val() == "") showError(kode_cabang, "Pilih Cabang");
            @endhasanyrole

            let bulan = $("#formSaldoawalkasbesar select[name='bulan']");
            if (bulan.val() == "") showError(bulan, "Pilih Bulan");

            let tahun = $("#formSaldoawalkasbesar select[name='tahun']");
            if (tahun.val() == "") showError(tahun, "Pilih Tahun");

            let jumlah_saldo = $("#formSaldoawalkasbesar input[name='jumlah_saldo']");
            if (jumlah_saldo.val() == "" || jumlah_saldo.val() == "0") showError(jumlah_saldo, "Jumlah Saldo harus diisi");

            if (!isValid) e.preventDefault();
        });

        $("#formSaldoawalkasbesar input, #formSaldoawalkasbesar select").on('input change', function() {
            $(this).removeClass("!border-red-500 invalid-border").addClass("border-slate-300");
            $(this).closest('.relative').find('.error-message').remove();
        });
    });
</script>
