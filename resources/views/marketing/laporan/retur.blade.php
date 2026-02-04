<form action="{{ route('laporanmarketing.cetakretur') }}" method="POST" target="_blank" id="formRetur" class="space-y-3">
    @csrf
    
    <div class="space-y-2">
        @hasanyrole($roles_show_cabang)
        <div class="relative">
             <select name="kode_cabang" id="kode_cabang_retur" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangretur">
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
                <select name="kode_salesman" id="kode_salesman_retur" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodesalesmanretur">
                </select>
            @endhasanyrole
        </div>

        <div class="relative">
            <select name="kode_pelanggan" id="kode_pelanggan_retur" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodepelangganretur">
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="relative">
                 <input type="text" name="dari" id="dari" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
            </div>
    
            <div class="relative">
                 <input type="text" name="sampai" id="sampai" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonRetur" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonRetur">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formRetur = $("#formRetur");
            const select2Kodecabangretur = $(".select2Kodecabangretur");
            if (select2Kodecabangretur.length) {
                select2Kodecabangretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanretur = $(".select2Kodesalesmanretur");
            if (select2Kodesalesmanretur.length) {
                select2Kodesalesmanretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelangganretur = $(".select2Kodepelangganretur");
            if (select2Kodepelangganretur.length) {
                select2Kodepelangganretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabangRetur() {
                var kode_cabang = formRetur.find("#kode_cabang_retur").val();
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
                        formRetur.find("#kode_salesman_retur").html(respond);
                    }
                });
            }

            function getpelangganbySalesmanRetur() {
                var kode_salesman = formRetur.find("#kode_salesman_retur").val();
                var kode_cabang = formRetur.find("#kode_cabang_retur").val();
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
                        formRetur.find("#kode_pelanggan_retur").html(respond);
                    }
                });
            }

            getsalesmanbyCabangRetur();
            getpelangganbySalesmanRetur();
            formRetur.find("#kode_cabang_retur").change(function(e) {
                getsalesmanbyCabangRetur();
                getpelangganbySalesmanRetur();
            });

            formRetur.find("#kode_salesman_retur").change(function(e) {
                getpelangganbySalesmanRetur();
            });





            formRetur.submit(function(e) {

                const kode_cabang = formRetur.find('#kode_cabang_retur').val();
                const dari = formRetur.find('#dari').val();
                const sampai = formRetur.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (dari == "") {
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
