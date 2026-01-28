<form action="{{ route('salesman.update', Crypt::encrypt($salesman->kode_salesman)) }}" id="formeditSalesman" method="POST" enctype="multipart/form-data" class="space-y-3">
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
        <h3 class="text-lg font-bold text-slate-800">Edit Data Salesman</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="space-y-4">
        <!-- Kode Salesman (Readonly) -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kode Salesman <span class="text-red-500">*</span></label>
            <input type="text" name="kode_salesman" id="kode_salesman"
                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-500 cursor-not-allowed focus:outline-none placeholder-slate-400"
                value="{{ $salesman->kode_salesman }}" readonly>
        </div>

        <!-- Nama Salesman -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Nama Salesman <span class="text-red-500">*</span></label>
            <input type="text" name="nama_salesman" id="nama_salesman"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                value="{{ $salesman->nama_salesman }}" placeholder="Nama Lengkap">
        </div>

        <!-- No HP -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. HP <span class="text-red-500">*</span></label>
            <input type="number" name="no_hp_salesman" id="no_hp_salesman"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                value="{{ $salesman->no_hp_salesman }}" placeholder="0812...">
        </div>

        <!-- Kategori Salesman -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kategori <span class="text-red-500">*</span></label>
            <select name="kode_kategori_salesman" id="kode_kategori_salesman"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="">Pilih Kategori</option>
                @foreach ($kategori_salesman as $k)
                    <option value="{{ $k->kode_kategori_salesman }}" {{ $salesman->kode_kategori_salesman == $k->kode_kategori_salesman ? 'selected' : '' }}>
                        {{ $k->nama_kategori_salesman }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status Komisi -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Dapat Komisi? <span class="text-red-500">*</span></label>
            <select name="status_komisi_salesman" id="status_komisi_salesman"
                 class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="">Pilih Status</option>
                <option value="1" {{ $salesman->status_komisi_salesman == '1' ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ $salesman->status_komisi_salesman == '0' ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <!-- Status Aktif -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Status Aktif <span class="text-red-500">*</span></label>
            <select name="status_aktif_salesman" id="status_aktif_salesman"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="">Pilih Status</option>
                <option value="1" {{ $salesman->status_aktif_salesman == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $salesman->status_aktif_salesman == '0' ? 'selected' : '' }}>Non Aktif</option>
            </select>
        </div>

        <!-- Cabang (Role Based) -->
        @hasanyrole($roles_show_cabang)
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang <span class="text-red-500">*</span></label>
            <select name="kode_cabang" id="kode_cabang_edit"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $c)
                    <option value="{{ $c->kode_cabang }}" {{ $salesman->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                        {{ $c->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </div>
        @endhasanyrole

        <!-- Alamat -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Alamat Lengkap <span class="text-red-500">*</span></label>
            <textarea name="alamat_salesman" id="alamat_salesman" rows="2"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors"
                placeholder="Alamat domisili salesman..." style="resize: none;">{{ $salesman->alamat_salesman }}</textarea>
        </div>

        <!-- Marker -->
        <div class="relative">
            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Marker (Optional)</label>
            <div class="flex items-center justify-center w-full mt-1">
                <label for="marker" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-blue-50 hover:border-blue-300 transition-all group overflow-hidden relative">
                    
                    @if (!empty($salesman->marker))
                        <!-- Initial State with Existing Image -->
                         <div id="initial-preview" class="absolute inset-0 w-full h-full flex items-center justify-center bg-slate-100 group-hover:opacity-90 transition-opacity">
                            <img src="{{ getdocMarker($salesman->marker) }}" alt="Current Marker" class="h-full object-contain p-1">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <p class="text-white text-xs font-medium"><i class="fas fa-edit mr-1"></i> Ganti Marker</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col items-center justify-center pt-3 pb-4 {{ !empty($salesman->marker) ? 'hidden' : '' }}" id="upload-placeholder">
                        <div class="p-2 bg-white rounded-full shadow-sm mb-2 group-hover:scale-110 transition-transform">
                             <i class="fas fa-cloud-upload-alt text-[#003d9e] text-lg"></i>
                        </div>
                        <p class="mb-0.5 text-xs text-slate-600 text-center"><span class="font-bold text-[#003d9e]">Klik upload</span> atau drag & drop</p>
                         <p class="text-[10px] text-slate-400">SVG, PNG, JPG (Max. 2MB)</p>
                    </div>

                    <!-- File Preview (Hidden by default) -->
                    <div id="file-preview-info" class="hidden flex-col items-center justify-center pt-3 pb-4 z-10 bg-blue-50 absolute inset-0 w-full h-full">
                        <div class="p-2 bg-blue-100 rounded-full shadow-sm mb-2">
                             <i class="fas fa-file-image text-[#003d9e] text-lg"></i>
                        </div>
                         <p class="text-xs text-slate-800 font-medium text-center truncate w-48 px-2" id="filename-display"></p>
                         <p class="text-[10px] text-[#003d9e] font-medium cursor-pointer hover:underline mt-1">Ganti File</p>
                    </div>

                    <input id="marker" name="marker" type="file" class="hidden" accept="image/*" />
                </label>
            </div>
        </div>
    </div>

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
        // Init Select2 if needed (assuming Layout/Scripts handles it, but just in case, or rely on native select styled)
        // Since we added custom CSS for Select2 at the top, if these are Select2s, they will pick it up.

        $("#formeditSalesman").submit(function(e) {
            // Remove previous errors (scoped to this form)
            $('#formeditSalesman .error-message').remove();
            $('#formeditSalesman input, #formeditSalesman select, #formeditSalesman textarea').removeClass('!border-red-500 invalid-border').addClass('border-slate-300');

            let isValid = true;

            function showError(fieldId, message) {
                const input = $(`#formeditSalesman #${fieldId}`);
                // Apply Red Border with !important
                input.removeClass("border-slate-300").addClass('!border-red-500 invalid-border');
                
                // Append error message INSIDE the parent container (div.relative)
                let wrapper = input.closest('.relative');
                if (wrapper.find('.error-message').length === 0) {
                     wrapper.append(`<p class="text-red-500 text-[10px] mt-1 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                isValid = false;
            }

            // Kode Salesman
            const kode_salesman = $("#formeditSalesman #kode_salesman");
            // Kode Salesman is readonly, but checking validity is harmless or skip

            // Nama Salesman
            const nama_salesman = $("#formeditSalesman #nama_salesman");
            if (nama_salesman.val() == "") {
                showError('nama_salesman', 'Nama Salesman harus diisi');
            }

            // No HP
            const no_hp_salesman = $("#formeditSalesman #no_hp_salesman");
            if (no_hp_salesman.val() == "") {
                showError('no_hp_salesman', 'No. HP harus diisi');
            }

            // Kategori
            const kode_kategori_salesman = $("#formeditSalesman #kode_kategori_salesman");
            if (kode_kategori_salesman.val() == "") {
                showError('kode_kategori_salesman', 'Pilih Kategori');
            }

             // Status Komisi
            const status_komisi_salesman = $("#formeditSalesman #status_komisi_salesman");
            if (status_komisi_salesman.val() == "") {
                showError('status_komisi_salesman', 'Pilih Status Komisi');
            }

            // Status Aktif
            const status_aktif_salesman = $("#formeditSalesman #status_aktif_salesman");
            if (status_aktif_salesman.val() == "") {
                showError('status_aktif_salesman', 'Pilih Status Aktif');
            }
            
            // Cabang (Only if visible aka Role Based)
            if ($("#formeditSalesman #kode_cabang_edit").length > 0) {
                 const kode_cabang = $("#formeditSalesman #kode_cabang_edit");
                 if (kode_cabang.val() == "") {
                    showError('kode_cabang_edit', 'Pilih Cabang');
                }
            }

            // Alamat
            const alamat_salesman = $("#formeditSalesman #alamat_salesman");
            if (alamat_salesman.val() == "") {
                showError('alamat_salesman', 'Alamat harus diisi');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Remove error on input change
         $('#formeditSalesman input, #formeditSalesman select, #formeditSalesman textarea').on('input change', function() {
            $(this).removeClass('!border-red-500 invalid-border').addClass("border-slate-300");
             // Remove error message INSIDE the wrapper
             $(this).closest('.relative').find('.error-message').remove();
        });

        // File Upload Preview
        $("#formeditSalesman #marker").on('change', function() {
            const file = this.files[0];
            if (file) {
                // Show File Info and Hide Initial/Placeholder
                let filename = file.name;
                $("#formeditSalesman #filename-display").text(filename);
                $("#formeditSalesman #upload-placeholder").addClass('hidden');
                $("#formeditSalesman #initial-preview").addClass('hidden'); // Hide existing image if new one selected
                $("#formeditSalesman #file-preview-info").removeClass('hidden').addClass('flex');
            } else {
                // User cancelled selection? Revert state.
                if($("#formeditSalesman #initial-preview").length) {
                     $("#formeditSalesman #initial-preview").removeClass('hidden');
                } else {
                     $("#formeditSalesman #upload-placeholder").removeClass('hidden');
                }
                 $("#formeditSalesman #file-preview-info").addClass('hidden').removeClass('flex');
            }
        });
    });
</script>
