<form action="{{ route('laporanmarketing.cetakrekapkendaraan') }}" method="POST" target="_blank" id="formrekapkendaraan" class="space-y-3">
    @csrf
    <div class="space-y-4">
        @hasanyrole($roles_show_cabang)
        <div class="relative">
            <select name="kode_cabang" id="kode_cabang_rekapkendaraan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangrekapkendaraan">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        @endrole
        <div class="relative">
            <select name="kode_kendaraan" id="kode_kendaraan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodekendaraan">
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
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonrekapkendaraan" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonrekapkendaraan">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formrekapkendaraan = $("#formrekapkendaraan");
            const select2Kodecabangrekapkendaraan = $(".select2Kodecabangrekapkendaraan");
            if (select2Kodecabangrekapkendaraan.length) {
                select2Kodecabangrekapkendaraan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodekendaraan = $(".select2Kodekendaraan");
            if (select2Kodekendaraan.length) {
                select2Kodekendaraan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Kendaraan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            function getkendaraanbyCabang() {
                var kode_cabang = formrekapkendaraan.find("#kode_cabang_rekapkendaraan").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/kendaraan/getkendaraandpbbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formrekapkendaraan.find("#kode_kendaraan").html(respond);
                    }
                });
            }



            getkendaraanbyCabang();
            formrekapkendaraan.find("#kode_cabang_rekapkendaraan").change(function(e) {
                getkendaraanbyCabang();
            });







            formrekapkendaraan.submit(function(e) {

                const kode_cabang = formrekapkendaraan.find('#kode_cabang_rekapkendaraan').val();
                const dari = formrekapkendaraan.find('#dari').val();
                const sampai = formrekapkendaraan.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);
                const kode_kendaraan = formrekapkendaraan.find('#kode_kendaraan').val();
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Cabang Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_cabang").focus();
                        }
                    });
                    return false;
                } else if (kode_kendaraan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Kendaraan Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_kendaraan").focus();
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
