<form action="{{ route('suratjalancbg.store') }}" method="POST" id="formSuratjalan" autocomplete="off" aria-autocomplete="none" class="flex flex-col h-full">
    @csrf
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-[#003d9e] to-blue-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-plus-circle text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">Tambah Surat Jalan Gudang Cabang</h3>
                <p class="text-blue-200 text-xs text-left">Form input data mutasi surat jalan produk bulanan</p>
            </div>
        </div>
        <button type="button" class="btn-close-modal w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" onclick="window.closeTailwindModal()">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <!-- Info Cards / Form Inputs Group -->
    <div class="p-6 pb-2 text-left">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 relative z-20">
            <!-- Bulan -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Bulan <span class="text-red-500">*</span></label>
                <select name="bulan" id="bulan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none font-medium text-slate-700">
                    <option value="">Pilih Bulan</option>
                    @foreach (config('global.list_bulan') as $b)
                        <option value="{{ $b['kode_bulan'] }}" {{ date('n') == $b['kode_bulan'] ? 'selected' : '' }}>{{ $b['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Tahun <span class="text-red-500">*</span></label>
                <select name="tahun" id="tahun" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none font-medium text-slate-700">
                    <option value="">Pilih Tahun</option>
                    @for ($t = config('global.start_year'); $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
            </div>

            <!-- Cabang (Conditional) -->
            @hasanyrole($roles_show_cabang)
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Pilih Cabang <span class="text-red-500">*</span></label>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none cursor-pointer font-medium text-slate-700">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{ auth()->user()->kode_cabang }}">
            @endrole

            <!-- Keterangan -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 transition-all font-medium" placeholder="Keterangan tambahan (opsional)">
            </div>
        </div>

        <!-- Detail Table -->
        <div class="border border-slate-200 rounded-lg overflow-hidden relative z-10 shadow-sm mb-4">
            <div class="overflow-y-auto max-h-[480px] min-h-[300px] custom-scrollbar overflow-x-auto relative bg-white">
                <!-- Loading Indicator Overlay -->
                <div id="table-loading-overlay" class="absolute inset-0 bg-white/80 z-50 flex items-center justify-center hidden">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-circle-notch fa-spin text-[#003d9e] text-2xl"></i>
                        <span class="text-xs font-semibold text-slate-600">Memuat data...</span>
                    </div>
                </div>

                <table class="w-full text-left border-collapse min-w-[1500px]" id="suratjalan-table">
                    <thead class="sticky top-0 z-20" id="table-head">
                        <!-- Dynamic header will be inserted here via JS -->
                    </thead>
                    <tbody id="table-body-products" class="divide-y divide-slate-100">
                        <!-- Dynamic rows will be inserted here via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2 mt-auto">
        <button type="button" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 rounded-lg text-sm font-bold transition-colors flex items-center gap-2" onclick="window.closeTailwindModal()">
             Batal
        </button>
        <button type="submit" class="bg-[#003d9e] hover:bg-blue-800 text-white px-6 py-2 rounded-lg text-sm font-bold transition-colors shadow-md shadow-blue-200 flex items-center gap-2" id="btnSubmit">
            <i class="fas fa-paper-plane text-[10px]"></i>
            <span>Simpan Data</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formSuratjalan");
        const produkList = @json($produk);

        function getNumber(val) {
            if (!val) return 0;
            return parseFloat(val.replace(/\./g, '')) || 0;
        }

        function updateProductTotal(productCode) {
            let total = 0;
            $(`.key-input-${productCode}`).each(function() {
                const val = getNumber($(this).val() || '0');
                total += val;
            });
            const formattedTotal = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(`#total_${productCode}`).text(formattedTotal);
        }

        function generateRows() {
            const bulan = $('#bulan').val();
            const tahun = $('#tahun').val();
            const kode_cabang = $('#kode_cabang').val();
            if (!bulan || !tahun || !kode_cabang) {
                $('#table-head').html('');
                $('#table-body-products').html('<tr><td class="text-center py-4 text-slate-400">Silakan pilih bulan, tahun dan cabang</td></tr>');
                return;
            }
            
            // Show loading overlay
            $('#table-loading-overlay').removeClass('hidden');

            // Get number of days in selected month & year
            const daysInMonth = new Date(tahun, bulan, 0).getDate();
            
            // 1. Generate Table Head
            let headHtml = `<tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                <th class="px-3 py-3 w-16 bg-slate-50 text-center sticky left-0 z-30 shadow-[2px_0_5px_rgba(0,0,0,0.05)] border-r border-slate-200">Kode</th>
                <th class="px-3 py-3 w-48 bg-slate-50 sticky left-16 z-30 shadow-[2px_0_5px_rgba(0,0,0,0.05)] border-r border-slate-200">Produk</th>`;
            
            for (let day = 1; day <= daysInMonth; day++) {
                headHtml += `<th class="px-2 py-3 text-center border-l border-slate-200 bg-slate-50 w-12">${day}</th>`;
            }
            headHtml += `<th class="px-3 py-3 bg-slate-50 text-center sticky right-0 z-30 shadow-[-2px_0_5px_rgba(0,0,0,0.05)] border-l border-slate-200" style="width: 70px; min-width: 70px; max-width: 70px;">Total</th>`;
            headHtml += `</tr>`;
            $('#table-head').html(headHtml);
            
            // 2. Generate Table Body
            let bodyHtml = '';
            produkList.forEach(function(p) {
                bodyHtml += `<tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-3 py-2 text-xs font-semibold text-slate-700 bg-slate-50 border-r border-slate-200 sticky left-0 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.05)]">${p.kode_produk}</td>
                    <td class="px-3 py-2 text-xs font-bold text-slate-700 bg-slate-50 border-r border-slate-200 sticky left-16 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.05)] max-w-[190px] truncate" title="${p.nama_produk}">${p.nama_produk}</td>`;
                
                for (let day = 1; day <= daysInMonth; day++) {
                    bodyHtml += `<td class="p-1 border-l border-slate-100 w-12">
                        <input type="text" class="w-full text-right px-1 py-1 text-xs font-bold bg-transparent border border-slate-200 focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e]/50 focus:bg-white rounded transition-colors money jml_dus key-input-${p.kode_produk}" name="jml_dus[${day}][${p.kode_produk}]" id="qty_${day}_${p.kode_produk}" data-product="${p.kode_produk}" placeholder="0">
                    </td>`;
                }
                bodyHtml += `<td class="px-3 py-2 text-xs font-bold text-right text-slate-700 bg-slate-50 border-l border-slate-200 sticky right-0 z-10 shadow-[-2px_0_5px_rgba(0,0,0,0.05)]" id="total_${p.kode_produk}" style="width: 70px; min-width: 70px; max-width: 70px;">0</td>`;
                bodyHtml += `</tr>`;
            });
            
            $('#table-body-products').html(bodyHtml);

            // 3. Fetch Existing Data
            $.ajax({
                url: "{{ route('suratjalancbg.getExistingData') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                    kode_cabang: kode_cabang
                },
                dataType: "json",
                success: function(response) {
                    // Populate existing values
                    Object.keys(response).forEach(function(day) {
                        const products = response[day];
                        Object.keys(products).forEach(function(kode_produk) {
                            const val = parseFloat(products[kode_produk]);
                            if (val > 0) {
                                $(`#qty_${day}_${kode_produk}`).val(val);
                            }
                        });
                    });

                    // Update all product totals
                    produkList.forEach(function(p) {
                        updateProductTotal(p.kode_produk);
                    });
                },
                error: function(err) {
                    console.error("Failed to load existing data", err);
                },
                complete: function() {
                    // Hide loading overlay
                    $('#table-loading-overlay').addClass('hidden');

                    // Initialize money mask
                    $(".money").maskMoney({
                        thousands: '.',
                        decimal: ',',
                        precision: 0,
                        allowZero: true
                    });
                }
            });
        }

        // Listener for input changes to update totals dynamically
        $(document).on('keyup change', '.jml_dus', function() {
            const productCode = $(this).attr('data-product');
            updateProductTotal(productCode);
        });

        // Trigger row generation on load and on change
        $('#bulan, #tahun, #kode_cabang').on('change', generateRows);
        generateRows();

        form.on('submit', function(e) {
            const bulan = $(this).find("#bulan").val();
            const tahun = $(this).find("#tahun").val();
            const kode_cabang = $(this).find("#kode_cabang").val();

            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#tahun").focus();
                    },
                });
                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#kode_cabang").focus();
                    },
                });
                return false;
            } else {
                $(this).find("#btnSubmit").prop('disabled', true).addClass('opacity-50 cursor-not-allowed').html('<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...');
                return true;
            }
        });
    });
</script>
