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
@section('titlepage', 'Laporan Gudang Cabang')

@section('content')
    <!-- Page Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Laporan Gudang Cabang</h2>
            <p class="text-sm text-slate-500">Monitoring stok, mutasi, dan statistik gudang cabang.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i class="far fa-folder-open text-[#003d9e]"></i>
            <span>Gudang Cabang</span>
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
                            ['id' => 'goodstok', 'label' => 'Lap. Persediaan GS', 'icon' => 'fa-check-circle', 'can' => 'gc.goodstok'],
                            ['id' => 'badstok', 'label' => 'Lap. Persediaan BS', 'icon' => 'fa-times-circle', 'can' => 'gc.badstok'],
                            ['id' => 'rekappersediaan', 'label' => 'Rekap Persediaan', 'icon' => 'fa-boxes', 'can' => 'gc.rekappersediaan'],
                            ['id' => 'mutasidpb', 'label' => 'Mutasi DPB', 'icon' => 'fa-truck-loading', 'can' => 'gc.mutasidpb'],
                            ['id' => 'rekonsiliasibj', 'label' => 'Rekonsiliasi BJ', 'icon' => 'fa-sync-alt', 'can' => 'gc.rekonsiliasibj'],
                        ];
                    @endphp

                    @foreach ($reports as $report)
                        @can($report['can'])
                            <li class="nav-item">
                                <button type="button" 
                                    class="report-link w-full flex items-center gap-3 px-6 py-2 text-sm font-medium transition-all duration-200 {{ $loop->first ? 'active bg-blue-50 text-[#003d9e] border-r-4 border-[#003d9e]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                                    data-bs-toggle="tab" 
                                    data-bs-target="#{{ $report['id'] }}" 
                                    role="tab"
                                    data-label="{{ $report['label'] }}">
                                    <i class="fas {{ $report['icon'] }} w-5 text-center {{ $loop->first ? 'text-[#003d9e]' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                                    <span>{{ $report['label'] }}</span>
                                </button>
                            </li>
                        @endcan
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
                        <i class="fas fa-warehouse text-white text-xs" id="headerIcon"></i>
                    </div>
                    <h3 class="text-white font-bold text-base mb-0" id="activeReportTitle">Lap. Persediaan GS</h3>
                </div>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    @can('gc.goodstok')
                        <div class="tab-pane fade show active" id="goodstok" role="tabpanel">
                            @include('gudangcabang.laporan.goodstok')
                        </div>
                    @endcan
                    @can('gc.badstok')
                        <div class="tab-pane fade" id="badstok" role="tabpanel">
                            @include('gudangcabang.laporan.badstok')
                        </div>
                    @endcan
                    @can('gc.rekappersediaan')
                        <div class="tab-pane fade" id="rekappersediaan" role="tabpanel">
                            @include('gudangcabang.laporan.rekappersediaan')
                        </div>
                    @endcan
                    @can('gc.mutasidpb')
                        <div class="tab-pane fade" id="mutasidpb" role="tabpanel">
                            @include('gudangcabang.laporan.mutasidpb')
                        </div>
                    @endcan
                    @can('gc.rekonsiliasibj')
                        <div class="tab-pane fade" id="rekonsiliasibj" role="tabpanel">
                            @include('gudangcabang.laporan.rekonsiliasibj')
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
                $('.report-link').removeClass('bg-blue-50 text-[#003d9e] border-r-4 border-[#003d9e]').addClass('text-slate-600 hover:bg-slate-50 hover:text-slate-900');
                $('.report-link i').removeClass('text-[#003d9e]').addClass('text-slate-400');
                
                $(e.target).addClass('bg-blue-50 text-[#003d9e] border-r-4 border-[#003d9e]').removeClass('text-slate-600 hover:bg-slate-50 hover:text-slate-900');
                $(e.target).find('i').addClass('text-[#003d9e]').removeClass('text-slate-400');

                // Update Header Title
                const label = $(e.target).data('label');
                $('#activeReportTitle').text(label);
            });
        });
    </script>
@endpush


@push('myscript')
<script>
   $(function() {




   });
</script>
@endpush
