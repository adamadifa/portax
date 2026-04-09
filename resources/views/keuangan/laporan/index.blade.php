@extends('layouts.app')
<style>
    .select2-container .select2-selection--single {
        height: 42px !important;
        background-color: #fff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155 !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        padding-left: 2.75rem !important;
        line-height: 40px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #94a3b8 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }

    .select2-dropdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #003d9e !important;
    }

    .form-select {
        border-color: #cbd5e1 !important;
        border-radius: 0.5rem !important;
        height: 42px !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        color: #334155 !important;
        padding-left: 2.75rem !important;
    }
</style>
@section('titlepage', 'Laporan Keuangan')

@section('content')
    <!-- Page Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Laporan Keuangan</h2>
            <p class="text-sm text-slate-500">Manajemen laporan kas, bank, dan keuangan lainnya.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i class="far fa-folder-open text-[#003d9e]"></i>
            <span>Keuangan</span>
            <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
            <i class="far fa-file-alt"></i>
            <span>Laporan</span>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Navigation -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                <ul class="nav flex flex-col py-2" id="reportTabs" role="tablist">
                    @php
                        $reports = [
                            ['id' => 'kaskecil', 'label' => 'Kas Kecil', 'icon' => 'fa-wallet', 'can' => 'keu.kaskecil'],
                            [
                                'id' => 'ledger',
                                'label' => auth()->user()->kode_cabang == 'PST' ? 'Ledger' : 'Mutasi Bank',
                                'icon' => 'fa-book-open',
                                'can' => 'keu.ledger',
                            ],
                            ['id' => 'mutasikeuangan', 'label' => 'Mutasi Keuangan', 'icon' => 'fa-exchange-alt', 'can' => 'keu.mutasikeuangan'],
                            ['id' => 'rekapledger', 'label' => 'Rekap Ledger', 'icon' => 'fa-file-invoice-dollar', 'hasRole' => ['super admin', 'gm administrasi', 'manager keuangan', 'direktur']],
                            ['id' => 'saldokasbesar', 'label' => 'Saldo Kas Besar', 'icon' => 'fa-money-check-alt', 'can' => 'keu.saldokasbesar'],
                            ['id' => 'lpu', 'label' => 'LPU', 'icon' => 'fa-file-alt', 'can' => 'keu.lpu'],
                            ['id' => 'penjualan', 'label' => 'Penjualan', 'icon' => 'fa-shopping-cart', 'can' => 'keu.penjualan'],
                            ['id' => 'uanglogam', 'label' => 'Uang Logam', 'icon' => 'fa-coins', 'can' => 'keu.uanglogam'],
                            ['id' => 'rekapbg', 'label' => 'Rekap BG', 'icon' => 'fa-money-bill-alt', 'can' => 'keu.rekapbg'],
                            ['id' => 'pinjaman', 'label' => 'PJP (Pinjaman)', 'icon' => 'fa-hand-holding-usd', 'can' => 'keu.pinjaman'],
                            ['id' => 'kasbon', 'label' => 'Kasbon', 'icon' => 'fa-user-clock', 'can' => 'keu.kasbon'],
                            ['id' => 'piutangkaryawan', 'label' => 'Piutang Karyawan', 'icon' => 'fa-user-tag', 'can' => 'keu.piutangkaryawan'],
                            ['id' => 'kartupinjaman', 'label' => 'Kartu PJP', 'icon' => 'fa-id-card-alt', 'can' => 'keu.kartupinjaman'],
                            ['id' => 'kartukasbon', 'label' => 'Kartu Kasbon', 'icon' => 'fa-id-badge', 'can' => 'keu.kartukasbon'],
                            ['id' => 'kartupiutangkaryawan', 'label' => 'Kartu Piutang Karyawan', 'icon' => 'fa-id-card', 'can' => 'keu.kartupiutangkaryawan'],
                            ['id' => 'rekapkartupiutang', 'label' => 'Rekap Kartu Pinjaman', 'icon' => 'fa-list-ol', 'can' => 'keu.rekapkartupiutang'],
                        ];
                    @endphp

                    @foreach ($reports as $report)
                        @php
                            $show = false;
                            if (isset($report['can'])) {
                                if (auth()->user()->can($report['can'])) {
                                    $show = true;
                                }
                            } elseif (isset($report['hasRole'])) {
                                if (auth()->user()->hasRole($report['hasRole'])) {
                                    $show = true;
                                }
                            }
                        @endphp
                        @if ($show)
                            <li class="nav-item">
                                <button type="button"
                                    class="report-link w-full flex items-center gap-3 px-6 py-2 text-sm font-medium transition-all duration-200 {{ $loop->first ? 'active bg-blue-50 text-[#003d9e] border-r-4 border-[#003d9e]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                                    data-bs-toggle="tab" data-bs-target="#{{ $report['id'] }}" role="tab"
                                    data-label="Laporan {{ $report['label'] }}">
                                    <i
                                        class="fas {{ $report['icon'] }} w-5 text-center {{ $loop->first ? 'text-[#003d9e]' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                                    <span>{{ $report['label'] }}</span>
                                </button>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Main Content area -->
        <div class="flex-1">
            <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden h-fit">
                <!-- Blue Header -->
                <div class="bg-[#003d9e] px-6 py-2.5 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class="fas fa-file-invoice text-white text-xs" id="headerIcon"></i>
                    </div>
                    <h3 class="text-white font-bold text-base mb-0" id="activeReportTitle">Laporan Kas Kecil</h3>
                </div>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    @can('keu.kaskecil')
                        <div class="tab-pane fade show active" id="kaskecil" role="tabpanel">
                            @include('keuangan.laporan.kaskecil')
                        </div>
                    @endcan

                    @can('keu.ledger')
                        <div class="tab-pane fade" id="ledger" role="tabpanel">
                            @include('keuangan.laporan.ledger')
                        </div>
                    @endcan

                    @can('keu.mutasikeuangan')
                        <div class="tab-pane fade" id="mutasikeuangan" role="tabpanel">
                            @include('keuangan.laporan.mutasikeuangan')
                        </div>
                    @endcan

                    @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                        <div class="tab-pane fade" id="rekapledger" role="tabpanel">
                            @include('keuangan.laporan.rekapledger')
                        </div>
                    @endhasanyrole

                    @can('keu.saldokasbesar')
                        <div class="tab-pane fade" id="saldokasbesar" role="tabpanel">
                            @include('keuangan.laporan.saldokasbesar')
                        </div>
                    @endcan

                    @can('keu.lpu')
                        <div class="tab-pane fade" id="lpu" role="tabpanel">
                            @include('keuangan.laporan.lpu')
                        </div>
                    @endcan

                    @can('keu.penjualan')
                        <div class="tab-pane fade" id="penjualan" role="tabpanel">
                            @include('keuangan.laporan.penjualan')
                        </div>
                    @endcan

                    @can('keu.uanglogam')
                        <div class="tab-pane fade" id="uanglogam" role="tabpanel">
                            @include('keuangan.laporan.uanglogam')
                        </div>
                    @endcan

                    @can('keu.rekapbg')
                        <div class="tab-pane fade" id="rekapbg" role="tabpanel">
                            @include('keuangan.laporan.rekapbg')
                        </div>
                    @endcan

                    @can('keu.pinjaman')
                        <div class="tab-pane fade" id="pinjaman" role="tabpanel">
                            @include('keuangan.laporan.pinjaman')
                        </div>
                    @endcan

                    @can('keu.kasbon')
                        <div class="tab-pane fade" id="kasbon" role="tabpanel">
                            @include('keuangan.laporan.kasbon')
                        </div>
                    @endcan

                    @can('keu.piutangkaryawan')
                        <div class="tab-pane fade" id="piutangkaryawan" role="tabpanel">
                            @include('keuangan.laporan.piutangkaryawan')
                        </div>
                    @endcan

                    @can('keu.kartupinjaman')
                        <div class="tab-pane fade" id="kartupinjaman" role="tabpanel">
                            @include('keuangan.laporan.kartupjp')
                        </div>
                    @endcan

                    @can('keu.kartukasbon')
                        <div class="tab-pane fade" id="kartukasbon" role="tabpanel">
                            @include('keuangan.laporan.kartukasbon')
                        </div>
                    @endcan

                    @can('keu.kartupiutangkaryawan')
                        <div class="tab-pane fade" id="kartupiutangkaryawan" role="tabpanel">
                            @include('keuangan.laporan.kartupiutangkaryawan')
                        </div>
                    @endcan

                    @can('keu.rekapkartupiutang')
                        <div class="tab-pane fade" id="rekapkartupiutang" role="tabpanel">
                            @include('keuangan.laporan.rekapkartupiutang')
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            // Update active state and header title on tab change
            $('.report-link').on('shown.bs.tab', function(e) {
                // Update navigation styles
                $('.report-link').removeClass('bg-blue-50 text-[#003d9e] border-r-4 border-[#003d9e]').addClass(
                    'text-slate-600 hover:bg-slate-50 hover:text-slate-900');
                $('.report-link i').removeClass('text-[#003d9e]').addClass('text-slate-400');

                $(e.target).addClass('bg-blue-50 text-[#003d9e] border-r-4 border-[#003d9e]').removeClass(
                    'text-slate-600 hover:bg-slate-50 hover:text-slate-900');
                $(e.target).find('i').addClass('text-[#003d9e]').removeClass('text-slate-400');

                // Update Header Title
                const label = $(e.target).data('label');
                $('#activeReportTitle').text(label);
            });
        });
    </script>
@endpush
