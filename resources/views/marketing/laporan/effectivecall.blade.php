<form action="{{ route('laporanmarketing.cetakeffectivecall') }}" method="POST" target="_blank" id="formeffectivecall" class="space-y-3">
    @csrf
    <div class="space-y-4">
        @hasanyrole($roles_show_cabang)
        <div class="relative">
             <select name="kode_cabang" id="kode_cabang_effectivecall" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangeffectivecall">
                <option value="">Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        @endrole
        
        <div class="relative">
            <select name="formatlaporan" id="formatlaporan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                <option value="">Format Laporan</option>
                <option value="1">Berdasarkan Salesman</option>
                <option value="2">Berdasarkan Produk</option>
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
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonEffectiveCall" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonEffectiveCall">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formeffectivecall = $("#formeffectivecall");
            const select2Kodecabangeffectivecall = $(".select2Kodecabangeffectivecall");
            if (select2Kodecabangeffectivecall.length) {
                select2Kodecabangeffectivecall.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }


            formeffectivecall.submit(function(e) {

                const kode_cabang = formeffectivecall.find('#kode_cabang_effectivecall').val();
                const formatlaporan = formeffectivecall.find('#formatlaporan').val();
                const dari = formeffectivecall.find('#dari').val();
                const sampai = formeffectivecall.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Cabang Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_cabang_effectivecall").focus();
                        }
                    });
                    return false;
                } else if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Format Laporan Harus Diisi !",
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
