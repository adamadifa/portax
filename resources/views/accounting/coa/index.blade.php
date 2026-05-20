@extends('layouts.app')
@section('titlepage', 'Chart of Account (COA)')

@section('content')
@section('navigasi')
    <span>Chart of Account (COA)</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('coa.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="ti ti-plus me-1"></i>Tambah Akun</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode Akuns</th>
                                    <th>Nama Akun</th>
                                    <th>Akun Portax</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Memulai perulangan untuk menampilkan setiap baris akun --}}
                                {{-- Loop semua akun yang dikirim dari controller --}}
                                @foreach ($allAccounts as $account)
                                    <tr>
                                        <td>{{ $account->kode_akun }}</td>
                                        <td>{{ $account->nama_akun }}</td>
                                        <td>
                                            @if ($account->coaPortax)
                                                <span class="badge bg-label-primary">{{ $account->kode_akun_portax }} - {{ $account->coaPortax->nama_akun }}</span>
                                            @else
                                                <span class="badge bg-label-secondary text-muted">Belum Dipetakan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @can('coa.edit')
                                                    <a href="#" class="btnEdit me-2" kode_akun="{{ Crypt::encrypt($account->kode_akun) }}">
                                                        <i class="ti ti-edit text-success fs-4"></i>
                                                    </a>
                                                @endcan
                                                @can('coa.delete')
                                                    <form method="POST" name="deleteform" class="deleteform m-0" action="{{ route('coa.delete', Crypt::encrypt($account->kode_akun)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm">
                                                            <i class="ti ti-trash text-danger fs-4"></i>
                                                        </a>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Tambah Akun");
            $("#loadmodal").load(`/coa/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_akun = $(this).attr('kode_akun');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Akun");
            $("#loadmodal").load(`/coa/${kode_akun}/edit`);
        });
    });
</script>
@endpush
