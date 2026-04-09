<form method="POST" action="{{ route('laporangudangcabang.cetakrekappersediaan') }}" id="frmLaporanrekappersediaan" target="_blank" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        @hasanyrole($roles_show_cabang)
            <div class="md:col-span-12 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 transition-colors text-slate-400">
                    <i class="ti ti-building"></i>
                </div>
                <select name="kode_cabang_persediaan" id="kode_cabang_persediaan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all transition-colors appearance-none select2Kodecabangpersediaan font-medium text-slate-700">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        @endrole

        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="ti ti-calendar"></i>
            </div>
            <input type="text" name="dari" id="dari_persediaan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date" placeholder="Dari">
        </div>
        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="ti ti-calendar"></i>
            </div>
            <input type="text" name="sampai" id="sampai_persediaan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date" placeholder="Sampai">
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 mt-4">
        <div class="flex-grow">
            <button type="submit" name="submitButton" 
                class="w-full h-12 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all duration-200 active:scale-95" 
                id="submitButton">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
        </div>
        <div class="w-full sm:w-16">
            <button type="submit" name="exportButton" 
                class="w-full h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95" 
                id="exportButton" title="Export Excel">
                <i class="fas fa-file-excel text-lg"></i>
            </button>
        </div>
    </div>
</form>

@push('myscript')
    <script>
        $(function() {
            const form = $("#frmLaporanrekappersediaan");
            const select2Kodecabangpersediaan = form.find('.select2Kodecabangpersediaan');
            if (select2Kodecabangpersediaan.length) {
                select2Kodecabangpersediaan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            form.submit(function() {
                const dari = form.find("#dari_persediaan").val();
                const sampai = form.find("#sampai_persediaan").val();
                const kode_cabang = form.find("#kode_cabang_persediaan").val();
                var start = new Date(dari);
                var end = new Date(sampai);

                @hasanyrole($roles_show_cabang)
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Kode Cabang Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#kode_cabang_persediaan").focus();
                        },
                    });
                    return false;
                }
                @endrole

                if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#dari_persediaan").focus();
                        },
                    });
                    return false;
                } else if (sampai == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Sampai Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#sampai_persediaan").focus();
                        },
                    });
                    return false;
                } else if (start.getTime() > end.getTime()) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#sampai_persediaan").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
