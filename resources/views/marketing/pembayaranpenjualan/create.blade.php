<div class="bg-blue-50 text-blue-600 p-3 rounded-lg mb-4 flex items-center gap-3 border border-blue-100">
    <i class="ti ti-wallet text-xl"></i>
    <div>
        <h6 class="font-bold text-xs uppercase tracking-wide text-blue-500 mb-0.5">Saldo Voucher</h6>
        <h4 class="font-bold text-lg leading-none">{{ formatAngka($saldo_voucher) }}</h4>
    </div>
</div>

<form id="formBayar" method="POST" action="{{ route('pembayaranpenjualan.store', Crypt::encrypt($no_faktur)) }}">
    @csrf
    
    <!-- Tanggal -->
    <div class="relative mb-4">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Tanggal Pembayaran</label>
        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
            <span class="pl-3 text-slate-400"><i class="ti ti-calendar"></i></span>
            <input type="text" name="tanggal" id="tanggal" class="flatpickr-date w-full px-2 py-2.5 text-sm border-0 focus:ring-0 placeholder-slate-400" placeholder="Pilih Tanggal">
        </div>
    </div>

    <!-- Jumlah -->
    <div class="relative mb-4">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jumlah Bayar</label>
        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
            <span class="pl-3 text-slate-400"><i class="ti ti-moneybag"></i></span>
            <input type="text" name="jumlah" id="jumlah" class="money w-full px-2 py-2.5 text-right text-sm border-0 focus:ring-0 placeholder-slate-400 font-bold" placeholder="0">
        </div>
    </div>

    <!-- Jenis Bayar -->
    <div class="relative mb-4">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jenis Bayar</label>
        <div class="flex items-center border border-slate-300 rounded-lg focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
            <span class="pl-3 text-slate-400"><i class="ti ti-credit-card"></i></span>
            <div class="w-full">
                 <select name="jenis_bayar" id="jenis_bayar" class="select2Jenisbayar w-full border-0 focus:ring-0">
                    <option value="">Jenis Bayar</option>
                    @foreach($jenis_bayar as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if ($level_user == 'salesman')
        <input type="hidden" name="kode_salesman" value="{{ Auth::user()->kode_salesman }}" />
    @else
        <div class="relative mb-4">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Salesman Penagih</label>
            <div class="flex items-center border border-slate-300 rounded-lg focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
                <span class="pl-3 text-slate-400"><i class="ti ti-user"></i></span>
                <div class="w-full">
                     <select name="kode_salesman" id="kode_salesman" class="select2Kodesalesman w-full border-0 focus:ring-0">
                        <option value="">Salesman Penagih</option>
                        @foreach($salesman as $d)
                            <option value="{{ $d->kode_salesman }}">{{ strtoupper($d->nama_salesman) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-4">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input class="form-checkbox h-4 w-4 text-[#003d9e] rounded border-slate-300 focus:ring-[#003d9e] agreementvoucher" name="agreementvoucher" value="1" type="checkbox" id="agreementvoucher">
            <span class="text-sm font-medium text-slate-700">Bayar Menggunakan Voucher ?</span>
        </label>
    </div>

    <div class="mb-4" id="voucher">
        <div class="relative mb-4">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Pilih Voucher</label>
            <div class="flex items-center border border-slate-300 rounded-lg focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
                <span class="pl-3 text-slate-400"><i class="ti ti-ticket"></i></span>
                 <div class="w-full">
                    <select name="jenis_voucher" id="jenis_voucher" class="select2Kodevoucher w-full border-0 focus:ring-0">
                        <option value="">Pilih Voucher</option>
                        @foreach($jenis_voucher as $d)
                            <option value="{{ $d->id }}">{{ strtoupper($d->nama_voucher) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
         <label class="flex items-center gap-2 cursor-pointer select-none">
            <input class="form-checkbox h-4 w-4 text-[#003d9e] rounded border-slate-300 focus:ring-[#003d9e] agreementgiro" name="agreementgiro" value="1" type="checkbox" id="agreementgiro">
            <span class="text-sm font-medium text-slate-700">Ganti Giro Ke Cash ?</span>
        </label>
    </div>

    <div class="mb-4" id="giroditolak">
        <div class="relative mb-4">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Pilih Giro</label>
            <div class="flex items-center border border-slate-300 rounded-lg focus-within:ring-1 focus-within:ring-[#003d9e] focus-within:border-[#003d9e]">
                 <span class="pl-3 text-slate-400"><i class="ti ti-building-bank"></i></span>
                 <div class="w-full">
                    <select name="kode_giro" id="kode_giro" class="select2Kodegiro w-full border-0 focus:ring-0">
                        <option value="">Pilih Giro</option>
                        @foreach($giroditolak as $d)
                            <option value="{{ $d->kode_giro }}">{{ $d->no_giro }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Custom Select2 Styling to match input fields */
        .select2-container--default .select2-selection--single {
            border: none !important;
            height: 100% !important;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
        }
    </style>

    <div class="mt-6">
        <button class="w-full bg-[#003d9e] hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all active:scale-95 flex items-center justify-center gap-2" id="btnSimpan">
            <i class="ti ti-send"></i> Submit Pembayaran
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formBayar");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }


        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ date('Y-m-d') }}"
            }, ]
        });


        const select2Kodesalesman = $('.select2Kodesalesman');
        if (select2Kodesalesman.length) {
            select2Kodesalesman.each(function() {
                var $this = $(this);
                $this.select2({
                    placeholder: 'Salesman Penagih',
                    allowClear: true,
                    dropdownParent: $('#modal')
                });
            });
        }

        const select2Kodevoucher = $('.select2Kodevoucher');
        if (select2Kodevoucher.length) {
            select2Kodevoucher.each(function() {
                var $this = $(this);
                $this.select2({
                    placeholder: 'Pilih Voucher',
                    allowClear: true,
                    dropdownParent: $('#modal')
                });
            });
        }

        const select2Jenisbayar = $('.select2Jenisbayar');
        if (select2Jenisbayar.length) {
            select2Jenisbayar.each(function() {
                var $this = $(this);
                $this.select2({
                    placeholder: 'Jenis Bayar',
                    allowClear: true,
                    dropdownParent: $('#modal')
                });
            });
        }

        const select2Kodegiro = $('.select2Kodegiro');
        if (select2Kodegiro.length) {
            select2Kodegiro.each(function() {
                var $this = $(this);
                $this.select2({
                    placeholder: 'Pilih Giro',
                    allowClear: true,
                    dropdownParent: $('#modal')
                });
            });
        }
        $("#jumlah").maskMoney();

        form.find("#voucher").hide();
        form.find("#giroditolak").hide();
        form.find('.agreementvoucher').change(function() {
            if (this.checked) {
                console.log($(".agreementvoucher").is(':checked'));
                form.find("#voucher").show();
            } else {
                form.find("#voucher").hide();
            }
        });
        form.find('.agreementgiro').change(function() {
            if (this.checked) {
                form.find("#giroditolak").show();
            } else {
                form.find("#giroditolak").hide();
            }
        });



        form.submit(function(e) {
            //e.preventDefault();
            let sisabayar = $("#sisabayar").text();
            let sb = sisabayar == "" ? 0 : sisabayar;
            let sisa_bayar = sb == 0 ? 0 : parseInt(sb.replace(/\./g, ''));
            const tanggal = $(this).find("#tanggal").val();
            const jml = $(this).find("#jumlah").val();
            const jumlah = parseInt(jml.replace(/\./g, ''));
            const kode_salesman = $(this).find("#kode_salesman").val();
            const jenis_bayar = $(this).find("#jenis_bayar").val();
            const jenis_voucher = $(this).find("#jenis_voucher").val();
            let saldo_voucher = "{{ $saldo_voucher }}";
            const kode_giro = $(this).find("#kode_giro").val();
            if (isNaN(sisa_bayar)) {
                sisa_bayar = 0;
            } else {
                sisa_bayar = sisa_bayar;
            }

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (jml === "" || jml === '0') {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (parseInt(jumlah) > parseInt(sisa_bayar)) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Bayar Melebihi Sisa Bayar !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (jenis_bayar == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Bayar Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jenis_bayar").focus();
                    },
                });

                return false;
            } else if (kode_salesman == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Salesman Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_salesman").focus();
                    },
                });

                return false;
            } else if ($(".agreementvoucher").is(':checked') && jenis_voucher == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Voucher Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jenis_voucher").focus();
                    },
                });

                return false;
            } else if ($(".agreementvoucher").is(':checked') && jenis_voucher == "2" && parseInt(jumlah) > parseInt(saldo_voucher)) {
                Swal.fire({
                    title: "Oops!",
                    text: "Saldo Voucher Tidak Cukup !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jenis_voucher").focus();
                    },
                });

                return false;
            } else if ($(".agreementgiro").is(':checked') && kode_giro == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Pilih Giro  Yang Diganti !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_giro").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
