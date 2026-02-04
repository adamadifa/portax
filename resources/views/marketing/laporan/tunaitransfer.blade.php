<form action="{{ route('laporanmarketing.cetaktunaitransfer') }}" method="POST" target="_blank" id="formtunaitransfer" class="space-y-3">
    @csrf
    <div class="space-y-4">
        @hasanyrole($roles_show_cabang)
        <div class="relative">
            <select name="kode_cabang" id="kode_cabang_tunaitransfer" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangtunaitransfer">
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
                <select name="kode_salesman" id="kode_salesman_tunaitransfer" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodesalemanntunaitransfer">
                </select>
            @endhasanyrole
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
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtontunaitransfer" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtontunaitransfer">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formtunaitransfer = $("#formtunaitransfer");
            const select2Kodecabangtunaitransfer = $(".select2Kodecabangtunaitransfer");
            if (select2Kodecabangtunaitransfer.length) {
                select2Kodecabangtunaitransfer.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalemanntunaitransfer = $(".select2Kodesalemanntunaitransfer");
            if (select2Kodesalemanntunaitransfer.length) {
                select2Kodesalemanntunaitransfer.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            function getsalesmanbyCabangtunaitransfer() {
                var kode_cabang = formtunaitransfer.find("#kode_cabang_tunaitransfer").val();
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
                        formtunaitransfer.find("#kode_salesman_tunaitransfer").html(respond);
                    }
                });
            }



            getsalesmanbyCabangtunaitransfer();
            formtunaitransfer.find("#kode_cabang_tunaitransfer").change(function(e) {
                getsalesmanbyCabangtunaitransfer();
            });







            formtunaitransfer.submit(function(e) {

                const kode_cabang = formtunaitransfer.find('#kode_cabang_tunaitransfer').val();
                const dari = formtunaitransfer.find('#dari').val();
                const sampai = formtunaitransfer.find('#sampai').val();
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
