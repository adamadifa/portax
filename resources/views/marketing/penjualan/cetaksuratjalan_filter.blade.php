<form action="{{ route('penjualan.cetaksuratjalanrange') }}" target="_blank" method="POST" id="formCetakfaktur" class="space-y-4">
    @csrf

    <!-- Select2 Custom CSS to match Tailwind Inputs -->
    <style>
        .select2-container .select2-selection--single {
            height: 46px !important;
            padding: 10px 12px !important;
            border: 1px solid #cbd5e1 !important; /* slate-300 */
            border-radius: 0.5rem !important; /* rounded-lg */
            background-color: #fff !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0 !important;
            color: #1e293b !important; /* slate-800 */
            font-size: 0.875rem !important; /* text-sm */
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            top: 1px !important;
            right: 8px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important; /* slate-400 */
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #003d9e !important;
            box-shadow: 0 0 0 1px #003d9e !important; 
            outline: none !important;
        }
        .flatpickr-calendar {
            z-index: 9999 !important;
        }
    </style>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Dari -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Dari <span class="text-red-500">*</span></label>
            <input type="text" name="dari" id="dari" 
                class="flatpickr-date w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                placeholder="Tanggal Awal">
        </div>

        <!-- Sampai -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Sampai <span class="text-red-500">*</span></label>
            <input type="text" name="sampai" id="sampai" 
                class="flatpickr-date w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                placeholder="Tanggal Akhir">
        </div>
    </div>

    @hasanyrole($roles_show_cabang)
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang</label>
            <select name="kode_cabang" id="kode_cabang" class="select2Kodecabang w-full text-left" data-placeholder="Semua Cabang">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $c)
                    <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                @endforeach
            </select>
        </div>
    @endrole

    <div class="relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Salesman</label>
        <select name="kode_salesman" id="kode_salesman" class="select2Kodesalesman w-full text-left" data-placeholder="Semua Salesman">
            <option value="">Semua Salesman</option>
        </select>
    </div>

    <div class="pt-2">
        <button class="w-full px-4 py-2.5 text-sm font-bold text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-sm shadow-blue-200 transition-colors flex items-center justify-center gap-2">
            <i class="ti ti-printer"></i>
            <span>Cetak Faktur</span>
        </button>
    </div>

</form>
<script>
    $(function() {

        // Flatpickr Scope to Form
        $("#formCetakfaktur .flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        const form = $("#formCetakfaktur");
        
        // Select2 Initialization with Styling Fixes
        form.find('.select2Kodecabang').select2({
            dropdownParent: form.parent(), // Assuming typical modal parent
            width: '100%',
            placeholder: 'Semua Cabang',
            allowClear: true
        });

        form.find('.select2Kodesalesman').select2({
            dropdownParent: form.parent(),
            width: '100%',
            placeholder: 'Semua Salesman',
            allowClear: true
        });

        function getsalesmanbyCabang() {
            var kode_cabang = form.find("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    form.find("#kode_salesman").html(respond);
                }
            });
        }
        
        form.find("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();

        form.submit(function() {
            const dari = $(this).find("#dari").val();
            const sampai = $(this).find("#sampai").val();
            const start = new Date(dari);
            const end = new Date(sampai);
            
            // Remove previous error styling/messages
            form.find('input').removeClass('!border-red-500');
            
            if (dari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Dari Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#dari").focus().addClass('!border-red-500');
                    },
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Sampai Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#sampai").focus().addClass('!border-red-500');
                    },
                });
                return false;
            } else if (start.getTime() > end.getTime()) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#sampai").focus().addClass('!border-red-500');
                    },
                });
                return false;
            }
        });
    });
</script>
