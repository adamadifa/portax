@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Buku Besar')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Buat Saldo Awal Buku Besar</h2>
            <p class="text-slate-500 text-sm">Pilih periode dan cabang untuk menginisialisasi atau memuat saldo awal.</p>
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
                <!-- Filter Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- Cabang -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Cabang</label>
                        <select name="kode_cabang" id="kode_cabang" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all select-styled">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $d)
                                <option value="{{ $d->kode_cabang }}">{{ $d->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bulan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Bulan</label>
                        <select name="bulan" id="bulan" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all select-styled">
                            <option value="">Pilih Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Tahun</label>
                        <select name="tahun" id="tahun" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all select-styled">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Get Saldo Button -->
                    <div class="flex items-end">
                        <a href="#" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm font-semibold text-sm" id="getsaldo">
                            <i class="fas fa-sync-alt" id="getsaldo-icon"></i>
                            <span id="getsaldo-text">Get Saldo</span>
                            <span id="getsaldo-loading" class="spinner-border spinner-border-sm d-none w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" role="status" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>

                <!-- Account Balances Input Table -->
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm mb-6">
                    <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold sticky top-0 z-10">
                                    <th class="px-4 py-3">Nama Akun</th>
                                    <th class="px-4 py-3 text-right">Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody id="loaddetailsaldo">
                                <tr id="empty-row">
                                    <td colspan="2" class="px-4 py-8 text-center text-slate-400 italic text-sm">
                                        Pilih filter di atas lalu klik "Get Saldo" untuk menampilkan akun.
                                    </td>
                                </tr>
                                <tr id="loading-row" class="d-none">
                                    <td colspan="2" class="px-4 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fas fa-circle-notch fa-spin text-emerald-600 text-2xl"></i>
                                            <p class="text-xs font-semibold text-slate-500">Memuat saldo...</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-[#003d9e] hover:bg-blue-800 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors shadow-sm shadow-blue-200 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Saldo Awal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('myscript')
<script>
    $(function() {
        // Toggle placeholder styling for select elements
        $('.select-styled').each(function() {
            const $this = $(this);
            const checkVal = () => {
                if ($this.val() === "") {
                    $this.addClass('text-slate-400').removeClass('text-slate-700');
                } else {
                    $this.addClass('text-slate-700').removeClass('text-slate-400');
                }
            };
            $this.on('change', checkVal);
            checkVal();
        });

        // Get Saldo Action
        $(document).on('click', '#getsaldo', function(e) {
            e.preventDefault();
            let bulan = $("#bulan").val();
            let tahun = $("#tahun").val();
            let kode_cabang = $("#kode_cabang").val();

            if (bulan == "" || tahun == "" || kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Cabang, Bulan, dan Tahun harus diisi!',
                    icon: "warning"
                });
                return false;
            }

            // Show Loading State
            $("#getsaldo").addClass('opacity-50 pointer-events-none');
            $("#getsaldo-icon").addClass('d-none');
            $("#getsaldo-text").addClass('d-none');
            $("#getsaldo-loading").removeClass('d-none');
            
            $("#loaddetailsaldo").html(`
                <tr id="loading-row">
                    <td colspan="2" class="px-4 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <i class="fas fa-circle-notch fa-spin text-emerald-600 text-2xl"></i>
                            <p class="text-xs font-semibold text-slate-500">Memuat saldo...</p>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: "POST",
                url: "{{ route('saldoawalbukubesar.getsaldo') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    $("#loaddetailsaldo").html(respond);
                    // Standardize CSS inside the loaded table rows for Tailwind aesthetics
                    $("#loaddetailsaldo tr").addClass('hover:bg-slate-50 transition-colors');
                    $("#loaddetailsaldo td").addClass('px-4 py-2 text-sm text-slate-700 border-b border-slate-100');
                    $("#loaddetailsaldo td:first-child").addClass('font-medium');
                    $("#loaddetailsaldo input[type='text']").addClass('w-44 px-3 py-1 text-right border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm transition-all money');
                    
                    // Reinitialize maskMoney
                    $(".money").maskMoney({
                        prefix: '', 
                        suffix: '', 
                        thousands: '.', 
                        decimal: ',', 
                        precision: 0,
                        allowZero: true,
                        allowNegative: true
                    });
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Terjadi kesalahan saat mengambil saldo!';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {}
                    }

                    Swal.fire({
                        title: "Oops!",
                        text: errorMessage,
                        icon: "error"
                    });
                    
                    $("#loaddetailsaldo").html(`
                        <tr>
                            <td colspan="2" class="px-4 py-8 text-center text-red-500 text-sm">
                                <i class="fas fa-exclamation-triangle mr-2"></i> ${errorMessage}
                            </td>
                        </tr>
                    `);
                },
                complete: function() {
                    $("#getsaldo").removeClass('opacity-50 pointer-events-none');
                    $("#getsaldo-icon").removeClass('d-none');
                    $("#getsaldo-text").removeClass('d-none');
                    $("#getsaldo-loading").addClass('d-none');
                }
            });
        });

        // Submit Form Validation
        $(document).on('submit', '#formCreatesaldoawal', function(e) {
            let bulan = $("#bulan").val();
            let tahun = $("#tahun").val();
            let kode_cabang = $("#kode_cabang").val();
            
            // Check if getsaldo succeeded (checks for empty/loading row)
            let hasData = $("#loaddetailsaldo tr").length > 0 && 
                          !$("#loaddetailsaldo tr#empty-row").length && 
                          !$("#loaddetailsaldo tr#loading-row").length;

            if (bulan == "" || tahun == "" || kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Cabang, Bulan, dan Tahun harus diisi!',
                    icon: "warning"
                });
                return false;
            } else if (!hasData) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Silakan klik "Get Saldo" terlebih dahulu untuk memuat akun!',
                    icon: "warning"
                });
                return false;
            }
        });
    });
</script>
@endpush
