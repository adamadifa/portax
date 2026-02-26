<form action="{{ route('mutasibank.update', Crypt::encrypt($mutasibank->no_bukti)) }}" method="POST" id="formMutasibank">
    @csrf
    @method('PUT')

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

    <!-- Bank Selection -->
    <div class="mb-4 relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Pilih Bank <span class="text-red-500">*</span></label>
        <select name="kode_bank" id="kode_bank" class="select2Kodebank w-full">
            <option value="">Pilih Bank</option>
            @foreach ($bank as $d)
                <option value="{{ $d->kode_bank }}" {{ $mutasibank->kode_bank == $d->kode_bank ? 'selected' : '' }}>
                    {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tanggal -->
    <div class="mb-4 relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Tanggal <span class="text-red-500">*</span></label>
        <input type="text" name="tanggal" id="tanggal" value="{{ $mutasibank->tanggal }}" class="flatpickr-date w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Pilih Tanggal">
    </div>

    <!-- Keterangan -->
    <div class="mb-4 relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Keterangan <span class="text-red-500">*</span></label>
        <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Keterangan transaksi...">{{ $mutasibank->keterangan }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
        <!-- Akun -->
        <div class="md:col-span-12 relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Akun <span class="text-red-500">*</span></label>
            <select name="kode_akun" id="kode_akun" class="select2Kodeakun w-full">
                <option value="">Pilih Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}" {{ $mutasibank->kode_akun == $d->kode_akun ? 'selected' : '' }}>
                        {{ $d->kode_akun }} {{ $d->nama_akun }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Jumlah -->
        <div class="md:col-span-8 relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jumlah <span class="text-red-500">*</span></label>
            <input type="text" name="jumlah" id="jumlah" value="{{ formatAngka($mutasibank->jumlah) }}" class="money text-right w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors font-medium" placeholder="0">
        </div>

        <!-- Debet / Kredit -->
        <div class="md:col-span-4 relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Posisi <span class="text-red-500">*</span></label>
            <select name="debet_kredit" id="debet_kredit" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="">Debet / Kredit</option>
                <option value="D" {{ $mutasibank->debet_kredit == 'D' ? 'selected' : '' }}>DEBET</option>
                <option value="K" {{ $mutasibank->debet_kredit == 'K' ? 'selected' : '' }}>KREDIT</option>
            </select>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-6">
        <button class="w-full bg-[#003d9e] hover:bg-blue-800 text-white py-3 rounded-lg font-bold text-base transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2 active:scale-95" type="submit" id="btnSimpan">
            <i class="ti ti-send"></i> Update
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formMutasibank");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`<i class="ti ti-loader fa-spin me-2"></i> Loading..`);
        }

        const select2Kodebank = $('.select2Kodebank');
        if (select2Kodebank.length) {
            select2Kodebank.select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownParent: select2Kodebank.parent()
            });
        }

        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.select2({
                placeholder: 'Pilih Kode Akun',
                allowClear: true,
                dropdownParent: select2Kodeakun.parent()
            });
        }

        form.submit(function(e) {
            const kode_bank = form.find("#kode_bank").val();
            const tanggal = form.find("#tanggal").val();
            const keterangan = form.find("#keterangan").val();
            const kode_akun = form.find("#kode_akun").val();
            const jumlah = form.find("#jumlah").val();
            const debet_kredit = form.find("#debet_kredit").val();

            if (kode_bank == "") {
                Swal.fire({ title: "Oops!", text: "Silahkan Pilih Bank Terlebih Dahulu !", icon: "warning", showConfirmButton: true, didClose: () => { form.find(".select2Kodebank").select2('open'); } });
                return false;
            } else if (tanggal == "") {
                Swal.fire({ title: "Oops!", text: "Tanggal Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { form.find("#tanggal").focus(); } });
                return false;
            } else if (keterangan == "") {
                Swal.fire({ title: "Oops!", text: "Keterangan Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { form.find("#keterangan").focus(); } });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({ title: "Oops!", text: "Kode Akun Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { form.find(".select2Kodeakun").select2('open'); } });
                return false;
            } else if (jumlah == "") {
                Swal.fire({ title: "Oops!", text: "Jumlah Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { form.find("#jumlah").focus(); } });
                return false;
            } else if (debet_kredit == "") {
                Swal.fire({ title: "Oops!", text: "Debet / Kredit Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { form.find("#debet_kredit").focus(); } });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
