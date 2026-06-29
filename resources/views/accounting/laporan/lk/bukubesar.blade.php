<form action="{{ route('laporanaccounting.cetakbukubesar') }}" id="formLedger" target="_blank" method="POST" class="space-y-2">
    @csrf
    <div class="grid grid-cols-1 gap-y-2">
        <!-- Format Laporan -->
        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-settings text-lg"></i>
                </div>
                <select name="formatlaporan" id="formatlaporan_ledger" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="">Format Laporan</option>
                    <option value="1">Buku Besar</option>
                    <option value="2">Neraca</option>
                    <option value="3">Laba Rugi</option>
                    <option value="4">Neraca Tahunan (Horizontal)</option>
                    <option value="5">Laba Rugi Tahunan (Horizontal)</option>
                </select>
            </div>
        </div>

        @hasanyrole($roles_show_cabang)
        <!-- Cabang -->
        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-building text-lg"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_ledger" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select select2KodecabangLedger">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endrole

        <!-- COA Dari & Sampai -->
        <div id="coa_ledger" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                        <i class="ti ti-hash text-lg"></i>
                    </div>
                    <select name="kode_akun_dari" id="kode_akun_dari_ledger" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select select2Kodeakundari">
                        <option value="">Semua Akun</option>
                        @foreach ($coa as $d)
                            <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                        <i class="ti ti-hash text-lg"></i>
                    </div>
                    <select name="kode_akun_sampai" id="kode_akun_sampai_ledger" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select select2Kodeakunsampai">
                        <option value="">Semua Akun</option>
                        @foreach ($coa as $d)
                            <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Periode -->
        <div id="periode_ledger_container">
            <div class="grid grid-cols-2 gap-4">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                        <i class="ti ti-calendar text-lg"></i>
                    </div>
                    <input type="text" name="dari" id="dari_ledger" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
                </div>
        
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                        <i class="ti ti-calendar text-lg"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_ledger" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
                </div>
            </div>
        </div>

        <!-- Tahun (Only for Annual/Horizontal reports) -->
        <div id="tahun_ledger_container" style="display: none;">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <select name="tahun" id="tahun_ledger" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none form-select">
                    <option value="">Pilih Tahun</option>
                    @for ($t = date('Y'); $t >= $start_year; $t--)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" name="submitButton" 
                class="flex-1 h-12 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all duration-200 active:scale-95" 
                id="submitButtonLedger">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
            <button type="submit" name="exportButton" 
                class="w-16 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95" 
                id="exportButtonLedger" title="Export to Excel">
                <i class="fas fa-file-excel text-xl"></i>
            </button>
        </div>
    </div>
</form>

@push('myscript')
    <script>
        $(function() {
            const formLedger = $("#formLedger");

            // Fungsi untuk menampilkan/menyembunyikan COA sesuai format laporan
            function showCoa() {
                const formatlaporan = formLedger.find("#formatlaporan_ledger").val();
                if (formatlaporan == '1') {
                    $("#coa_ledger").show();
                } else {
                    $("#coa_ledger").hide();
                    // Reset value COA ke kosong
                    formLedger.find("#kode_akun_dari_ledger").val("").trigger('change');
                    formLedger.find("#kode_akun_sampai_ledger").val("").trigger('change');
                }

                // Toggle between Periode (Tanggal) and Tahun
                if (formatlaporan == '4' || formatlaporan == '5') {
                    $("#periode_ledger_container").hide();
                    $("#tahun_ledger_container").show();
                } else {
                    $("#periode_ledger_container").show();
                    $("#tahun_ledger_container").hide();
                }
            }

            // Inisialisasi select2
            const select2KodecabangLedger = $(".select2KodecabangLedger");
            if (select2KodecabangLedger.length) {
                select2KodecabangLedger.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodeakundari = $(".select2Kodeakundari");
            if (select2Kodeakundari.length) {
                select2Kodeakundari.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Akun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodeakunsampai = $(".select2Kodeakunsampai");
            if (select2Kodeakunsampai.length) {
                select2Kodeakunsampai.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Akun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            // Jalankan showCoa saat halaman pertama kali dimuat
            showCoa();

            // Event ketika formatlaporan berubah
            formLedger.find("#formatlaporan_ledger").change(function() {
                showCoa();
            });

            formLedger.submit(function(e) {
                const formatlaporan = formLedger.find("#formatlaporan_ledger").val();
                const dari = formLedger.find("#dari_ledger").val();
                const sampai = formLedger.find("#sampai_ledger").val();
                const tahun = formLedger.find("#tahun_ledger").val();

                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Jenis Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formLedger.find("#formatlaporan_ledger").focus();
                        },
                    });
                    return false;
                }

                // Validation for Annual/Horizontal Laporan
                if (formatlaporan == '4' || formatlaporan == '5') {
                    if (tahun == "") {
                        Swal.fire({
                            title: "Oops!",
                            text: 'Tahun Harus Diisi !',
                            icon: "warning",
                            showConfirmButton: true,
                            didClose: (e) => {
                                formLedger.find("#tahun_ledger").focus();
                            },
                        });
                        return false;
                    }
                } else {
                    // Validation for normal reports
                    const start = new Date(dari);
                    const end = new Date(sampai);
                    if (dari == "") {
                        Swal.fire({
                            title: "Oops!",
                            text: 'Periode Dari Harus Diisi !',
                            icon: "warning",
                            showConfirmButton: true,
                            didClose: (e) => {
                                formLedger.find("#dari_ledger").focus();
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
                                formLedger.find("#sampai_ledger").focus();
                            },
                        });
                        return false;
                    } else if (start > end) {
                        Swal.fire({
                            title: "Oops!",
                            text: 'Periode Tidak Valid !',
                            icon: "warning",
                            showConfirmButton: true,
                            didClose: (e) => {
                                formLedger.find("#sampai_ledger").focus();
                            },
                        });
                        return false;
                    }
                }
            });

        });
    </script>
@endpush
