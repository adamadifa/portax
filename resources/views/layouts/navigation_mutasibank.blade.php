@if (auth()->user()->hasAnyPermission(['mutasibank.index', 'samutasibank.index']))
    <div class="flex flex-wrap gap-1 -mb-px">
        @can('samutasibank.index')
            <a href="{{ route('samutasibank.index') }}" 
               class="flex items-center gap-2 px-4 py-3 text-sm font-bold transition-all border-b-2 {{ request()->is(['samutasibank']) ? 'border-[#003d9e] text-[#003d9e]' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <i class="ti ti-database-heart text-lg"></i>
                <span>Saldo Awal</span>
            </a>
        @endcan

        @can('mutasibank.index')
            <a href="{{ route('mutasibank.index') }}" 
               class="flex items-center gap-2 px-4 py-3 text-sm font-bold transition-all border-b-2 {{ request()->is(['mutasibank']) ? 'border-[#003d9e] text-[#003d9e]' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <i class="ti ti-file-description text-lg"></i>
                <span>Mutasi Bank</span>
            </a>
        @endcan
    </div>
@endif
