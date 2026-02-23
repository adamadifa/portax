@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Gudang Cabang')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Buat Saldo Awal</h2>
            <p class="text-slate-500 text-sm">
                <a href="{{ route('sagudangcabang.index') }}" class="text-[#003d9e] hover:underline">Saldo Awal Gudang Cabang</a>
                <span class="mx-1">/</span>
                <span>Buat Saldo Awal</span>
            </p>
        </div>
        <a href="{{ route('sagudangcabang.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors border border-slate-200">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-3">
                    <form action="{{ route('sagudangcabang.store') }}" method="POST" id="formCreatesaldoawal" aria-autocomplete="off">
                        @csrf

                        <!-- Filter Fields -->
                        <div class="space-y-4 mb-4">
                            @hasanyrole($roles_show_cabang)
                            <div class="relative">
                                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang <span class="text-red-500">*</span></label>
                                <select name="kode_cabang" id="kode_cabang"
                                    class="select2Kodecabang w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endrole

                            <div class="relative">
                                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Bulan <span class="text-red-500">*</span></label>
                                <select name="bulan" id="bulan"
                                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="relative">
                                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Tahun <span class="text-red-500">*</span></label>
                                <select name="tahun" id="tahun"
                                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                                    <option value="">Pilih Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="relative">
                                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Good/Bad Stok <span class="text-red-500">*</span></label>
                                <select name="kondisi" id="kondisi"
                                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                                    <option value="">Pilih Kondisi</option>
                                    <option value="GS">GOOD STOK</option>
                                    <option value="BS">BAD STOK</option>
                                </select>
                            </div>

                            <a href="#" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm font-medium text-sm" id="getsaldo">
                                <i class="fas fa-sync-alt"></i>
                                Get Saldo
                            </a>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto rounded-lg border border-slate-200 mb-5">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                        <th class="px-3 py-2" rowspan="2">Kode</th>
                                        <th class="px-3 py-2" rowspan="2">Nama Barang</th>
                                        <th class="px-3 py-2 text-center border-b border-slate-200" colspan="3">Kuantitas</th>
                                    </tr>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                        <th class="px-3 py-2 text-center">Dus</th>
                                        <th class="px-3 py-2 text-center">Pack</th>
                                        <th class="px-3 py-2 text-center">Pcs</th>
                                    </tr>
                                </thead>
                                <tbody id="loaddetailsaldo" class="divide-y divide-slate-100">
                                </tbody>
                            </table>
                        </div>

                        <!-- Submit Button -->
                        <button class="w-full bg-[#003d9e] hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg font-medium text-sm transition-colors shadow-sm shadow-blue-200 flex items-center justify-center gap-2" type="submit">
                            <i class="fas fa-paper-plane"></i>
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('myscript')
<script>
    $(function() {

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
        //Mendapatkan Data Detail Saldo
        function loaddetailsaldo() {
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            var kondisi = $("#kondisi").val();
            var kode_cabang = $("#kode_cabang").val();
            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Cabang !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Bulan !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Tahun !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#tahun").focus();
                    },
                });
                return false;
            } else if (kondisi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Dulu Good Stok / Bad Stok  !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kondisi").focus();
                    },
                });
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('sagudangcabang.getdetailsaldo') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kondisi: kondisi,
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond == '1') {
                            Swal.fire({
                                title: "Oops!",
                                text: "Saldo Bulan Sebelumnya Belum di input !",
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    $("#bulan").focus();
                                },
                            });
                            $("#loaddetailsaldo").html("");
                        } else {
                            $("#loaddetailsaldo").html(respond);
                        }
                    }
                });
            }
        }

        $("#getsaldo").click(function(e) {
            e.preventDefault();
            loaddetailsaldo();
        });

        $("#formCreatesaldoawal").submit(function(e) {
            const form = $("#formCreatesaldoawal");
            if (form.find('#loaddetailsaldo tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Silakan Get Saldo Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_barang").focus();
                    },
                });

                return false;
            }
        });
    });
</script>
@endpush
