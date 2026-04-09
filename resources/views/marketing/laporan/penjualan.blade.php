<form action="{{ route('laporanmarketing.cetakpenjualan') }}" method="POST" target="_blank" id="formPenjualan" class="space-y-3">
    @csrf



    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        @hasanyrole($roles_show_cabang)
        <div class="md:col-span-12 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 transition-colors">
                <i class="ti ti-building text-slate-400 group-focus-within:text-[#003d9e]"></i>
            </div>
            <select name="kode_cabang" id="kode_cabang_penjualan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodecabangpenjualan font-medium text-slate-700">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        @endrole

        <div class="md:col-span-6 relative">
             @hasanyrole('salesman')
                <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="ti ti-tie text-slate-400"></i>
                </div>
                <input type="text" class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-500 font-medium" value="{{ auth()->user()->name }}" readonly>
            @else
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="ti ti-tie text-slate-400"></i>
                </div>
                <select name="kode_salesman" id="kode_salesman_penjualan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodesalesman font-medium text-slate-700">
                </select>
            @endhasanyrole
        </div>

        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-user text-slate-400"></i>
            </div>
            <select name="kode_pelanggan" id="kode_pelanggan_penjualan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none select2Kodepelanggan font-medium text-slate-700">
            </select>
        </div>

        <div class="md:col-span-4 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-credit-card text-slate-400"></i>
            </div>
            <select name="jenis_transaksi" id="jenis_transaksi" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Semua Jenis Transaksi</option>
                <option value="T">TUNAI</option>
                <option value="K">KREDIT</option>
            </select>
        </div>

        <div class="md:col-span-4 relative" id="formatlaporanoption">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-layout-grid text-slate-400"></i>
            </div>
            <select name="formatlaporan" id="formatlaporan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Format Laporan</option>
                <option value="1">Standar</option>
                <option value="2">Format Satu Baris</option>
                <option value="3">Transaksi PO</option>
                <option value="5">Perhitungan Komisi</option>
            </select>
        </div>

        <div class="md:col-span-4 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="ti ti-settings text-slate-400"></i>
            </div>
            <select name="status_penjualan" id="status_penjualan" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all appearance-none form-select font-medium text-slate-700">
                <option value="">Status Penjualan</option>
                <option value="1">Batal</option>
                <option value="2" selected>Tanpa Status Batal</option>
            </select>
        </div>

        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
             <input type="text" name="dari" id="dari" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date" placeholder="Dari Tanggal">
        </div>

        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="ti ti-calendar text-slate-400"></i>
            </div>
             <input type="text" name="sampai" id="sampai" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700 flatpickr-date" placeholder="Sampai Tanggal">
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
        $(document).ready(function() {
            const formPenjualan = $("#formPenjualan");
            const select2Kodecabangpenjualan = $(".select2Kodecabangpenjualan");
            if (select2Kodecabangpenjualan.length) {
                select2Kodecabangpenjualan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesman = $(".select2Kodesalesman");
            if (select2Kodesalesman.length) {
                select2Kodesalesman.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelanggan = $(".select2Kodepelanggan");
            if (select2Kodepelanggan.length) {
                select2Kodepelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabang() {
                var kode_cabang = formPenjualan.find("#kode_cabang_penjualan").val();
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
                        formPenjualan.find("#kode_salesman_penjualan").html(respond);
                    }
                });
            }

            function getpelangganbySalesman() {
                var kode_salesman = formPenjualan.find("#kode_salesman_penjualan").val();
                var kode_cabang = formPenjualan.find("#kode_cabang_penjualan").val();
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
                        formPenjualan.find("#kode_pelanggan_penjualan").html(respond);
                    }
                });
            }

            getsalesmanbyCabang();
            getpelangganbySalesman();
            formPenjualan.find("#kode_cabang_penjualan").change(function(e) {
                getsalesmanbyCabang();
                showformatlaporan();
                getpelangganbySalesman();
            });

            formPenjualan.find("#kode_salesman_penjualan").change(function(e) {
                getpelangganbySalesman();
            });

            function showformatlaporan() {
                const kode_cabang = $("#kode_cabang_penjualan").val();
                if (kode_cabang == "") {
                    formPenjualan.find("#formatlaporanoption").hide();
                    formPenjualan.find("#kode_salesman_penjualan").prop("disabled", true);
                    formPenjualan.find("#kode_pelanggan_penjualan").prop("disabled", true);
                    formPenjualan.find("#jenis_transaksi").prop("disabled", true);
                    $('.select2Kodesalesman').val('').trigger("change");
                    $('.select2Kodepelanggan').val('').trigger("change");
                } else {
                    formPenjualan.find("#formatlaporanoption").show();
                    formPenjualan.find("#kode_salesman_penjualan").prop("disabled", false);
                    formPenjualan.find("#kode_pelanggan_penjualan").prop("disabled", false);
                    formPenjualan.find("#jenis_transaksi").prop("disabled", false);
                }
            }

            showformatlaporan();

            formPenjualan.submit(function(e) {
                const formatlaporan = formPenjualan.find("#formatlaporan").val();
                const kode_cabang = formPenjualan.find('#kode_cabang_penjualan').val();
                const dari = formPenjualan.find('#dari').val();
                const sampai = formPenjualan.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (kode_cabang != "" && formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Jenis Laporan Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#formatlaporan").focus();
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
