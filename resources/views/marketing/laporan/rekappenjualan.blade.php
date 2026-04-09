<form action="{{ route('laporanmarketing.cetakrekappenjualan') }}" method="POST" target="_blank" id="formRekappenjualan" class="space-y-3">
    @csrf



    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        <!-- Jenis Laporan -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-settings text-lg"></i>
                </div>
                <select name="jenis_laporan" id="jenis_laporan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="">Jenis Laporan</option>
                    <option value="1">Rekap Penjualan</option>
                    <option value="2">Rekap Retur</option>
                    <option value="3">Rekap Penjualan Qty</option>
                    <option value="4">Rekap Penjualan Produk</option>
                    <option value="5">Collect Aup</option>
                </select>
            </div>
        </div>

        @hasanyrole($roles_show_cabang)
        <!-- Cabang -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-building text-lg"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_rekappenjualan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangrekappenjualan">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endrole
        
        <!-- Salesman -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-user text-lg"></i>
                </div>
                @hasanyrole('salesman')
                    <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
                    <input type="text" class="w-full pl-10 pr-3 py-2 bg-gray-100 border border-slate-300 rounded-lg text-sm text-slate-500" value="{{ auth()->user()->name }}" readonly>
                @else
                    <select name="kode_salesman" id="kode_salesman_rekappenjualan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodesalesmanrekappenjualan">
                    </select>
                @endhasanyrole
            </div>
        </div>

        <!-- Tanggal (Conditional show) -->
        <div class="md:col-span-12" id="tanggalaup" style="display:none;">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Tanggal">
            </div>
        </div>
        
        <!-- Periode (Conditional show) -->
        <div class="md:col-span-12 grid grid-cols-2 gap-4" id="tanggalpenjualan">
             <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="dari" id="dari" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
             </div>
             <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="sampai" id="sampai" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
             </div>
        </div>

        <!-- Action Buttons -->
        <div class="md:col-span-12 flex items-center gap-3 pt-2">
            <button type="submit" name="submitButton" 
                class="flex-1 h-12 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all duration-200 active:scale-95" 
                id="submitButtonRekappenjualan">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
            <button type="submit" name="exportButton" 
                class="w-16 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95" 
                id="exportButtonRekappenjualan" title="Export to Excel">
                <i class="fas fa-file-excel text-xl"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formRekappenjualan = $("#formRekappenjualan");

            function showtanggalaup() {
                const jenis_laporan = formRekappenjualan.find('#jenis_laporan').val();
                if (jenis_laporan == "5") {
                    $("#tanggalaup").show();
                    $("#tanggalpenjualan").hide();
                } else {
                    $("#tanggalaup").hide();
                    $("#tanggalpenjualan").show();
                }
            }

            showtanggalaup();
            formRekappenjualan.find('#jenis_laporan').on('change', function() {
                showtanggalaup();
            });
            const select2Kodecabangrekappenjualan = $(".select2Kodecabangrekappenjualan");
            if (select2Kodecabangrekappenjualan.length) {
                select2Kodecabangrekappenjualan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanrekappenjualan = $(".select2Kodesalesmanrekappenjualan");
            if (select2Kodesalesmanrekappenjualan.length) {
                select2Kodesalesmanrekappenjualan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            function getsalesmanbyCabangRekappenjualan() {
                var kode_cabang = formRekappenjualan.find("#kode_cabang_rekappenjualan").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/salesman/getsalesmanbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formRekappenjualan.find("#kode_salesman_rekappenjualan").html(respond);
                    }
                });
            }



            getsalesmanbyCabangRekappenjualan();
            formRekappenjualan.find("#kode_cabang_rekappenjualan").change(function(e) {
                getsalesmanbyCabangRekappenjualan();
            });







            formRekappenjualan.submit(function(e) {

                const kode_cabang = formRekappenjualan.find('#kode_cabang_rekappenjualan').val();
                const dari = formRekappenjualan.find('#dari').val();
                const sampai = formRekappenjualan.find('#sampai').val();
                const tanggal = formRekappenjualan.find('#tanggal').val();
                const start = new Date(dari);
                const end = new Date(sampai);
                const jenis_laporan = formRekappenjualan.find('#jenis_laporan').val();

                if (jenis_laporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Jenis Laporan Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#jenis_laporan").focus();
                        },

                    });
                    return false;

                } else if (dari == "" && jenis_laporan != "5") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Dari Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#dari").focus();
                        },
                    });
                    return false;
                } else if (sampai == "" && jenis_laporan != "5") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Sampai Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                } else if (start.getTime() > end.getTime() && jenis_laporan != "5") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                } else if (jenis_laporan == 5 && tanggal == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#tanggal").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
