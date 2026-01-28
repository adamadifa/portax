<form action="{{ route('cabang.update', Crypt::encrypt($cabang->kode_cabang)) }}" id="formeditCabang" method="POST" class="space-y-3">
    @csrf
    @method('PUT')
    
    <style>
        .select2-container .select2-selection--single {
            height: 46px !important; /* height-11 approx */
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
            color: #94a3b8 !important; /* slate-400 */
        }
        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 0px !important;
            font-weight: bold !important;
            color: #cbd5e1 !important; /* light gray */
            order: 2 !important; /* push to right if needed */
            margin-left: auto !important; /* push x to end of rendered box */
        }
        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #64748b !important;
        }
    </style>

    <!-- Header/Title for Modal -->
    <div class="border-b border-slate-200 pb-3 mb-3 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800">Edit Data Cabang</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Kode Cabang (Readonly) -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kode Cabang</label>
            <input type="text" name="kode_cabang" value="{{ $cabang->kode_cabang }}" readonly class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-500 cursor-not-allowed focus:outline-none placeholder-slate-400">
        </div>

        <!-- Nama Cabang -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Nama Cabang</label>
            <input type="text" name="nama_cabang" value="{{ $cabang->nama_cabang }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Nama Cabang">
        </div>

        <!-- Alamat Cabang -->
        <div class="relative md:col-span-2">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Alamat Cabang</label>
            <textarea name="alamat_cabang" rows="2" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Alamat Lengkap">{{ $cabang->alamat_cabang }}</textarea>
        </div>

        <!-- Telepon -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Telepon</label>
            <input type="text" name="telepon_cabang" value="{{ $cabang->telepon_cabang }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Nomor Telepon">
        </div>

        <!-- Lokasi (Koordinator) -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Lokasi (Lat, Long)</label>
            <input type="text" name="lokasi_cabang" value="{{ $cabang->lokasi_cabang }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="-7.1234, 108.1234">
        </div>

        <!-- Radius -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Radius (Meter)</label>
            <input type="text" name="radius_cabang" value="{{ $cabang->radius_cabang }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="50">
        </div>

        <!-- Regional -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Regional</label>
            <select name="kode_regional" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="">Pilih Regional</option>
                @foreach ($regional as $r)
                    <option value="{{ $r->kode_regional }}" {{ $cabang->kode_regional == $r->kode_regional ? 'selected' : '' }}>{{ $r->nama_regional }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kode PT -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kode PT</label>
            <input type="text" name="kode_pt" value="{{ $cabang->kode_pt }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Kode PT">
        </div>

        <!-- Nama PT -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Nama PT</label>
            <input type="text" name="nama_pt" value="{{ $cabang->nama_pt }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Nama PT">
        </div>

        <!-- Domain -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Domain</label>
            <input type="text" name="domain" value="{{ $cabang->domain }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="domain.com">
        </div>

        <!-- Urutan -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Urutan</label>
            <input type="number" name="urutan" value="{{ $cabang->urutan }}" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="1">
        </div>

        <!-- Color Marker -->
        <div class="relative md:col-span-2">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Warna Marker Peta</label>
            <div class="flex items-center gap-2 p-2 border border-slate-300 rounded-lg mt-1">
                <input type="color" name="color_marker" value="{{ $cabang->color_marker }}" class="h-8 w-16 p-0.5 bg-white border border-slate-300 rounded cursor-pointer">
                <span class="text-xs text-slate-500">Pilih warna untuk marker di peta.</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end pt-3 border-t border-slate-100 mt-3 gap-2">
        <button type="button" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors" onclick="closeTailwindModal()">Batal</button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-sm shadow-blue-200 transition-colors flex items-center gap-2">
            <i class="fas fa-save"></i>
            <span>Update</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        // Validation Logic for Edit Form
        $("#formeditCabang").submit(function(e) {
            let isValid = true;

            // Reset all previous errors inside this form
            $("#formeditCabang .error-message").remove();
            $("#formeditCabang input, #formeditCabang textarea, #formeditCabang select").removeClass("!border-red-500 invalid-border").addClass("border-slate-300");

            // Helper function to show error
            function showError(field, message) {
                $(field).removeClass("border-slate-300").addClass("!border-red-500 invalid-border");
                // Append error INSIDE the relative container
                let wrapper = $(field).closest('.relative');
                if (wrapper.find('.error-message').length === 0) {
                     wrapper.append(`<p class="text-red-500 text-[10px] mt-1 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                isValid = false;
            }

            // Kode Cabang is Readonly, usually doesn't need validation on edit, but if manipulated:
            let kode_cabang = $("#formeditCabang input[name='kode_cabang']");
            if (kode_cabang.val() == "") {
                showError(kode_cabang, "Kode Cabang error");
            }

            // Validate Nama Cabang
            let nama_cabang = $("#formeditCabang input[name='nama_cabang']");
            if (nama_cabang.val() == "") {
                showError(nama_cabang, "Nama Cabang harus diisi");
            }

            // Validate Alamat Cabang
            let alamat_cabang = $("#formeditCabang textarea[name='alamat_cabang']");
            if (alamat_cabang.val() == "") {
                showError(alamat_cabang, "Alamat Cabang harus diisi");
            }

            // Validate Telepon
            let telepon_cabang = $("#formeditCabang input[name='telepon_cabang']");
            if (telepon_cabang.val() == "") {
                showError(telepon_cabang, "Telepon harus diisi");
            } else if (isNaN(telepon_cabang.val())) {
                showError(telepon_cabang, "Telepon harus berupa angka");
            } else if (telepon_cabang.val().length > 13) {
                showError(telepon_cabang, "Telepon maksimal 13 angka");
            }

            // Validate Lokasi
            let lokasi_cabang = $("#formeditCabang input[name='lokasi_cabang']");
            if (lokasi_cabang.val() == "") {
                showError(lokasi_cabang, "Lokasi harus diisi");
            }

            // Validate Radius
            let radius_cabang = $("#formeditCabang input[name='radius_cabang']");
            if (radius_cabang.val() == "") {
                showError(radius_cabang, "Radius harus diisi");
            }

            // Validate Regional
            let kode_regional = $("#formeditCabang select[name='kode_regional']");
            if (kode_regional.val() == "") {
                showError(kode_regional, "Silahkan pilih regional");
            }

            // Validate Kode PT
            let kode_pt = $("#formeditCabang input[name='kode_pt']");
            if (kode_pt.val() == "") {
                showError(kode_pt, "Kode PT harus diisi");
            } else if (kode_pt.val().length != 3) {
                showError(kode_pt, "Kode PT harus 3 karakter");
            }

            // Validate Nama PT
            let nama_pt = $("#formeditCabang input[name='nama_pt']");
            if (nama_pt.val() == "") {
                showError(nama_pt, "Nama PT harus diisi");
            }

            // Validate Urutan
            let urutan = $("#formeditCabang input[name='urutan']");
            if (urutan.val() == "") {
                showError(urutan, "Urutan harus diisi");
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Remove Error on Input (Scoped to this form only)
        $("#formeditCabang input, #formeditCabang textarea, #formeditCabang select").on('input change', function() {
            $(this).removeClass("!border-red-500 invalid-border").addClass("border-slate-300");
             // Remove the error message INSIDE the wrapper
             $(this).closest('.relative').find('.error-message').remove();
        });
    });
</script>
