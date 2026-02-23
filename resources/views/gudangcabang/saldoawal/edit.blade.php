<form action="{{ route('sagudangcabang.update', Crypt::encrypt($saldo_awal->kode_saldo_awal)) }}" method="POST" id="formEditsaldoawal" autocomplete="off" aria-autocomplete="none" class="flex flex-col h-full">
    @csrf
    @method('PUT')
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-[#003d9e] to-blue-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-edit text-white text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">Edit Saldo Awal</h3>
                <p class="text-blue-200 text-xs text-left">Update kuantitas produk untuk saldo awal</p>
            </div>
        </div>
        <button type="button" class="btn-close-modal w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" onclick="window.closeTailwindModal()">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <div class="p-6 pb-2 text-left">
        <!-- Info Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Bulan & Tahun</span>
                <span class="text-xs font-bold text-slate-700">{{ $nama_bulan[$saldo_awal->bulan] }} {{ $saldo_awal->tahun }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Cabang</span>
                <span class="text-xs font-bold text-slate-700">{{ strtoupper($saldo_awal->kode_cabang) }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Kondisi</span>
                <span class="text-xs font-bold {{ $saldo_awal->kondisi == 'GS' ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $saldo_awal->kondisi == 'GS' ? 'GOOD STOK' : 'BAD STOK' }}
                </span>
            </div>
            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Kode</span>
                <span class="text-[11px] font-mono font-bold text-slate-500">{{ $saldo_awal->kode_saldo_awal }}</span>
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
                        @foreach ($detail as $d)
                            @php
                                $jumlah = array(0, 0, 0);
                                if (!empty($d->jumlah)) {
                                    $jumlah = convertToduspackpcsv3($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah);
                                }
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
        $(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        $(document).off('submit', '#formEditsaldoawal').on('submit', '#formEditsaldoawal', function(e) {
            e.stopImmediatePropagation();
            // Basic validation if needed
            $(this).find("#btnSubmit").prop('disabled', true).addClass('opacity-50 cursor-not-allowed').html('<i class="fas fa-circle-notch fa-spin"></i> Memperbarui...');
            return true;
        });
    });
</script>
