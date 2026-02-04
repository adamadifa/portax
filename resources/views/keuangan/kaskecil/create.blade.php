<form action="{{ route('kaskecil.store') }}" method="POST" id="formKaskecil">
    <input type="hidden" id="cektutuplaporan">
    @csrf

    <style>
        .select2-container .select2-selection--single {
            height: 46px !important;
            padding: 10px 12px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            background-color: #fff !important;
            position: relative;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0 !important;
            color: #1e293b !important;
            font-size: 0.875rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            top: 1px !important;
            right: 8px !important;
        }
    </style>

    <!-- Header Section -->
    @hasanyrole($roles_show_cabang)
    <div class="mb-4 relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Cabang <span class="text-red-500">*</span></label>
        <select name="kode_cabang" id="kode_cabang" class="select2Kodecabang w-full">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $d)
                <option value="{{ $d->kode_cabang }}">{{ textuppercase($d->nama_cabang) }}</option>
            @endforeach
        </select>
    </div>
    @endhasanyrole

    <div class="mb-4 relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">No. Bukti <span class="text-red-500">*</span></label>
        <input type="text" name="no_bukti" id="no_bukti" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Contoh: BKK-001">
    </div>

    <div class="mb-4 relative">
        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Tanggal <span class="text-red-500">*</span></label>
        <input type="text" name="tanggal" id="tanggal" class="flatpickr-date w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Pilih Tanggal">
    </div>
    
    <hr class="border-slate-200 my-5">

    <!-- Input Item Section -->
    <!-- Input Item Section -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end mb-4">
        <!-- Akun (Col 5) -->
         <div class="md:col-span-5 relative">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Akun <span class="text-red-500">*</span></label>
             <select id="kode_akun" class="select2Kodeakun w-full">
                <option value="">Pilih Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }} </option>
                @endforeach
            </select>
        </div>

        <!-- Jumlah (Col 4) -->
         <div class="md:col-span-4 relative">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Jumlah <span class="text-red-500">*</span></label>
            <input type="text" id="jumlah" class="text-right w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors font-medium" placeholder="0">
        </div>

        <!-- Debet / Kredit (Col 3) -->
         <div class="md:col-span-3 relative">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Posisi <span class="text-red-500">*</span></label>
             <select id="debet_kredit" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none">
                <option value="D" selected>DEBET</option>
                <option value="K">KREDIT</option>
            </select>
        </div>

        <!-- Keterangan (Full Width) -->
        <div class="md:col-span-12 relative">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Keterangan <span class="text-red-500">*</span></label>
             <input type="text" id="keterangan" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors" placeholder="Keterangan transaksi...">
        </div>
    </div>
    
    <!-- Conditional Peruntukan (PST Only) -->
    @if (auth()->user()->kode_cabang == 'PST')
        <div class="mb-5 relative p-4 border border-dashed border-slate-300 rounded-lg">
             <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-black z-10">Peruntukan</label>
             <div class="flex flex-col md:flex-row gap-6">
                <label class="inline-flex items-center gap-2 cursor-pointer group">
                    <input type="radio" name="kode_peruntukan" value="PC" class="form-radio text-[#003d9e] focus:ring-[#003d9e] group-hover:scale-110 transition-transform">
                    <span class="text-sm font-medium text-slate-700">Pacific</span>
                </label>
                 <label class="inline-flex items-center gap-2 cursor-pointer group">
                    <input type="radio" name="kode_peruntukan" value="MP" class="form-radio text-[#003d9e] focus:ring-[#003d9e] group-hover:scale-110 transition-transform">
                    <span class="text-sm font-medium text-slate-700">Makmur Permata</span>
                </label>
            </div>
        </div>
    @endif
    
    <!-- Button Tambah -->
    <div class="mb-6">
         <button type="button" id="tambahitem" class="w-full bg-[#003d9e] hover:bg-blue-800 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition-all shadow-sm shadow-blue-200 flex items-center justify-center gap-2 active:scale-95">
            <i class="ti ti-plus"></i> Tambah Item
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-lg border border-slate-200 shadow-sm mb-6">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-slate-800 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 font-bold">Keterangan</th>
                    <th class="px-4 py-3 font-bold">Akun</th>
                    <th class="px-4 py-3 font-bold text-right">Penerimaan</th>
                    <th class="px-4 py-3 font-bold text-right">Pengeluaran</th>
                    @if (auth()->user()->kode_cabang == 'PST')
                        <th class="px-4 py-3 font-bold text-center">Peruntukan</th>
                    @endif
                    <th class="px-4 py-3 font-bold text-center">Aksi</th>
                </tr>
            </thead>
             <tbody id="loaditem" class="bg-white divide-y divide-slate-100">
                <!-- Items will be appended here -->
             </tbody>
        </table>
    </div>
    
    <!-- Footer Agree & Submit -->
    <div class="flex flex-col gap-3">
        <div class="flex items-center gap-2">
            <input class="form-checkbox text-[#003d9e] rounded border-slate-300 focus:ring-[#003d9e] agreement" name="aggrement" value="aggrement" type="checkbox" id="defaultCheck3">
            <label class="text-sm text-slate-600 font-medium cursor-pointer select-none" for="defaultCheck3">
                Yakin Akan Disimpan ?
            </label>
        </div>

        <div id="saveButton" class="hidden">
            <button class="w-full bg-[#003d9e] hover:bg-blue-800 text-white py-3 rounded-lg font-bold text-base transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2 active:scale-95" type="submit" id="btnSimpan">
                <i class="ti ti-send"></i> Submit
            </button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        const formKaskecil = $("#formKaskecil");
        const select2Kodecabang = $('.select2Kodecabang');
        
        // Mask Money
        formKaskecil.find("#jumlah").maskMoney();
        
        // Datepicker
        $(".flatpickr-date").flatpickr();
        
        // Initialize Select2 with Tailwind-compatible container
        // We use specific CSS for height/padding so minimal config here
        if (select2Kodecabang.length) {
            select2Kodecabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                dropdownParent: select2Kodecabang.parent()
            });
        }

        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.select2({
                placeholder: 'Pilih Akun',
                allowClear: true,
                dropdownParent: select2Kodeakun.parent()
            });
        }
        
        let baris = 0;

        function resetform() {
            formKaskecil.find("#keterangan").val("");
            $('.select2Kodeakun').val('').trigger("change");
            formKaskecil.find("#jumlah").val("");
            formKaskecil.find("#keterangan").focus();
        }

        function addItem() {
            const keterangan = formKaskecil.find("#keterangan").val();
            const dataCoa = formKaskecil.find("#kode_akun :selected").select2(this.data);
            const kode_akun = $(dataCoa).val();
            const nama_akun = $(dataCoa).text();
            const debet_kredit = formKaskecil.find("#debet_kredit").val();
            const jumlah = formKaskecil.find("#jumlah").val();
            
            // Get Radio Value correctly
            const kode_peruntukan = formKaskecil.find("input[name='kode_peruntukan']:checked").val() ?? '';
            
            let penerimaan = debet_kredit == 'K' ? jumlah : '';
            let pengeluaran = debet_kredit == 'D' ? jumlah : '';
            
            if (keterangan == "") {
                Swal.fire({ title: "Oops!", text: "Keterangan Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: (e) => { formKaskecil.find("#keterangan").focus(); }, });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({ title: "Oops!", text: "Akun Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: (e) => { formKaskecil.find("#kode_akun").focus(); }, });
                return false;
            } else if (jumlah == "") {
                Swal.fire({ title: "Oops!", text: "Jumlah Harus Diisi !", icon: "warning", showConfirmButton: true, didClose: (e) => { formKaskecil.find("#jumlah").focus(); }, })
                return false;
            } else {
                baris = baris + 1;
                
                // Styling text colors
                const classPenerimaan = penerimaan ? 'text-emerald-600 font-bold' : '';
                const classPengeluaran = pengeluaran ? 'text-rose-600 font-bold' : '';

                let item = `
                    <tr id="index_${baris}" class="hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0">
                        <td class="px-4 py-3 font-medium text-slate-700">
                            <input type="hidden" name="keterangan_item[]" value="${keterangan}" />
                            <input type="hidden" name="jumlah_item[]" value="${jumlah}" />
                            <input type="hidden" name="debet_kredit_item[]" value="${debet_kredit}" />
                            <input type="hidden" name="kode_peruntukan_item[]" value="${kode_peruntukan}" />
                            ${keterangan}
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" />
                            <span class="font-mono text-xs bg-slate-100 px-1 py-0.5 rounded text-slate-500 mr-1">${kode_akun}</span>
                            ${nama_akun.split(' ').slice(1).join(' ')} 
                        </td>
                        <td class='px-4 py-3 text-right ${classPenerimaan}'>${penerimaan}</td>
                        <td class='px-4 py-3 text-right ${classPengeluaran}'>${pengeluaran}</td>
                        @if (auth()->user()->kode_cabang == 'PST')
                            <td class='px-4 py-3 text-center'>
                                ${kode_peruntukan ? `<span class="px-2 py-1 bg-indigo-100 text-indigo-700 font-bold text-xs rounded">${kode_peruntukan}</span>` : ''}
                            </td>
                        @endif
                        <td class="px-4 py-3 text-center">
                            <a href="#" id="index_${baris}" class="delete text-slate-400 hover:text-rose-500 transition-colors"><i class="ti ti-trash text-lg"></i></a>
                        </td>
                    </tr>`;
                    
                $('#loaditem').append(item);
                resetform();
            }
        }

        $("#tambahitem").click(function(e) {
            e.preventDefault();
            addItem();
        });


        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <i class="ti ti-loader fa-spin me-2"></i> Loading..`);
        }
        
        formKaskecil.on('click', '.delete', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            Swal.fire({
                title: `Hapus Item?`,
                text: "Item akan dihapus dari daftar.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#${id}`).remove();
                }
            });
        });

        // Toggle Save Button
        // Initialize hidden first (done in HTML)
        formKaskecil.find('.agreement').change(function() {
            if (this.checked) {
                formKaskecil.find("#saveButton").removeClass('hidden');
            } else {
                formKaskecil.find("#saveButton").addClass('hidden');
            }
        });

        formKaskecil.submit(function(e) {
            const kode_cabang = formKaskecil.find("#kode_cabang").val();
            const no_bukti = formKaskecil.find("#no_bukti").val();
            const tanggal = formKaskecil.find("#tanggal").val();
            const cekData = $('#loaditem tr').length;
            
            // Validation
             let isValid = true;
             
            @hasanyrole($roles_show_cabang)
                if (kode_cabang == "") {
                     Swal.fire({ title: "Oops!", text: "Cabang harus diisi !", icon: "warning", showConfirmButton: true, didClose: () => { $('.select2Kodecabang').select2('open'); }, });
                    return false;
                }
            @endhasanyrole

            if (no_bukti == "") {
                Swal.fire({ title: "Oops!", text: "No. Bukti harus diisi !", icon: "warning", showConfirmButton: true, didClose: () => { $("#no_bukti").focus(); }, });
                return false;
            } else if (tanggal == "") {
                Swal.fire({ title: "Oops!", text: "Tanggal harus diisi !", icon: "warning", showConfirmButton: true, didClose: () => { $("#tanggal").focus(); }, });
                return false;
            } else if (cekData == 0) {
                 Swal.fire({ title: "Oops!", text: "Data transaksi masih kosong !", icon: "warning", showConfirmButton: true, didClose: () => { $("#keterangan").focus(); }, });
                return false;
            }
            
            if (isValid) buttonDisable();
        })
    });
</script>
