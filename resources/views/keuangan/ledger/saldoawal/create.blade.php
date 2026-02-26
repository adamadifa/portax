<form action="{{ route('saledger.store') }}" id="formSaldoawalledger" method="POST">
    @csrf
    <input type="hidden" name="cekgetsaldo" id="cekgetsaldo" value="0">

    <style>
        .select2-container .select2-selection--single {
            height: 46px !important;
            padding: 10px 12px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            background-color: #fff !important;
            position: relative;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0 !important;
            color: #1e293b !important;
            font-size: 0.875rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            top: 1px !important;
            right: 8px !important;
        }
    </style>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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

    <!-- Bank Selection -->
    <div class="mb-4 relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Pilih Bank <span class="text-red-500">*</span></label>
        <select name="kode_bank" id="kode_bank" class="select2Kodebank w-full">
            <option value="">Pilih Bank</option>
            @foreach ($bank as $d)
                <option value="{{ $d->kode_bank }}">{{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}</option>
            @endforeach
        </select>
    </div>

    <!-- Jumlah & Get Saldo -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end mb-6">
        <div class="md:col-span-9 relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jumlah Saldo <span class="text-red-500">*</span></label>
            <input type="text" name="jumlah" id="jumlah" readonly class="money text-right w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors font-bold text-slate-800" placeholder="0">
        </div>
        <div class="md:col-span-3">
            <button type="button" id="getsaldo" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-2.5 rounded-lg font-bold text-xs transition-all flex items-center justify-center gap-1 active:scale-95">
                <i class="ti ti-refresh text-sm"></i> GET SALDO
            </button>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-8">
        <button class="w-full bg-[#003d9e] hover:bg-blue-800 text-white py-3 rounded-lg font-bold text-base transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2 active:scale-95" type="submit" id="btnSimpan">
            <i class="ti ti-send"></i> Submit Saldo Awal
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formSaldoawalledger");

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number.toString().split("").reverse().join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (rupiah.split("", rupiah.length - 1).reverse().join(""));
            } else {
                return number;
            }
        }

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`<i class="ti ti-loader fa-spin me-2"></i> Processing..`);
        }

        const select2Kodebank = $('.select2Kodebank');
        if (select2Kodebank.length) {
            select2Kodebank.select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownParent: select2Kodebank.parent()
            });
        }

        form.find("#kode_bank,#bulan,#tahun").change(function() {
            form.find("#cekgetsaldo").val(0);
            form.find("#jumlah").val(0).addClass('bg-slate-50').removeClass('bg-white');
        });

        $("#getsaldo").click(function() {
            const bulan = form.find("#bulan").val();
            const tahun = form.find("#tahun").val();
            const kode_bank = form.find("#kode_bank").val();
            
            if (bulan == "") {
                Swal.fire({ title: "Oops!", text: "Bulan Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { form.find("#bulan").focus(); } });
                return false;
            } else if (tahun == "") {
                Swal.fire({ title: "Oops!", text: "Tahun Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { form.find("#tahun").focus(); } });
                return false;
            } else if (kode_bank == "") {
                Swal.fire({ title: "Oops!", text: "Bank Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { $(".select2Kodebank").select2('open'); } });
                return false;
            } else {
                const $btn = $(this);
                $btn.prop('disabled', true).html(`<i class="ti ti-loader fa-spin"></i> Loading..`);
                
                $.ajax({
                    type: 'POST',
                    url: '/saledger/getsaldo',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_bank: kode_bank
                    },
                    cache: false,
                    success: function(response) {
                        $btn.prop('disabled', false).html(`<i class="ti ti-refresh text-sm"></i> GET SALDO`);
                        
                        if (response.data.ceksaldo == 0) {
                            form.find("#jumlah").prop('readonly', false).removeClass('bg-slate-50').addClass('bg-white').maskMoney();
                            form.find("#jumlah").focus();
                            form.find("#cekgetsaldo").val(1);
                        } else if (response.data.ceksaldobulanlalu == 0 && response.data.ceksaldobulanini == 0) {
                            Swal.fire({ title: "Oops!", text: "Saldo Bulan Sebelumnya Belum Di Set !", icon: "warning", showConfirmButton: true, didClose: () => { form.find("#bulan").focus(); } });
                        } else {
                            form.find("#cekgetsaldo").val(1);
                            form.find("#jumlah").val(convertToRupiah(response.data.saldo)).prop('readonly', true).addClass('bg-slate-50').removeClass('bg-white');
                        }
                    },
                    error: function() {
                         $btn.prop('disabled', false).html(`<i class="ti ti-refresh text-sm"></i> GET SALDO`);
                         Swal.fire({ title: "Error", text: "Terjadi kesalahan saat mengambil data saldo.", icon: "error" });
                    }
                });
            }
        });

        form.submit(function() {
            const cekgetsaldo = form.find("#cekgetsaldo").val();
            if (cekgetsaldo === '0') {
                Swal.fire({ title: "Oops!", text: "Silahkan Get Saldo Terlebih Dahulu !", icon: "warning", showConfirmButton: true, didClose: () => { $("#getsaldo").focus(); } });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
