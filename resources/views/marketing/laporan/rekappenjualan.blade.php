<form action="{{ route('laporanmarketing.cetakrekappenjualan') }}" method="POST" target="_blank" id="formRekappenjualan" class="space-y-3">
    @csrf



    <div class="space-y-2">
        <div class="relative">
            <select name="jenis_laporan" id="jenis_laporan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                <option value="">Jenis Laporan</option>
                <option value="1">Rekap Penjualan</option>
                <option value="2">Rekap Retur</option>
                <option value="3">Rekap Penjualan Qty</option>
                <option value="4">Rekap Penjualan Produk</option>
                <option value="5">Collect Aup</option>
            </select>
        </div>

        @hasanyrole($roles_show_cabang)
        <div class="relative">
             <select name="kode_cabang" id="kode_cabang_rekappenjualan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangrekappenjualan">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        @endrole
        
        <div class="relative">
            @hasanyrole('salesman')
                <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
                <input type="text" class="w-full px-3 py-2.5 bg-gray-100 border border-slate-300 rounded-lg text-sm text-slate-500" value="{{ auth()->user()->name }}" readonly>
            @else
                <select name="kode_salesman" id="kode_salesman_rekappenjualan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodesalesmanrekappenjualan">
                </select>
            @endhasanyrole
        </div>
    </div>

    <div class="row" id="tanggalaup" style="display:none;">
        <div class="col-12">
             <div class="relative">
                <input type="text" name="tanggal" id="tanggal" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Tanggal">
             </div>
        </div>
    </div>
    
    <div class="grid grid-cols-2 gap-4" id="tanggalpenjualan">
         <div class="relative">
            <input type="text" name="dari" id="dari" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
         </div>
         <div class="relative">
            <input type="text" name="sampai" id="sampai" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
         </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonRekappenjualan" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonRekappenjualan">
                <i class="ti ti-download"></i>
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
