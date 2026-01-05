@extends('layouts.app')
@section('titlepage', 'Reset Data')

@section('content')
@section('navigasi')
    <span>Reset Data</span>
@endsection

<style>
    .danger-zone {
        border: 3px solid #dc3545;
        border-radius: 12px;
        padding: 30px;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
    }

    .warning-box {
        background: #fff3cd;
        border-left: 5px solid #ffc107;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
    }

    .danger-icon {
        font-size: 80px;
        color: #dc3545;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin: 10px 0;
    }

    .stats-number {
        font-size: 36px;
        font-weight: 700;
        color: #03204f;
    }

    .stats-label {
        color: #6c757d;
        font-size: 14px;
        margin-top: 5px;
    }

    .confirm-input {
        font-size: 18px;
        font-weight: 700;
        text-align: center;
        letter-spacing: 2px;
        border: 2px solid #dc3545;
    }

    .btn-reset {
        background: #dc3545;
        color: white;
        font-weight: 700;
        font-size: 18px;
        padding: 15px 40px;
        border: none;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-reset:hover {
        background: #bb2d3b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }

    .protected-list {
        max-height: 300px;
        overflow-y: auto;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    .log-box {
        background: #1e1e1e;
        color: #00ff00;
        font-family: 'Courier New', monospace;
        padding: 20px;
        border-radius: 8px;
        max-height: 400px;
        overflow-y: auto;
        margin-top: 20px;
    }

    .log-line {
        margin: 5px 0;
        font-size: 12px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
            </h5>
            <hr>
            <p class="mb-0">
                <strong>Tabel di-reset:</strong> {{ session('reset_count') }} tabel<br>
                <strong>Tabel dilindungi:</strong> {{ session('protected_count') }} tabel
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        @if(session('log'))
        <div class="log-box">
            <div style="color: #00ff00; margin-bottom: 10px;">📋 LOG RESET DATA:</div>
            @foreach(session('log') as $logLine)
            <div class="log-line">{{ $logLine }}</div>
            @endforeach
        </div>
        @endif
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="ti ti-x-circle me-2"></i>Error!
            </h5>
            <p class="mb-0">{{ session('error') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Danger Zone Card -->
        <div class="danger-zone">
            <div class="text-center mb-4">
                <i class="ti ti-alert-triangle danger-icon"></i>
                <h2 class="text-danger mt-3 mb-2" style="font-weight: 800;">ZONA BERBAHAYA</h2>
                <h4 class="text-danger">Reset Data Database</h4>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <h5 class="mb-3">
                    <i class="ti ti-alert-circle me-2" style="color: #ffc107;"></i>
                    <strong>PERINGATAN PENTING!</strong>
                </h5>
                <p class="mb-2"><strong>Fitur ini akan:</strong></p>
                <ul class="mb-0">
                    <li>✅ Menghapus <strong>SEMUA DATA</strong> dari database</li>
                    <li>✅ Menghapus semua user <strong>KECUALI</strong> user dengan ID = 1</li>
                    <li>❌ Data yang dihapus <strong>TIDAK BISA</strong> dikembalikan</li>
                    <li>🔒 Beberapa tabel sistem akan dilindungi (roles, permissions, dll)</li>
                </ul>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stats-number text-primary">{{ $total_tables }}</div>
                        <div class="stats-label">Total Tabel</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stats-number text-success">{{ $protected_tables }}</div>
                        <div class="stats-label">Tabel Dilindungi</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center">
                        <div class="stats-number text-danger">{{ $reset_tables }}</div>
                        <div class="stats-label">Tabel Akan Di-Reset</div>
                    </div>
                </div>
            </div>

            <!-- Protected Tables -->
            <div class="mb-4">
                <h5 class="mb-3">
                    <i class="ti ti-shield-check me-2"></i>
                    Tabel yang Dilindungi:
                </h5>
                <div class="protected-list">
                    <div class="row">
                        @foreach($protected_list as $table)
                        <div class="col-md-4 mb-2">
                            <span class="badge bg-success">
                                <i class="ti ti-shield me-1"></i>{{ $table }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Reset Form -->
            <div class="card border-danger">
                <div class="card-body">
                    <form action="{{ route('resetdata.reset') }}" method="POST" id="resetForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label text-danger" style="font-weight: 700;">
                                <i class="ti ti-key me-2"></i>
                                Konfirmasi: Ketik "RESET" (huruf besar) untuk melanjutkan
                            </label>
                            <input 
                                type="text" 
                                name="confirmation" 
                                class="form-control confirm-input @error('confirmation') is-invalid @enderror" 
                                placeholder="Ketik RESET disini"
                                required
                                autocomplete="off"
                            >
                            @error('confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="agreeCheck" required>
                            <label class="form-check-label text-danger" for="agreeCheck" style="font-weight: 600;">
                                Saya memahami bahwa data yang dihapus tidak dapat dikembalikan
                            </label>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-reset" id="btnReset" disabled>
                                <i class="ti ti-trash me-2"></i>
                                RESET DATA SEKARANG
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-4" role="alert">
                <i class="ti ti-info-circle me-2"></i>
                <strong>Alternatif:</strong> Anda juga bisa menggunakan command Artisan:
                <code class="ms-2">php artisan data:reset</code>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        // Enable/disable submit button based on checkbox
        $('#agreeCheck').change(function() {
            $('#btnReset').prop('disabled', !this.checked);
        });

        // Confirm before submit
        $('#resetForm').submit(function(e) {
            const confirmation = $('input[name="confirmation"]').val();
            
            if (confirmation !== 'RESET') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Konfirmasi Salah!',
                    text: 'Anda harus mengetik "RESET" (huruf besar) untuk melanjutkan',
                });
                return false;
            }

            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Terakhir!',
                html: '<strong style="color: #dc3545;">Apakah Anda YAKIN ingin menghapus semua data?</strong><br><br>Proses ini tidak dapat dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Reset Data!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Sedang Mereset Data...',
                        html: 'Mohon tunggu, proses sedang berjalan...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    e.target.submit();
                }
            });
        });
    });
</script>
@endpush

















