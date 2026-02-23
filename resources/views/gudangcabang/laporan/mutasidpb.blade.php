<form method="POST" action="{{ route('laporangudangcabang.cetakmutasidpb') }}" id="frmMutasidpb" target="_blank" class="space-y-3">
    @csrf
    <div class="space-y-2">
        @hasanyrole($roles_show_cabang)
            <div class="relative">
                <select name="kode_cabang_mutasidpb" id="kode_cabang_mutasidpb" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangmutasidpb">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        @endrole

        <div class="relative text-left">
            <select name="kode_produk_mutasidpb" id="kode_produk_mutasidpb" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodeprodukmutasidpb">
                <option value="">Pilih Produk</option>
                @foreach ($produk as $d)
                    <option value="{{ $d->kode_produk }}">{{ $d->kode_produk }} - {{ textUpperCase($d->nama_produk) }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="relative">
                <input type="text" name="dari" id="dari_mutasidpb" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari">
            </div>
            <div class="relative">
                <input type="text" name="sampai" id="sampai_mutasidpb" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai">
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton" style="background-color: #003d9e; border-color: #003d9e;">
                <i class="ti ti-printer me-1 text-sm"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
                <i class="ti ti-download text-sm"></i>
            </button>
        </div>
    </div>
</form>

@push('myscript')
    <script>
        $(function() {
            const form = $("#frmMutasidpb");
            const select2Kodecabangmutasidpb = form.find('.select2Kodecabangmutasidpb');
            if (select2Kodecabangmutasidpb.length) {
                select2Kodecabangmutasidpb.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodeprodukmutasidpb = form.find('.select2Kodeprodukmutasidpb');
            if (select2Kodeprodukmutasidpb.length) {
                select2Kodeprodukmutasidpb.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Produk',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            form.submit(function() {
                const kode_produk = form.find("#kode_produk_mutasidpb").val();
                const dari = form.find("#dari_mutasidpb").val();
                const sampai = form.find("#sampai_mutasidpb").val();
                const kode_cabang = form.find("#kode_cabang_mutasidpb").val();
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
                            form.find("#kode_cabang_mutasidpb").focus();
                        },
                    });
                    return false;
                }
                @endrole

                if (kode_produk == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Kode Produk Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#kode_produk_mutasidpb").focus();
                        },
                    });
                    return false;
                } else if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#dari_mutasidpb").focus();
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
                            form.find("#sampai_mutasidpb").focus();
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
                            form.find("#sampai_mutasidpb").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
