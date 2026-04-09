<form action="{{ route('laporanmarketing.cetakkasbesar') }}" method="POST" target="_blank" id="formKasbesar" class="space-y-3">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        @hasanyrole($roles_show_cabang)
        <!-- Cabang -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-building text-lg"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_kasbesar" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangkasbesar">
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
                    <select name="kode_salesman" id="kode_salesman_kasbesar" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodesalesmankasbesar">
                    </select>
                @endhasanyrole
            </div>
        </div>

        <!-- Pelanggan -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-id text-lg"></i>
                </div>
                <select name="kode_pelanggan" id="kode_pelanggan_kasbesar" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodepelanggankasbesar">
                </select>
            </div>
        </div>

        <!-- Jenis Bayar -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-credit-card text-lg"></i>
                </div>
                <select name="jenis_bayar" id="jenis_bayar" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="">Semua Jenis Pembayaran</option>
                    <option value="TN">TUNAI</option>
                    <option value="TP">TITIPAN</option>
                    <option value="TR">TRANSFER</option>
                    <option value="GR">GIRO</option>
                </select>
            </div>
        </div>

        <!-- Format Laporan -->
        <div class="md:col-span-12" id="formatlaporanoptionkasbesar">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-settings text-lg"></i>
                </div>
                <select name="formatlaporan" id="formatlaporan_kasbesar" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="">Format Laporan</option>
                    <option value="1">Detail</option>
                    <option value="2">Rekap</option>
                    <option value="3">LHP</option>
                </select>
            </div>
        </div>

        <!-- Periode -->
        <div class="md:col-span-12 grid grid-cols-2 gap-4">
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
                id="submitButtonKasbesar">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
            <button type="submit" name="exportButton" 
                class="w-16 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95" 
                id="exportButtonKasbesar" title="Export to Excel">
                <i class="fas fa-file-excel text-xl"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formKasbesar = $("#formKasbesar");
            const select2Kodecabangkasbesar = $(".select2Kodecabangkasbesar");
            if (select2Kodecabangkasbesar.length) {
                select2Kodecabangkasbesar.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmankasbesar = $(".select2Kodesalesmankasbesar");
            if (select2Kodesalesmankasbesar.length) {
                select2Kodesalesmankasbesar.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelanggankasbesar = $(".select2Kodepelanggankasbesar");
            if (select2Kodepelanggankasbesar.length) {
                select2Kodepelanggankasbesar.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabangKasbesar() {
                var kode_cabang = formKasbesar.find("#kode_cabang_kasbesar").val();
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
                        formKasbesar.find("#kode_salesman_kasbesar").html(respond);
                    }
                });
            }

            function getpelangganbySalesmanKasbesar() {
                var kode_salesman = formKasbesar.find("#kode_salesman_kasbesar").val();
                var kode_cabang = formKasbesar.find("#kode_cabang_kasbesar").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/pelanggan/getpelangganbysalesman',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_salesman: kode_salesman,
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formKasbesar.find("#kode_pelanggan_kasbesar").html(respond);
                    }
                });
            }

            getsalesmanbyCabangKasbesar();
            getpelangganbySalesmanKasbesar();
            formKasbesar.find("#kode_cabang_kasbesar").change(function(e) {
                getsalesmanbyCabangKasbesar();
                showformatlaporanKasbesar();
                getpelangganbySalesmanKasbesar();
            });

            formKasbesar.find("#kode_salesman_kasbesar").change(function(e) {
                getpelangganbySalesmanKasbesar();
            });

            formKasbesar.find("#formatlaporan_kasbesar").change(function(e) {
                showformatlaporanKasbesar();
            })

            function showformatlaporanKasbesar() {
                const kode_cabang = $("#kode_cabang_kasbesar").val();
                const formatlaporan = formKasbesar.find("#formatlaporan_kasbesar").val();
                if (kode_cabang == "" || formatlaporan == "2") {
                    formKasbesar.find("#kode_salesman_kasbesar").prop("disabled", true);
                    formKasbesar.find("#kode_pelanggan_kasbesar").prop("disabled", true);
                    formKasbesar.find("#jenis_transaksi_kasbesar").prop("disabled", true);
                    $('.select2Kodesalesmankasbesar').val('').trigger("change");
                    $('.select2Kodepelanggankasbesar').val('').trigger("change");
                } else {
                    formKasbesar.find("#kode_salesman_kasbesar").prop("disabled", false);
                    formKasbesar.find("#kode_pelanggan_kasbesar").prop("disabled", false);
                    formKasbesar.find("#jenis_transaksi_kasbesar").prop("disabled", false);
                }
            }

            showformatlaporanKasbesar();

            formKasbesar.submit(function(e) {
                const formatlaporan = formKasbesar.find("#formatlaporan_kasbesar").val();
                const kode_cabang = formKasbesar.find('#kode_cabang_kasbesar').val();
                const dari = formKasbesar.find('#dari').val();
                const sampai = formKasbesar.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Format Laporan Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#formatlaporan_kasbesar").focus();
                        }
                    });
                    return false;
                } else if (dari == "") {
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
                } else if (sampai == "") {
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
                } else if (start.getTime() > end.getTime()) {
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
                }
            })
        });
    </script>
@endpush
