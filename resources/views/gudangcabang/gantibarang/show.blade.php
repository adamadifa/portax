<!-- Modal Header -->
<div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-[#003d9e] to-blue-700">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
            <i class="fas fa-file-alt text-white"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-white">Detail Ganti Barang</h3>
            <p class="text-blue-200 text-xs">{{ $mutasi->no_mutasi }}</p>
        </div>
    </div>
    <button onclick="window.closeTailwindModal()" class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
        <i class="fas fa-times text-sm"></i>
    </button>
</div>

<!-- Info Cards -->
<div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 mb-6">
        <!-- Kolom Kiri -->
        <table class="w-full text-sm">
            <tbody>
                <tr class="border-b border-slate-100">
                    <td class="py-2.5 pr-4 text-slate-400 font-medium w-32 text-left">Tanggal</td>
                    <td class="py-2.5 text-slate-700 font-semibold text-left">{{ DateToIndo($mutasi->tanggal) }}</td>
                </tr>
                <tr class="border-b border-slate-100 md:border-b-0">
                    <td class="py-2.5 pr-4 text-slate-400 font-medium w-32 text-left">Cabang</td>
                    <td class="py-2.5 text-slate-700 font-semibold text-left">{{ textUpperCase($mutasi->nama_cabang) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Kolom Kanan -->
        <table class="w-full text-sm">
            <tbody>
                <tr class="border-b border-slate-100">
                    <td class="py-2.5 pr-4 text-slate-400 font-medium w-32 text-left">Keterangan</td>
                    <td class="py-2.5 text-slate-700 font-semibold text-left">{{ !empty($mutasi->keterangan) ? $mutasi->keterangan : '-' }}</td>
                </tr>
                <tr class="hidden md:table-row">
                    <td class="py-2.5 pr-4"></td>
                    <td class="py-2.5"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detail Table -->
    <div class="border border-slate-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-4 py-3" rowspan="2">Kode</th>
                        <th class="px-4 py-3" rowspan="2">Nama Produk</th>
                        <th class="px-4 py-3 text-center border-l border-slate-200" colspan="3">Kuantitas</th>
                    </tr>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold text-center">
                        <th class="px-4 py-2 border-l border-slate-200">Dus</th>
                        <th class="px-4 py-2 border-l border-slate-200">Pack</th>
                        <th class="px-4 py-2 border-l border-slate-200">Pcs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($detail as $d)
                        @php
                            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                            $jumlah_dus = $jumlah[0];
                            $jumlah_pack = $jumlah[1];
                            $jumlah_pcs = $jumlah[2];
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-2.5">
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-mono font-medium border border-slate-200">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-sm text-slate-700 font-medium text-left">{{ $d->nama_produk }}</td>
                            <td class="px-4 py-2.5 text-sm text-slate-600 text-right border-l border-slate-100">{{ formatAngka($jumlah_dus) }}</td>
                            <td class="px-4 py-2.5 text-sm text-slate-600 text-right border-l border-slate-100">{{ formatAngka($jumlah_pack) }}</td>
                            <td class="px-4 py-2.5 text-sm text-slate-600 text-right border-l border-slate-100">{{ formatAngka($jumlah_pcs) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Footer -->
<div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex justify-end">
    <button onclick="window.closeTailwindModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition-colors">
        <i class="fas fa-times mr-1.5"></i>Tutup
    </button>
</div>
