<form action="{{ route('laporanaccounting.cetakbiaya') }}" method="POST" target="_blank" id="formBiaya">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <select name="formatlaporan" id="formatlaporan" class="form-select">
                    <option value="1">Detail</option>
                    <option value="2">Rekap</option>
                </select>
            </div>
        </div>
    </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            @if (auth()->user()->kode_cabang == 'PST')
                <div class="form-group mb-3">
                    <select name="kode_cabang" id="kode_cabang" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="kode_cabang" value="{{ auth()->user()->kode_cabang }}">
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
<script>
    $(document).ready(function() {
        $("#formBiaya").submit(function(e) {
            var dari = $(this).find('input[name="dari"]').val();
            var sampai = $(this).find('input[name="sampai"]').val();
            var start = new Date(dari);
            var end = new Date(sampai);

            if (dari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode Dari Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find('input[name="dari"]').focus();
                    },
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode Sampai Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find('input[name="sampai"]').focus();
                    },
                });
                return false;
            } else if (start > end) {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode Tidak Valid !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find('input[name="sampai"]').focus();
                    },
                });
                return false;
            }
        });
    });
</script>
@endpush
