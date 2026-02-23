@extends('layouts.app')
<style>
    .select2-container .select2-selection--single {
        height: 46px !important;
        padding: 10px 12px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        background-color: #fff !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal !important;
        padding-left: 0 !important;
        color: #1e293b !important;
        font-size: 0.875rem !important;
        flex-grow: 1 !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
        top: 1px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #94a3b8 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear {
        margin-right: 0px !important;
        font-weight: bold !important;
        color: #cbd5e1 !important;
        order: 2 !important;
        margin-left: auto !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear:hover {
        color: #64748b !important;
    }
    .form-select {
        border-color: #cbd5e1 !important;
        border-radius: 0.5rem !important;
    }
    .form-select:focus {
            border-color: #003d9e !important;
            box-shadow: 0 0 0 1px #003d9e !important;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        padding: 8px 12px !important;
    }
    .select2-search--dropdown .select2-search__field:focus {
        border-color: #003d9e !important;
        outline: none !important;
        box-shadow: 0 0 0 1px #003d9e !important;
    }
</style>
@section('titlepage', 'Laporan Gudang Cabang')

@section('content')

@section('navigasi')
   <span>Laporan Gudang Cabang</span>
@endsection
<div class="row">
   <div class="col-xl-6 col-md-12 col-sm-12">
      <div class="nav-align-left nav-tabs-shadow mb-4 text-left">
         <ul class="nav nav-tabs lg:w-48" role="tablist">
            @can('gc.goodstok')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                     data-bs-target="#goodstok" aria-controls="goodstok" aria-selected="false" tabindex="-1">
                     Lap. Persediaan GS
                  </button>
               </li>
            @endcan
            @can('gc.badstok')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                     data-bs-target="#badstok" aria-controls="badstok" aria-selected="false" tabindex="-1">
                     Lap. Persediaan BS
                  </button>
               </li>
            @endcan
            @can('gc.rekappersediaan')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                     data-bs-target="#rekappersediaan" aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                     Rekap Persediaan
                  </button>
               </li>
            @endcan
            <!-- @can('gc.mutasidpb')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                     data-bs-target="#mutasidpb" aria-controls="mutasidpb" aria-selected="false" tabindex="-1">
                     Mutasi DPB
                  </button>
               </li>
            @endcan -->
            <!-- @can('gc.rekonsiliasibj')
               <li class="nav-item" role="presentation">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                     data-bs-target="#rekonsiliasibj" aria-controls="rekonsiliasibj" aria-selected="false" tabindex="-1">
                     Rekonsiliasi BJ
                  </button>
               </li> -->
            @endcan
         </ul>
         <div class="tab-content">
            <!-- Laporan Persediaan-->
            @can('gc.goodstok')
               <div class="tab-pane fade active show" id="goodstok" role="tabpanel">
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




   });
</script>
@endpush
