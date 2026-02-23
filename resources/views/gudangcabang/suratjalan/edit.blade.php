<form action="{{ route('suratjalancbg.update', Crypt::encrypt($suratjalan->no_mutasi)) }}" method="POST" id="formSuratjalan" autocomplete="off" aria-autocomplete="none" class="flex flex-col h-full">
    @csrf
    @method('PUT')
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-[#003d9e] to-blue-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-edit text-white text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">Edit Surat Jalan Gudang Cabang</h3>
                <p class="text-blue-200 text-xs text-left">Update data mutasi surat jalan produk</p>
            </div>
        </div>
        <button type="button" class="btn-close-modal w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" onclick="window.closeTailwindModal()">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <!-- Info Cards / Form Inputs Group -->
    <div class="p-6 pb-2 text-left">
        <div class="flex flex-col gap-y-4 mb-6 relative z-20">
            <!-- No Surat Jalan -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">No. Surat Jalan <span class="text-red-500">*</span></label>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-barcode text-slate-400 text-xs"></i>
                </div>
                <input type="text" name="no_surat_jalan" id="no_surat_jalan" value="{{ $suratjalan->no_surat_jalan }}" class="w-full pl-10 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 transition-all font-medium uppercase" placeholder="Masukkan Nomor Surat Jalan">
            </div>

            <!-- Tanggal -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Tanggal <span class="text-red-500">*</span></label>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-calendar-alt text-slate-400 text-xs"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" value="{{ $suratjalan->tanggal }}" class="flatpickr-date w-full pl-10 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 transition-all font-medium" placeholder="Pilih Tanggal">
            </div>

            <!-- Cabang (Conditional) -->
            @hasanyrole($roles_show_cabang)
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Pilih Cabang <span class="text-red-500">*</span></label>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="fas fa-building text-slate-400 text-xs"></i>
                </div>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang" class="w-full pl-10 pr-8 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none cursor-pointer font-medium text-slate-700">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ $suratjalan->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>{{ strtoupper($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
            @endrole

            <!-- Keterangan -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Keterangan</label>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-file-alt text-slate-400 text-xs"></i>
                </div>
                <input type="text" name="keterangan" id="keterangan" value="{{ $suratjalan->keterangan }}" class="w-full pl-10 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 transition-all font-medium" placeholder="Masukkan keterangan tambahan (opsional)">
            </div>
        </div>

        <!-- Detail Table -->
        <div class="border border-slate-200 rounded-lg overflow-hidden relative z-10 shadow-sm mb-4">
            <div class="overflow-y-auto max-h-[400px] custom-scrollbar overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-20">
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="px-3 py-3 w-20 bg-slate-50">Kode</th>
                            <th class="px-3 py-3 w-1/3 bg-slate-50 min-w-[200px]">Produk</th>
                            <th class="px-3 py-3 text-center border-l border-slate-200 bg-slate-50" colspan="3">Kuantitas</th>
                        </tr>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[9px] uppercase tracking-wider text-slate-400 font-bold sticky top-[41px] z-10">
                            <th class="px-3 py-2 bg-slate-50"></th>
                            <th class="px-3 py-2 bg-slate-50"></th>
                            <th class="px-3 py-2 text-center border-l border-slate-200 bg-slate-50 w-24">Dus</th>
                            <th class="px-3 py-2 text-center border-l border-slate-200 bg-slate-50 w-24">Pack</th>
                            <th class="px-3 py-2 text-center border-l border-slate-200 bg-slate-50 w-24">Pcs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($produk as $d)
                            @php
                                $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                                $jumlah_dus = $jumlah[0];
                                $jumlah_pack = $jumlah[1];
                                $jumlah_pcs = $jumlah[2];
                                
                                $hasPack = !empty($d->isi_pcs_pack);
                                $packDisabledClass = !$hasPack ? 'bg-slate-50/70 text-slate-400 cursor-not-allowed placeholder:text-transparent' : 'bg-transparent text-slate-700 focus:bg-white focus:ring-1 focus:ring-[#003d9e]/50';
                                $packReadOnlyAttr = !$hasPack ? 'readonly' : '';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-3 py-2">
                                    <input type="hidden" class="kode_produk" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                    <input type="hidden" class="isi_pcs_dus" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                    <input type="hidden" class="isi_pcs_pack" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-[11px] font-mono font-bold border border-slate-200 leading-none inline-block">{{ $d->kode_produk }}</span>
                                </td>
                                <td class="px-3 py-2 text-[13px] text-slate-700 font-bold leading-tight">
                                    {{ $d->nama_produk }}
                                </td>
                                <td class="p-1 border-l border-slate-100">
                                    <input type="text" class="w-full text-right px-2 py-2 text-sm font-bold bg-transparent border-0 focus:ring-1 focus:ring-[#003d9e]/50 focus:bg-white rounded transition-colors money jml_dus" name="jml_dus[]" value="{{ formatAngka($jumlah_dus) }}" placeholder="0">
                                </td>
                                <td class="p-1 border-l border-slate-100 {{ !$hasPack ? 'bg-slate-50/50' : '' }}">
                                    <input type="text" class="w-full text-right px-2 py-2 text-sm font-bold border-0 rounded transition-colors money jml_pack placeholder:text-slate-200 {{ $packDisabledClass }}" name="jml_pack[]" value="{{ $hasPack ? formatAngka($jumlah_pack) : '-' }}" placeholder="{{ $hasPack ? '0' : '-' }}" {{ $packReadOnlyAttr }}>
                                </td>
                                <td class="p-1 border-l border-slate-100">
                                    <input type="text" class="w-full text-right px-2 py-2 text-sm font-bold bg-transparent border-0 focus:ring-1 focus:ring-[#003d9e]/50 focus:bg-white rounded transition-colors money jml_pcs" name="jml_pcs[]" value="{{ formatAngka($jumlah_pcs) }}" placeholder="0">
                                </td>
                            </tr>
                        @endforeach
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
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg text-sm font-bold transition-colors shadow-md shadow-emerald-200 flex items-center gap-2" id="btnSubmit">
            <i class="fas fa-check-circle text-[10px]"></i>
            <span>Update Data</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formSuratjalan");
        $(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        $(document).off('submit', '#formSuratjalan').on('submit', '#formSuratjalan', function(e) {
            e.stopImmediatePropagation();
            const no_surat_jalan = $(this).find("#no_surat_jalan").val();
            const tanggal = $(this).find("#tanggal").val();
            const kode_cabang = $(this).find("#kode_cabang").val();

            if (no_surat_jalan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Surat Jalan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#no_surat_jalan").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#tanggal").focus();
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
                $(this).find("#btnSubmit").prop('disabled', true).addClass('opacity-50 cursor-not-allowed').html('<i class="fas fa-circle-notch fa-spin"></i> Memperbarui...');
                return true;
            }
        });
    });
</script>
