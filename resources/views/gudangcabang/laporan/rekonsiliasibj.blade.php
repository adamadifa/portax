<form method="POST" action="{{ route('laporangudangcabang.cetakrekonsiliasibj') }}" id="frmRekonsiliasibj" target="_blank" class="space-y-3">
    @csrf
    <div class="space-y-2">
        @hasanyrole($roles_show_cabang)
            <div class="relative">
                <select name="kode_cabang_rekonsiliasi" id="kode_cabang_rekonsiliasi" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangrekonsiliasi">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        @endrole

        <div class="relative text-left">
            <select name="kode_salesman" id="kode_salesman_rekonsiliasi" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodesalesmanrekonsiliasi">
                <option value="">Pilih Salesman</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="relative">
                <input type="text" name="dari" id="dari_rekonsiliasi" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari">
            </div>
            <div class="relative">
                <input type="text" name="sampai" id="sampai_rekonsiliasi" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai">
            </div>
        </div>

        <div class="relative">
            <select name="jenis_rekonsiliasi" id="jenis_rekonsiliasi" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                <option value="">Jenis Rekonsiliasi</option>
                <option value="1">Penjualan</option>
                <option value="2">Retur</option>
                <option value="3">Promosi</option>
            </select>
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
            const form = $("#frmRekonsiliasibj");
            const select2Kodecabangrekonsiliasi = form.find('.select2Kodecabangrekonsiliasi');
            if (select2Kodecabangrekonsiliasi.length) {
                select2Kodecabangrekonsiliasi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanrekonsiliasi = form.find(".select2Kodesalesmanrekonsiliasi");
            if (select2Kodesalesmanrekonsiliasi.length) {
                select2Kodesalesmanrekonsiliasi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabang() {
                var kode_cabang = form.find("#kode_cabang_rekonsiliasi").val();
                $.ajax({
                    type: 'POST',
                    url: '/salesman/getsalesmanbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        form.find("#kode_salesman_rekonsiliasi").html(respond);
                    }
                });
            }

            getsalesmanbyCabang();
            form.find("#kode_cabang_rekonsiliasi").change(function(e) {
                getsalesmanbyCabang();
            });

            form.submit(function() {
                const kode_salesman = form.find("#kode_salesman_rekonsiliasi").val();
                const dari = form.find("#dari_rekonsiliasi").val();
                const sampai = form.find("#sampai_rekonsiliasi").val();
                const kode_cabang = form.find("#kode_cabang_rekonsiliasi").val();
                const jenis_rekonsiliasi = form.find("#jenis_rekonsiliasi").val();
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
                            form.find("#kode_cabang_rekonsiliasi").focus();
                        },
                    });
                    return false;
                }
                @endrole

                if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#dari_rekonsiliasi").focus();
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
                            form.find("#sampai_rekonsiliasi").focus();
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
                            form.find("#sampai_rekonsiliasi").focus();
                        },
                    });
                    return false;
                } else if (jenis_rekonsiliasi == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Jenis Rekonsiliasi Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#jenis_rekonsiliasi").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
