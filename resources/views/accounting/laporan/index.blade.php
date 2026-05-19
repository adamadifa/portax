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
        color: #94a3b8 !important;
        padding-left: 2.75rem !important;
    }
    .form-select:has(option:checked:not([value=""])) {
        color: #334155 !important;
    }
    .form-select option {
        color: #334155 !important;
    }
    .form-select option[value=""] {
        color: #94a3b8 !important;
    }
</style>
@section('titlepage', 'Laporan Accounting')

@section('content')
    <!-- Page Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Laporan Accounting</h2>
            <p class="text-sm text-slate-500">Rekap persediaan, cost ratio, jurnal umum, dan laporan keuangan.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i class="far fa-folder-open text-[#003d9e]"></i>
            <span>Accounting</span>
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
                            ['id' => 'rekappersediaan', 'label' => 'Rekap Persediaan', 'icon' => 'fa-boxes', 'can' => 'akt.rekappersediaan'],
                            ['id' => 'rekapbj', 'label' => 'Rekap BJ', 'icon' => 'fa-archive', 'can' => 'akt.rekapbj'],
                            ['id' => 'costratio', 'label' => 'Cost Ratio', 'icon' => 'fa-percentage', 'can' => 'akt.costratio'],
                            ['id' => 'jurnalumum', 'label' => 'Jurnal Umum', 'icon' => 'fa-book', 'can' => 'akt.jurnalumum'],
                            ['id' => 'bukubesar', 'label' => 'Laporan Keuangan', 'icon' => 'fa-file-invoice-dollar', 'can' => 'lk.bukubesar'],
                            ['id' => 'biaya', 'label' => 'Laporan Biaya', 'icon' => 'fa-calculator', 'can' => 'lk.bukubesar'],
                        ];
                        $firstActive = true;
                    @endphp

                    @foreach ($reports as $report)
                        @can($report['can'])
                            <li class="nav-item">
                                <button type="button" 
                                    class="report-link w-full flex items-center gap-3 px-6 py-2 text-sm font-medium transition-all duration-200 {{ $firstActive ? 'active bg-blue-50 text-[#003d9e] border-r-4 border-[#003d9e]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                                    data-bs-toggle="tab" 
                                    data-bs-target="#{{ $report['id'] }}" 
                                    role="tab"
                                    data-label="Laporan {{ $report['label'] }}">
                                    <i class="fas {{ $report['icon'] }} w-5 text-center {{ $firstActive ? 'text-[#003d9e]' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                                    <span>{{ $report['label'] }}</span>
                                </button>
                            </li>
                            @php
                                $firstActive = false;
                            @endphp
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
                        <i class="fas fa-file-invoice text-white text-xs" id="headerIcon"></i>
                    </div>
                    <h3 class="text-white font-bold text-base mb-0" id="activeReportTitle">Laporan Accounting</h3>
                </div>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    @php
                        $firstPane = true;
                    @endphp
                    @can('akt.rekappersediaan')
                        <div class="tab-pane fade {{ $firstPane ? 'show active' : '' }}" id="rekappersediaan" role="tabpanel">
                            @include('accounting.laporan.rekappersediaan')
                        </div>
                        @php $firstPane = false; @endphp
                    @endcan
                    @can('akt.rekapbj')
                        <div class="tab-pane fade {{ $firstPane ? 'show active' : '' }}" id="rekapbj" role="tabpanel">
                            @include('accounting.laporan.rekapbj')
                        </div>
                        @php $firstPane = false; @endphp
                    @endcan
                    @can('akt.costratio')
                        <div class="tab-pane fade {{ $firstPane ? 'show active' : '' }}" id="costratio" role="tabpanel">
                            @include('accounting.laporan.costratio')
                        </div>
                        @php $firstPane = false; @endphp
                    @endcan
                    @can('akt.jurnalumum')
                        <div class="tab-pane fade {{ $firstPane ? 'show active' : '' }}" id="jurnalumum" role="tabpanel">
                            @include('accounting.laporan.jurnalumum')
                        </div>
                        @php $firstPane = false; @endphp
                    @endcan
                    @can('lk.bukubesar')
                        <div class="tab-pane fade {{ $firstPane ? 'show active' : '' }}" id="bukubesar" role="tabpanel">
                            @include('accounting.laporan.lk.bukubesar')
                        </div>
                        @php $firstPane = false; @endphp
                    @endcan
                    @can('lk.bukubesar')
                        <div class="tab-pane fade {{ $firstPane ? 'show active' : '' }}" id="biaya" role="tabpanel">
                            @include('accounting.laporan.biaya')
                        </div>
                        @php $firstPane = false; @endphp
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            // Set initial header title based on active tab
            const initialActiveTab = $('.report-link.active');
            if (initialActiveTab.length) {
                $('#activeReportTitle').text(initialActiveTab.data('label'));
            }

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

            // Handle select placeholder color dynamically for native select dropdowns
            $(document).on('change', 'select.form-select', function() {
                if ($(this).val() === "") {
                    $(this).css('color', '#94a3b8');
                } else {
                    $(this).css('color', '#334155');
                }
            });
            // Initial run
            $('select.form-select').each(function() {
                if ($(this).val() === "") {
                    $(this).css('color', '#94a3b8');
                } else {
                    $(this).css('color', '#334155');
                }
            });
        });
    </script>
@endpush
