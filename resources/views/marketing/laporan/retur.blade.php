<form action="{{ route('laporanmarketing.cetakretur') }}" method="POST" target="_blank" id="formRetur" class="space-y-3">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-4 gap-y-1">
        @hasanyrole($roles_show_cabang)
        <!-- Cabang -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-building text-lg"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_retur" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodecabangretur">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endrole
        
        <!-- Salesman -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-user text-lg"></i>
                </div>
                @hasanyrole('salesman')
                    <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
                    <input type="text" class="w-full pl-10 pr-3 py-2 bg-gray-100 border border-slate-300 rounded-lg text-sm text-slate-500" value="{{ auth()->user()->name }}" readonly>
                @else
                    <select name="kode_salesman" id="kode_salesman_retur" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodesalesmanretur">
                    </select>
                @endhasanyrole
            </div>
        </div>

        <!-- Pelanggan -->
        <div class="md:col-span-12">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none z-10 transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-id text-lg"></i>
                </div>
                <select name="kode_pelanggan" id="kode_pelanggan_retur" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none select2Kodepelangganretur">
                </select>
            </div>
        </div>

        <!-- Periode -->
        <div class="md:col-span-12 grid grid-cols-2 gap-4">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="dari" id="dari" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Dari Tanggal">
            </div>
    
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none transition-colors duration-200 group-focus-within:text-[#003d9e] text-slate-400">
                    <i class="ti ti-calendar text-lg"></i>
                </div>
                <input type="text" name="sampai" id="sampai" class="w-full pl-10 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors flatpickr-date" placeholder="Sampai Tanggal">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="md:col-span-12 flex items-center gap-3 pt-2">
            <button type="submit" name="submitButton" 
                class="flex-1 h-12 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all duration-200 active:scale-95" 
                id="submitButtonRetur">
                <i class="fas fa-print opacity-70"></i>
                <span class="tracking-wide">Cetak Laporan</span>
            </button>
            <button type="submit" name="exportButton" 
                class="w-16 h-12 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 active:scale-95" 
                id="exportButtonRetur" title="Export to Excel">
                <i class="fas fa-file-excel text-xl"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formRetur = $("#formRetur");
            const select2Kodecabangretur = $(".select2Kodecabangretur");
            if (select2Kodecabangretur.length) {
                select2Kodecabangretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanretur = $(".select2Kodesalesmanretur");
            if (select2Kodesalesmanretur.length) {
                select2Kodesalesmanretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelangganretur = $(".select2Kodepelangganretur");
            if (select2Kodepelangganretur.length) {
                select2Kodepelangganretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabangRetur() {
                var kode_cabang = formRetur.find("#kode_cabang_retur").val();
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
                        formRetur.find("#kode_salesman_retur").html(respond);
                    }
                });
            }

            function getpelangganbySalesmanRetur() {
                var kode_salesman = formRetur.find("#kode_salesman_retur").val();
                var kode_cabang = formRetur.find("#kode_cabang_retur").val();
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
                        formRetur.find("#kode_pelanggan_retur").html(respond);
                    }
                });
            }

            getsalesmanbyCabangRetur();
            getpelangganbySalesmanRetur();
            formRetur.find("#kode_cabang_retur").change(function(e) {
                getsalesmanbyCabangRetur();
                getpelangganbySalesmanRetur();
            });

            formRetur.find("#kode_salesman_retur").change(function(e) {
                getpelangganbySalesmanRetur();
            });





            formRetur.submit(function(e) {

                const kode_cabang = formRetur.find('#kode_cabang_retur').val();
                const dari = formRetur.find('#dari').val();
                const sampai = formRetur.find('#sampai').val();
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
