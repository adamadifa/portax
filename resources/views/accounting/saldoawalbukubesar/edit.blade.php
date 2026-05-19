@extends('layouts.app')
@section('titlepage', 'Edit Saldo Awal Buku Besar')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Edit Saldo Awal Buku Besar</h2>
            <p class="text-slate-500 text-sm">Sesuaikan detail saldo awal buku besar untuk periode terpilih.</p>
        </div>
        <a href="{{ route('saldoawalbukubesar.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors font-medium text-sm">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl">
        <div class="p-6">
            <form action="{{ route('saldoawalbukubesar.store') }}" method="POST" id="formCreatesaldoawal" autocomplete="off">
                @csrf
                
                <!-- Info Section -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100 mb-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Kode Saldo Awal</label>
                        <span class="font-mono text-sm font-semibold text-slate-700">{{ $saldoawalbukubesar->kode_saldo_awal }}</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Cabang</label>
                        <span class="text-sm font-semibold text-slate-700">{{ $saldoawalbukubesar->nama_cabang }}</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Bulan</label>
                        <span class="text-sm font-semibold text-slate-700">{{ $nama_bulan[$saldoawalbukubesar->bulan] }}</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Tahun</label>
                        <span class="text-sm font-semibold text-slate-700">{{ $saldoawalbukubesar->tahun }}</span>
                    </div>
                </div>

                <hr class="border-slate-150 mb-6">

                <!-- Add Account Form Row -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-6 items-end">
                    <div class="md:col-span-6">
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Pilih Akun</label>
                        <select name="kode_akun_select" id="kode_akun" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all select-styled select2Kodeakun">
                            <option value="">Pilih Akun</option>
                            @foreach ($coa as $d)
                                <option value="{{ $d['kode_akun'] }}">{{ $d['kode_akun'] }} - {{ $d['nama_akun'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Jumlah</label>
                        <input type="text" name="jumlah_input" id="jumlah" class="w-full px-3 py-2 text-right border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all" placeholder="0">
                    </div>
                    <div class="md:col-span-2">
                        <button type="button" class="w-full bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors font-semibold text-sm h-[38px]" id="addsaldoawal">
                            <i class="fas fa-plus"></i>
                            <span>Tambah</span>
                        </button>
                    </div>
                </div>

                <!-- Account Balances Edit Table -->
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm mb-6">
                    <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold sticky top-0 z-10">
                                    <th class="px-4 py-3">Nama Akun</th>
                                    <th class="px-4 py-3 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="loaddetailsaldo" class="divide-y divide-slate-100">
                                @foreach ($detailsaldoawalbukubesar as $d)
                                    <tr id="idx-{{ $d->kode_akun }}" class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-2.5 text-sm text-slate-700 font-medium">
                                            <input type="hidden" name="kode_akun[]" value="{{ $d->kode_akun }}"/>
                                            {{ $d->kode_akun }} - {{ $d->nama_akun }}
                                        </td>
                                        <td class="px-4 py-2.5 text-right">
                                            <input type="hidden" name="jumlah[]" value="{{ $d->jumlah }}"/>
                                            <span class="font-mono text-sm font-semibold text-slate-800">{{ formatAngka($d->jumlah) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-[#003d9e] hover:bg-blue-800 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors shadow-sm shadow-blue-200 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('myscript')
<script>
    $(function() {
        $("#jumlah").maskMoney({
            prefix: '', 
            suffix: '', 
            thousands: '.', 
            decimal: ',', 
            precision: 0,
            allowZero: true,
            allowNegative: true
        });

        // Initialize Select2 if needed
        const select2Kodeakun = $(".select2Kodeakun");
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        // Add Account Row dynamically
        $("#addsaldoawal").click(function(e) {
            e.preventDefault();
            const kode_akun = $("#kode_akun").val();
            const nama_akun = $("#kode_akun").select2('data')[0].text;
            const jumlah = $("#jumlah").val();
            
            if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Kode Akun harus diisi!',
                    icon: "warning",
                    didClose: () => {
                        $("#kode_akun").focus();
                    }
                });
                return false;
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jumlah harus diisi!',
                    icon: "warning",
                    didClose: () => {
                        $("#jumlah").focus();
                    }
                });
                return false;
            } else {
                let checkKodeAkun = $("#loaddetailsaldo tr").filter(function() {
                    return $(this).attr("id") == `idx-${kode_akun}`;
                }).length;
                
                if (checkKodeAkun > 0) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Kode Akun sudah ditambahkan!',
                        icon: "warning"
                    });
                } else {
                    $("#loaddetailsaldo").append(`
                        <tr id="idx-${kode_akun}" class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-2.5 text-sm text-slate-700 font-medium">
                                <input type="hidden" name="kode_akun[]" value="${kode_akun}"/>
                                ${nama_akun}
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <input type="hidden" name="jumlah[]" value="${jumlah}"/>
                                <span class="font-mono text-sm font-semibold text-slate-800">${jumlah}</span>
                            </td>
                        </tr>
                    `);
                }
            }
        });

        // Submit Form Validation
        $(document).on('submit', '#formCreatesaldoawal', function(e) {
            let jmldata = $("#loaddetailsaldo tr").length;
            if (jmldata == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Data saldo awal masih kosong!',
                    icon: "warning"
                });
                return false;
            }
        });
    });
</script>
@endpush
