<form action="{{ route('kaskecil.update', Crypt::encrypt($kaskecil->id)) }}" method="POST" id="formeditKaskecil">
    @csrf
    @method('PUT')

    <!-- Cabang -->
    @hasanyrole($roles_show_cabang)
    <div class="mb-4 relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang</label>
        <select name="kode_cabang" id="kode_cabang" class="select2Kodecabangedit w-full" {{ !empty($kaskecil->kode_klaim) ? 'disabled' : '' }}>
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $d)
                <option value="{{ $d->kode_cabang }}" {{ $kaskecil->kode_cabang == $d->kode_cabang ? 'selected' : '' }}>
                    {{ textuppercase($d->nama_cabang) }}
                </option>
            @endforeach
        </select>
    </div>
    @endhasanyrole

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <!-- No. Bukti -->
        <div class="relative">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. Bukti <span class="text-red-500">*</span></label>
             <input type="text" name="no_bukti" value="{{ $kaskecil->no_bukti }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors {{ !empty($kaskecil->kode_klaim) ? 'bg-slate-100 text-slate-500' : '' }}" {{ !empty($kaskecil->kode_klaim) ? 'readonly' : '' }}>
        </div>
        <!-- Tanggal -->
        <div class="relative">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Tanggal <span class="text-red-500">*</span></label>
             <input type="text" name="tanggal" value="{{ $kaskecil->tanggal }}" class="flatpickr-date w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Pilih Tanggal">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
        <!-- Akun (Col 5) -->
        <div class="md:col-span-5 relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Akun <span class="text-red-500">*</span></label>
            <select name="kode_akun" id="kode_akun" class="select2Kodeakunedit w-full">
                <option value="">Pilih Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}" {{ $kaskecil->kode_akun == $d->kode_akun ? 'selected' : '' }}>
                        {{ $d->kode_akun }} - {{ $d->nama_akun }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Jumlah (Col 4) -->
        <div class="md:col-span-4 relative">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jumlah <span class="text-red-500">*</span></label>
             <input type="text" name="jumlah" id="jumlah" value="{{ formatAngka($kaskecil->jumlah) }}" class="money text-right w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors font-medium {{ !empty($kaskecil->kode_klaim) ? 'bg-slate-100 text-slate-500' : '' }}" {{ !empty($kaskecil->kode_klaim) ? 'readonly' : '' }}>
        </div>

        <!-- Posisi (Col 3) -->
        <div class="md:col-span-3 relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Posisi <span class="text-red-500">*</span></label>
            <select name="debet_kredit_edit" id="debet_kredit" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none" {{ !empty($kaskecil->kode_klaim) ? 'disabled' : '' }}>
                <option value="D" {{ $kaskecil->debet_kredit == 'D' ? 'selected' : '' }}>DEBET</option>
                <option value="K" {{ $kaskecil->debet_kredit == 'K' ? 'selected' : '' }}>KREDIT</option>
            </select>
            <input type="hidden" name="debet_kredit" value="{{ $kaskecil->debet_kredit }}">
        </div>
    </div>

    <!-- Keterangan -->
    <div class="mb-6 relative">
         <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Keterangan <span class="text-red-500">*</span></label>
         <input type="text" name="keterangan" id="keterangan" value="{{ $kaskecil->keterangan }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Keterangan transaksi...">
    </div>

    <div class="mb-2">
        <button class="w-full bg-[#003d9e] hover:bg-blue-800 text-white py-3 rounded-lg font-bold text-base transition-all shadow-sm shadow-blue-200 flex items-center justify-center gap-2 active:scale-95" type="submit" id="btnSimpan">
            <i class="ti ti-send"></i> Simpan Perubahan
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const formeditKaskecil = $('#formeditKaskecil');
        
        // Init Libraries
        formeditKaskecil.find(".flatpickr-date").flatpickr();
        formeditKaskecil.find(".money").maskMoney();
        
        const select2Kodecabangedit = $(".select2Kodecabangedit");
        if (select2Kodecabangedit.length) {
            select2Kodecabangedit.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                dropdownParent: select2Kodecabangedit.parent()
            });
        }

        const select2Kodeakunedit = $(".select2Kodeakunedit");
        if (select2Kodeakunedit.length) {
            select2Kodeakunedit.select2({
                placeholder: 'Pilih Akun',
                allowClear: true,
                dropdownParent: select2Kodeakunedit.parent()
            });
        }

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`<i class="ti ti-loader fa-spin me-2"></i> Loading..`);
        }

        formeditKaskecil.submit(function(e) {
            const kode_cabang = formeditKaskecil.find("#kode_cabang").val();
            const kode_akun = formeditKaskecil.find("#kode_akun").val();
            const keterangan = formeditKaskecil.find("#keterangan").val();
            const debet_kredit = formeditKaskecil.find("#debet_kredit").val();
            const jumlah = formeditKaskecil.find("#jumlah").val();

            // Needs explicit check for hidden/disabled input value if referencing standard logic, 
            // but here we check the fields we expect.
            
            // Note: If fields are disabled/readonly, they might still have values. 
            // Checking logic below checks for empty strings.
            
            // If kode_cabang is disabled (because of claim), val() might be null or we shouldn't validate it strictly if we can't change it?
            // Original code validated it. If disabled, select2 might behave differently.
            // But let's assume standard validation holds.
            
             if (kode_cabang == "" && !$('#kode_cabang').prop('disabled')) { // Only check if enabled
                Swal.fire({ title: "Oops!", text: "Cabang Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { formeditKaskecil.find("#kode_cabang").select2('open'); }});
                return false;
            } else if (kode_akun == "") {
                Swal.fire({ title: "Oops!", text: "Akun Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { formeditKaskecil.find("#kode_akun").select2('open'); }});
                return false;
            } else if (keterangan == "") {
                Swal.fire({ title: "Oops!", text: "Keterangan Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { formeditKaskecil.find("#keterangan").focus(); }});
                return false;
            } else if (jumlah == "") {
                Swal.fire({ title: "Oops!", text: "Jumlah Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: () => { formeditKaskecil.find("#jumlah").focus(); }});
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
