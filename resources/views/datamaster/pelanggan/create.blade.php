<form action="{{ route('pelanggan.store') }}" id="formcreatePelanggan" method="POST" enctype="multipart/form-data" class="space-y-3">
    @csrf
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
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            top: 1px !important;
            right: 8px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important; /* slate-400 */
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            flex-grow: 1 !important;
            display: flex !important;
            align-items: center !important;
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
        .flatpickr-calendar {
            z-index: 9999 !important;
        }
    </style>

    <!-- Header -->
    <div class="border-b border-slate-200 pb-2 mb-2 flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-800">Tambah Data Pelanggan</h3>
        <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" onclick="closeTailwindModal()">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 relative">
        <!-- Vertical Divider (Desktop) -->
        <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-px bg-slate-200 -translate-x-1/2"></div>

        <!-- LEFT COLUMN -->
        <div class="space-y-4 pt-1">
            <!-- Kode (Auto) -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kode (Auto)</label>
                <input type="text" name="kode_pelanggan" disabled 
                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-500 cursor-not-allowed placeholder-slate-400" 
                    placeholder="Auto">
            </div>

            <!-- NIK -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nik" id="nik" 
                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                    placeholder="NIK">
            </div>

            <!-- No KK -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. KK</label>
                <input type="text" name="no_kk" id="no_kk" 
                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                    placeholder="No. KK">
            </div>

            <!-- Nama Pelanggan -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Nama Pelanggan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pelanggan" id="nama_pelanggan" 
                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                    placeholder="Nama Pelanggan">
            </div>

            <!-- Tanggal Lahir -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Tanggal Lahir</label>
                <input type="text" name="tanggal_lahir" id="tanggal_lahir" 
                    class="flatpickr-date w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                    placeholder="Tanggal Lahir">
            </div>

            <!-- Alamat Pelanggan -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Alamat Pelanggan <span class="text-red-500">*</span></label>
                <textarea name="alamat_pelanggan" id="alamat_pelanggan" rows="2" 
                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400 resize-none" 
                    placeholder="Alamat Pelanggan"></textarea>
            </div>

            <!-- Alamat Toko -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Alamat Toko</label>
                <textarea name="alamat_toko" id="alamat_toko" rows="2" 
                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400 resize-none" 
                    placeholder="Alamat Toko"></textarea>
            </div>

            <!-- No HP -->
            <div class="flex gap-2 items-start">
                <div class="relative flex-1">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. HP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" 
                        class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors placeholder-slate-400" 
                        placeholder="No. HP">
                </div>
                <label class="flex items-center gap-2 cursor-pointer h-[40px] px-2 rounded-lg border border-transparent hover:bg-slate-50 transition-colors">
                    <input type="checkbox" class="na_nohp form-checkbox h-4 w-4 text-[#003d9e] rounded border-slate-300 focus:ring-[#003d9e]">
                    <span class="text-xs font-medium text-slate-500">NA</span>
                </label>
            </div>

            <!-- Cabang -->
            @hasanyrole($roles_show_cabang)
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang <span class="text-red-500">*</span></label>
                <select name="kode_cabang" id="kode_cabang" class="select2Kodecabang w-full text-left" data-placeholder="Cabang">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>
            @endhasanyrole

            <!-- Salesman -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Salesman <span class="text-red-500">*</span></label>
                <select name="kode_salesman" id="kode_salesman" class="select2Kodesalesman w-full text-left" data-placeholder="Salesman"></select>
            </div>
            
            <!-- Wilayah -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Wilayah</label>
                <select name="kode_wilayah" id="kode_wilayah" class="select2Kodewilayah w-full text-left" data-placeholder="Wilayah"></select>
            </div>

             <!-- Hari Kunjungan -->
             <div>
                <label class="block text-[10px] font-bold text-black mb-1 ml-1 uppercase">Hari Kunjungan</label>
                <div class="grid grid-cols-4 sm:grid-cols-7 gap-1.5">
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                        <label class="cursor-pointer group">
                            <input type="checkbox" name="hari[]" value="{{ $hari }}" class="hari-check peer hidden">
                            <span class="block text-center text-[10px] py-2 px-0.5 rounded border border-slate-200 bg-white text-slate-500 peer-checked:!bg-[#003d9e] peer-checked:!text-white peer-checked:font-bold peer-checked:!border-[#003d9e] transition-all select-none group-hover:border-blue-300">
                                {{ substr($hari, 0, 3) }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

             <!-- Limit & LJT -->
             <div class="grid grid-cols-2 gap-3">
                 @hasanyrole($roles_show_cabang)
                 <div class="relative">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Limit Kredit</label>
                    <input type="text" name="limit_pelanggan" id="limit_pelanggan" class="money w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-right focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] placeholder-slate-400" placeholder="Limit Pelanggan">
                </div>
                 @endhasanyrole
                 
                 <div class="relative">
                     <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">LJT (Hari)</label>
                     <select name="ljt" id="ljt" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] text-slate-600">
                        <option value="">LJT (Hari)</option>
                        <option value="14">14 Hari</option>
                        <option value="30">30 Hari</option>
                        <option value="45">45 Hari</option>
                    </select>
                </div>
            </div>
            
             <!-- Status Aktif -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Status Pelanggan <span class="text-red-500">*</span></label>
                <select name="status_aktif_pelanggan" id="status_aktif_pelanggan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] text-slate-600">
                    <option value="" disabled>Status</option>
                    <option value="1" selected>Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-4 pt-1">
            <!-- Alert Info -->
            <div class="bg-orange-50 border border-orange-100 rounded-lg p-2.5 flex items-start gap-2.5">
                <div class="bg-orange-100 text-orange-500 rounded p-1 flex-shrink-0">
                     <i class="fas fa-bell text-xs"></i>
                </div>
                <div>
                     <p class="text-[10px] font-bold text-orange-800 leading-tight pt-0.5">Bisa Diisi Saat Melakukan Ajuan Limit Kredit !</p>
                </div>
            </div>

            <!-- Form Groups Right -->
             <div class="relative">
                 <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Kepemilikan</label>
                 <select name="kepemilikan" id="kepemilikan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-600">
                    <option value="">Kepemilikan</option>
                    <option value="SW">Sewa</option>
                    <option value="MS">Milik Sendiri</option>
                </select>
            </div>

             <div class="relative">
                 <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Lama Usaha</label>
                 <select name="lama_berjualan" id="lama_berjualan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-600">
                    <option value="">Lama Usaha</option>
                    <option value="LU01">< 2 Thn</option>
                    <option value="LU02">2-5 Thn</option>
                    <option value="LU03">> 5 Thn</option>
                </select>
            </div>
            
            <div class="relative">
                 <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Status Outlet</label>
                 <select name="status_outlet" id="status_outlet" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-600">
                    <option value="">Status Outlet</option>
                    <option value="NO">New Outlet</option>
                    <option value="EO">Existing Outlet</option>
                </select>
            </div>

            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Klasifikasi Outlet</label>
                <select name="kode_klasifikasi" id="kode_klasifikasi" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-600">
                    <option value="">Klasifikasi Outlet</option>
                    @foreach ($klasifikasi_outlet as $k)
                        <option value="{{ $k->kode_klasifikasi }}">{{ $k->klasifikasi }}</option>
                    @endforeach
                </select>
            </div>

             <div class="relative">
                 <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Type Outlet</label>
                 <select name="type_outlet" id="type_outlet" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-600">
                    <option value="">Type Outlet</option>
                    <option value="GR">Grosir</option>
                    <option value="RT">Retail</option>
                </select>
            </div>

             <div class="relative">
                 <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cara Pembayaran</label>
                 <select name="cara_pembayaran" id="cara_pembayaran" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-600">
                    <option value="">Cara Pembayaran</option>
                    <option value="BT">Transfer</option>
                    <option value="AC">Cash</option>
                    <option value="CQ">Cheque</option>
                </select>
            </div>

            <!-- Lama Langganan -->
            <div class="relative">
                 <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Lama Langganan</label>
                 <select name="lama_langganan" id="lama_langganan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-600">
                    <option value="">Lama Langganan</option>
                    @foreach ($lama_langganan as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jaminan -->
            <div class="relative">
                 <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jaminan</label>
                 <select name="jaminan" id="jaminan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-600">
                    <option value="">Jaminan</option>
                    <option value="1">Ada</option>
                    <option value="0">Tidak Ada</option>
                </select>
            </div>

             <!-- Koord -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Titik Koordinat</label>
                <input type="text" name="lokasi" id="lokasi" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400" placeholder="Titik Koordinat">
            </div>

            <!-- Omset -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Omset Toko</label>
                <input type="text" name="omset_toko" id="omset_toko" class="money w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-right placeholder-slate-400" placeholder="Omset Toko">
            </div>

            <!-- File Uploads -->
            <div class="grid grid-cols-2 gap-3 pt-2">
                <!-- Foto Toko -->
                <div class="relative">
                    <label for="foto" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-300 rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors overflow-hidden relative">
                        <!-- Placeholder -->
                        <div id="upload-placeholder-foto" class="flex flex-col items-center justify-center text-center p-4 transition-opacity duration-300">
                             <div class="w-10 h-10 bg-blue-50 text-[#003d9e] rounded-full flex items-center justify-center mb-2 shadow-sm">
                                <i class="fas fa-store text-lg"></i>
                            </div>
                            <p class="text-[10px] font-bold text-slate-600 uppercase">Foto Toko</p>
                            <p class="text-[9px] text-slate-400">Klik/Drop file</p>
                        </div>
                        
                        <!-- Preview Image -->
                        <div id="file-preview-foto" class="hidden absolute inset-0 w-full h-full flex items-center justify-center bg-white">
                             <img src="" class="w-full h-full object-cover">
                             <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                 <span class="text-white text-xs font-medium"><i class="fas fa-sync-alt mr-1"></i> Ganti Foto</span>
                             </div>
                        </div>
                        
                        <input type="file" name="foto" id="foto" class="hidden" accept="image/*">
                    </label>
                </div>

                <!-- Foto Owner -->
                <div class="relative">
                    <label for="foto_owner" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-300 rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors overflow-hidden relative">
                        <!-- Placeholder -->
                        <div id="upload-placeholder-owner" class="flex flex-col items-center justify-center text-center p-4 transition-opacity duration-300">
                             <div class="w-10 h-10 bg-blue-50 text-[#003d9e] rounded-full flex items-center justify-center mb-2 shadow-sm">
                                <i class="fas fa-user-tie text-lg"></i>
                            </div>
                            <p class="text-[10px] font-bold text-slate-600 uppercase">Foto Owner</p>
                            <p class="text-[9px] text-slate-400">Klik/Drop file</p>
                        </div>
                        
                        <!-- Preview Image -->
                         <div id="file-preview-owner" class="hidden absolute inset-0 w-full h-full flex items-center justify-center bg-white">
                             <img src="" class="w-full h-full object-cover">
                              <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                 <span class="text-white text-xs font-medium"><i class="fas fa-sync-alt mr-1"></i> Ganti Foto</span>
                             </div>
                        </div>

                        <input type="file" name="foto_owner" id="foto_owner" class="hidden" accept="image/*">
                    </label>
                </div>
            </div>

        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end pt-4 border-t border-slate-100 mt-4 gap-3 sticky bottom-0 bg-white pb-1 backdrop-blur-sm">
        <button type="button" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors" onclick="closeTailwindModal()">Batal</button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#003d9e] hover:bg-blue-800 rounded-lg shadow-sm shadow-blue-200 transition-colors flex items-center gap-2">
            <i class="fas fa-save"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        $(".money").maskMoney();
        
        // Scope Flatpickr to this form only
        $("#formcreatePelanggan .flatpickr-date").flatpickr({
            allowInput: true
        });
        
        // Select2 Initialization with form scoping
        $('#formcreatePelanggan .select2Kodecabang, #formcreatePelanggan .select2Kodesalesman, #formcreatePelanggan .select2Kodewilayah').select2({
            dropdownParent: $('#tailwindModal'),
            width: '100%',
            placeholder: 'Pilih',
            allowClear: true
        });

        // NA Checkbox Logic
        $('#formcreatePelanggan .na_nohp').change(function() {
            const hpInput = $("#formcreatePelanggan #no_hp_pelanggan");
            if (this.checked) {
                hpInput.val("NA").attr("readonly", true).addClass('bg-slate-100 cursor-not-allowed').removeClass('bg-white');
            } else {
                 if(hpInput.val() == "NA") hpInput.val("");
                 hpInput.attr("readonly", false).removeClass('bg-slate-100 cursor-not-allowed').addClass('bg-white');
            }
        });

        // Limit Hari Selection
        $('#formcreatePelanggan .hari-check').on('change', function() {
            var checkedCount = $('#formcreatePelanggan .hari-check:checked').length;
             if (checkedCount > 2) {
                $(this).prop('checked', false);
                 Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Maksimal 2 hari kunjungan', timer: 1500, showConfirmButton: false });
            }
        });

        // Cascading Dropdowns
        function getsalesmanbyCabang() {
            var kode_cabang = $("#formcreatePelanggan #kode_cabang").val();
            $.ajax({
                type: 'POST', url: '/salesman/getsalesmanbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kode_cabang },
                success: function(respond) { $("#formcreatePelanggan #kode_salesman").html(respond); }
            });
        }

        function getwilayahbyCabang() {
            var kode_cabang = $("#formcreatePelanggan #kode_cabang").val();
            $.ajax({
                type: 'POST', url: '/wilayah/getwilayahbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kode_cabang },
                success: function(respond) { $("#formcreatePelanggan #kode_wilayah").html(respond); }
            });
        }
        
        $("#formcreatePelanggan #kode_cabang").change(function() {
            getsalesmanbyCabang();
            getwilayahbyCabang();
        });

        // File Presentation Logic
        function handleFilePreview(inputId, placeholderId, previewId) {
            $(inputId).on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                         $(previewId).find('img').attr('src', e.target.result);
                         $(placeholderId).addClass('hidden');
                         $(previewId).removeClass('hidden').addClass('flex');
                    }
                    reader.readAsDataURL(file);
                } else {
                    $(placeholderId).removeClass('hidden');
                     $(previewId).addClass('hidden').removeClass('flex');
                }
            });
        }
        handleFilePreview("#formcreatePelanggan #foto", "#upload-placeholder-foto", "#file-preview-foto");
        handleFilePreview("#formcreatePelanggan #foto_owner", "#upload-placeholder-owner", "#file-preview-owner");

        // Validation
        $("#formcreatePelanggan").submit(function(e) {
            // Remove error styling
            $('#formcreatePelanggan .error-message').remove();
            $('#formcreatePelanggan input, #formcreatePelanggan select, #formcreatePelanggan textarea').removeClass('!border-red-500 invalid-border').addClass('border-slate-300');
            
            let isValid = true;
            const form = $(this);
            
            function showError(fieldId, message) {
                const input = form.find(`#${fieldId}`);
                if (input.length == 0) return; // safety

                input.removeClass('border-slate-300').addClass('!border-red-500 invalid-border');
                
                let wrapper = input.closest('.relative');
                if (wrapper.length === 0) wrapper = input.parent();

                // Special handling for No HP to appear below the flex row
                if (fieldId === 'no_hp_pelanggan') {
                     wrapper = input.closest('.flex');
                }
                
                if (wrapper.next('.error-message').length === 0) {
                    wrapper.after(`<p class="text-red-500 text-[10px] mt-0 error-message"><i class="fas fa-exclamation-circle"></i> ${message}</p>`);
                }
                isValid = false;
            }

            if (form.find("#nik").val() == "") showError('nik', 'NIK wajib diisi');
            if (form.find("#nama_pelanggan").val() == "") showError('nama_pelanggan', 'Nama wajib diisi');
            if (form.find("#alamat_pelanggan").val() == "") showError('alamat_pelanggan', 'Alamat wajib diisi');
            if (form.find("#no_hp_pelanggan").val() == "") showError('no_hp_pelanggan', 'No HP wajib diisi');
            
            // Allow role-based skip for cabang if not present
            const cabangInput = form.find("#kode_cabang");
            if (cabangInput.length > 0 && cabangInput.val() == "") {
                showError('kode_cabang', 'Pilih Cabang');
                cabangInput.next('.select2-container').find('.select2-selection').addClass('!border-red-500');
            }

             const salesmanInput = form.find("#kode_salesman");
             if (salesmanInput.length > 0 && salesmanInput.val() == "") {
                 showError('kode_salesman', 'Pilih Salesman');
                 salesmanInput.next('.select2-container').find('.select2-selection').addClass('!border-red-500');
             }

             if (!isValid) e.preventDefault();
        });
        
        // Remove error on input logic
         $('#formcreatePelanggan input, #formcreatePelanggan select, #formcreatePelanggan textarea').on('input change', function() {
            $(this).removeClass('!border-red-500 invalid-border').addClass('border-slate-300');
            $(this).closest('.relative').next('.error-message').remove();
             // Clean Select2
             if($(this).hasClass('select2-hidden-accessible')) {
                 $(this).next('.select2-container').find('.select2-selection').removeClass('!border-red-500');
             }
        });
        
         // Specific Listener for Select2 change to remove error
        $('#formcreatePelanggan .select2Kodecabang, #formcreatePelanggan .select2Kodesalesman').on('select2:select', function (e) {
             $(this).next('.select2-container').find('.select2-selection').removeClass('!border-red-500');
             $(this).closest('.relative').next('.error-message').remove();
        });
    });
</script>
