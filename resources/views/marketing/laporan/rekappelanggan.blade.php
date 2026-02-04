<form action="{{ route('laporanmarketing.cetakrekappelanggan') }}" method="POST" target="_blank" id="formrekappelanggan" class="space-y-3">
    @csrf
    <div class="space-y-4">
        @hasanyrole($roles_show_cabang)
        <div class="relative">
            <select name="kode_cabang" id="kode_cabang_rekappelanggan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangrekappelanggan">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        @endrole
        <div class="relative">
            <select name="kode_salesman" id="kode_salesman_rekappelanggan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodesalesmanrekappelanggan">
            </select>
        </div>
        <div class="relative">
            <select name="kode_pelanggan" id="kode_pelanggan_rekappelanggan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodepelangganrekappelanggan">
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

    <div class="row mt-3">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonrekappelanggan" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonrekappelanggan">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formrekappelanggan = $("#formrekappelanggan");
            const select2Kodecabangrekappelanggan = $(".select2Kodecabangrekappelanggan");
            if (select2Kodecabangrekappelanggan.length) {
                select2Kodecabangrekappelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanrekappelanggan = $(".select2Kodesalesmanrekappelanggan");
            if (select2Kodesalesmanrekappelanggan.length) {
                select2Kodesalesmanrekappelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelangganrekappelanggan = $(".select2Kodepelangganrekappelanggan");
            if (select2Kodepelangganrekappelanggan.length) {
                select2Kodepelangganrekappelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmandbyCabangrekappelanggan() {
                var kode_cabang = formrekappelanggan.find("#kode_cabang_rekappelanggan").val();
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
                        formrekappelanggan.find("#kode_salesman_rekappelanggan").html(respond);
                    }
                });
            }

            function getpelangganbySalesmanrekappelanggan() {
                var kode_salesman = formrekappelanggan.find("#kode_salesman_rekappelanggan").val();
                var kode_cabang = formrekappelanggan.find("#kode_cabang_rekappelanggan").val();
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
                        formrekappelanggan.find("#kode_pelanggan_rekappelanggan").html(respond);
                    }
                });
            }
            getpelangganbySalesmanrekappelanggan();
            getsalesmandbyCabangrekappelanggan();
            formrekappelanggan.find("#kode_cabang_rekappelanggan").change(function(e) {
                getsalesmandbyCabangrekappelanggan();
                getpelangganbySalesmanrekappelanggan();
            });

            formrekappelanggan.find("#kode_salesman_rekappelanggan").change(function(e) {
                getpelangganbySalesmanrekappelanggan();
            });





            formrekappelanggan.submit(function(e) {

                const kode_cabang = formrekappelanggan.find('#kode_cabang_rekappelanggan').val();
                const dari = formrekappelanggan.find('#dari').val();
                const sampai = formrekappelanggan.find('#sampai').val();
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
