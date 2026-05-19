<div class="space-y-6">
    <!-- Header/Meta Info -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div>
            <h3 class="text-lg font-bold text-slate-800" id="modal-title">Detail Saldo Awal Buku Besar</h3>
            <p class="text-slate-500 text-xs">Informasi detail saldo awal buku besar.</p>
        </div>
        <button onclick="closeModal()" type="button" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Kode Saldo Awal</label>
            <span class="font-mono text-sm font-semibold text-slate-700">{{ $saldoawalbukubesar->kode_saldo_awal }}</span>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Cabang</label>
            <span class="text-sm font-semibold text-slate-700">{{ $saldoawalbukubesar->nama_cabang }}</span>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Periode</label>
            <span class="text-sm font-semibold text-slate-700">{{ $nama_bulan[$saldoawalbukubesar->bulan] }} {{ $saldoawalbukubesar->tahun }}</span>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Tanggal Input</label>
            <span class="text-sm font-semibold text-slate-700">{{ date('d-m-Y', strtotime($saldoawalbukubesar->tanggal)) }}</span>
        </div>
    </div>

    <!-- Account Balances Table -->
    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto max-h-[350px] overflow-y-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold sticky top-0 z-10">
                        <th class="px-4 py-2">Kode Akun</th>
                        <th class="px-4 py-2">Nama Akun</th>
                        <th class="px-4 py-2 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($detailsaldoawalbukubesar as $d)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-2.5">
                                <span class="font-mono text-xs font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200">{{ $d->kode_akun }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-sm font-medium text-slate-700">{{ $d->nama_akun }}</td>
                            <td class="px-4 py-2.5 text-sm text-right font-semibold text-slate-800 font-mono">
                                {{ formatAngka($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-400 italic text-sm">
                                Tidak ada data detail saldo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Footer Actions -->
    <div class="flex justify-end pt-4 border-t border-slate-100">
        <button onclick="closeModal()" type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-sm font-semibold transition-colors">
            Tutup
        </button>
    </div>
</div>

<script>
    function closeModal() {
        const modal = document.getElementById('tailwindModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
