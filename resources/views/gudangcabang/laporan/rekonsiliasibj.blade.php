<form method="POST" action="{{ route('laporangudangcabang.cetakrekonsiliasibj') }}" id="frmRekonsiliasibj" target="_blank" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        @hasanyrole($roles_show_cabang)
            <div class="md:col-span-12 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 transition-colors text-slate-400">
                    <i class="ti ti-building"></i>
                </div>
                <select name="kode_cabang_rekonsiliasi" id="kode_cabang_rekonsiliasi" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all transition-colors appearance-none select2Kodecabangrekonsiliasi font-medium text-slate-700">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        @endrole

        <div class="md:col-span-12 relative text-left">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 text-slate-400">
                <i class="ti ti-tie"></i>
            </div>
            <select name="kode_salesman" id="kode_salesman_rekonsiliasi" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all transition-colors appearance-none select2Kodesalesmanrekonsiliasi font-medium text-slate-700">
                <option value="">Pilih Salesman</option>
            </select>
        </div>

        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="ti ti-calendar"></i>
            </div>
            <input type="text" name="dari" id="dari_rekonsiliasi" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date" placeholder="Dari">
        </div>
        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="ti ti-calendar"></i>
            </div>
            <input type="text" name="sampai" id="sampai_rekonsiliasi" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date" placeholder="Sampai">
        </div>

        <div class="md:col-span-12 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 text-slate-400">
                <i class="ti ti-settings"></i>
            </div>
            <select name="jenis_rekonsiliasi" id="jenis_rekonsiliasi" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all transition-colors appearance-none form-select font-medium text-slate-700">
                <option value="">Jenis Rekonsiliasi</option>
                <option value="1">Penjualan</option>
                <option value="2">Retur</option>
                <option value="3">Promosi</option>
            </select>
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
            const form = $("#frmRekonsiliasibj");
            const select2Kodecabangrekonsiliasi = form.find('.select2Kodecabangrekonsiliasi');
            if (select2Kodecabangrekonsiliasi.length) {
                select2Kodecabangrekonsiliasi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanrekonsiliasi = form.find(".select2Kodesalesmanrekonsiliasi");
            if (select2Kodesalesmanrekonsiliasi.length) {
                select2Kodesalesmanrekonsiliasi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabang() {
                var kode_cabang = form.find("#kode_cabang_rekonsiliasi").val();
                $.ajax({
                    type: 'POST',
                    url: '/salesman/getsalesmanbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        form.find("#kode_salesman_rekonsiliasi").html(respond);
                    }
                });
            }

            getsalesmanbyCabang();
            form.find("#kode_cabang_rekonsiliasi").change(function(e) {
                getsalesmanbyCabang();
            });

            form.submit(function() {
                const kode_salesman = form.find("#kode_salesman_rekonsiliasi").val();
                const dari = form.find("#dari_rekonsiliasi").val();
                const sampai = form.find("#sampai_rekonsiliasi").val();
                const kode_cabang = form.find("#kode_cabang_rekonsiliasi").val();
                const jenis_rekonsiliasi = form.find("#jenis_rekonsiliasi").val();
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
                            form.find("#kode_cabang_rekonsiliasi").focus();
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
                            form.find("#dari_rekonsiliasi").focus();
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
                            form.find("#sampai_rekonsiliasi").focus();
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
                            form.find("#sampai_rekonsiliasi").focus();
                        },
                    });
                    return false;
                } else if (jenis_rekonsiliasi == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Jenis Rekonsiliasi Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#jenis_rekonsiliasi").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
