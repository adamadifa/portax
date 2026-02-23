<form action="{{ route('reject.store') }}" method="POST" id="formReject" autocomplete="off" aria-autocomplete="none" class="flex flex-col h-full">
    @csrf
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-[#003d9e] to-blue-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-plus-circle text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">Tambah Reject Gudang Cabang</h3>
                <p class="text-blue-200 text-xs">Form input data mutasi reject produk</p>
            </div>
        </div>
        <button type="button" onclick="window.closeTailwindModal()" class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <!-- Info Cards / Form Inputs Group -->
    <div class="p-6 pb-2">
        <div class="flex flex-col gap-y-4 mb-6 relative z-20">
            <!-- Tanggal -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Tanggal <span class="text-red-500">*</span></label>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-calendar-alt text-slate-400"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" class="flatpickr-date w-full pl-10 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 transition-all font-medium" placeholder="Pilih Tanggal">
            </div>

            <!-- Cabang (Conditional) -->
            @hasanyrole($roles_show_cabang)
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Pilih Cabang <span class="text-red-500">*</span></label>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="fas fa-building text-slate-400"></i>
                </div>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang" class="select2Kodecabang w-full pl-10 pr-8 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none cursor-pointer">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
            @endrole

            <!-- Jenis Mutasi -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Jenis Mutasi <span class="text-red-500">*</span></label>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="fas fa-exchange-alt text-slate-400"></i>
                </div>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                </div>
                <select name="jenis_mutasi" id="jenis_mutasi" class="select2Jenismutasi w-full pl-10 pr-8 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors appearance-none cursor-pointer">
                    <option value="">Jenis Mutasi</option>
                    @foreach ($jenis_mutasi as $jm)
                        <option value="{{ $jm->kode_jenis_mutasi }}">{{ strtoupper($jm->jenis_mutasi) }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Keterangan -->
            <div class="relative">
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-600 z-10">Keterangan</label>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-file-alt text-slate-400"></i>
                </div>
                <input type="text" name="keterangan" id="keterangan" class="w-full pl-10 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003d9e]/20 focus:border-[#003d9e] text-sm text-slate-700 transition-all font-medium" placeholder="Masukkan keterangan tambahan (opsional)">
            </div>
        </div>

        <!-- Detail Table -->
        <div class="border border-slate-200 rounded-lg overflow-hidden relative z-10">
            <div class="overflow-y-auto max-h-[400px] custom-scrollbar overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-20">
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                            <th class="px-3 py-3 w-20 bg-slate-50">Kode</th>
                            <th class="px-3 py-3 w-1/3 bg-slate-50 min-w-[200px]">Produk</th>
                            <th class="px-3 py-3 text-center border-l border-slate-200 bg-slate-50" colspan="3">Kuantitas</th>
                        </tr>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-semibold sticky top-[41px] z-10">
                            <th class="px-3 py-2 bg-slate-50"></th>
                            <th class="px-3 py-2 bg-slate-50"></th>
                            <th class="px-3 py-2 text-center border-l border-slate-200 bg-slate-50 w-24">Dus</th>
                            <th class="px-3 py-2 text-center border-l border-slate-200 bg-slate-50 w-24">Pack</th>
                            <th class="px-3 py-2 text-center border-l border-slate-200 bg-slate-50 w-24">Pcs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($produk as $d)
                            @php
                                $hasPack = !empty($d->isi_pcs_pack);
                                $packDisabledClass = !$hasPack ? 'bg-slate-100/70 text-slate-400 cursor-not-allowed' : 'bg-transparent text-slate-700 focus:bg-white focus:ring-1 focus:ring-[#003d9e]/50';
                                $packReadOnlyAttr = !$hasPack ? 'readonly text-transparent' : '';
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-3 py-2">
                                    <input type="hidden" class="kode_produk" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                    <input type="hidden" class="isi_pcs_dus" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                    <input type="hidden" class="isi_pcs_pack" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono font-medium border border-slate-200">{{ $d->kode_produk }}</span>
                                </td>
                                <td class="px-3 py-2 text-sm text-slate-700 font-medium whitespace-nowrap">
                                    {{ $d->nama_produk }}
                                </td>
                                <td class="p-1 border-l border-slate-100">
                                    <input type="text" class="w-full text-right px-2 py-1.5 text-sm font-medium bg-transparent border-0 focus:ring-1 focus:ring-[#003d9e]/50 focus:bg-white rounded transition-colors money jml_dus placeholder:text-slate-300" name="jml_dus[]" placeholder="0">
                                </td>
                                <td class="p-1 border-l border-slate-100 {{ !$hasPack ? 'bg-slate-50/80' : '' }}">
                                    <input type="text" class="w-full text-right px-2 py-1.5 text-sm font-medium border-0 rounded transition-colors money jml_pack placeholder:text-slate-300 {{ $packDisabledClass }}" name="jml_pack[]" placeholder="-" {{ $packReadOnlyAttr }}>
                                </td>
                                <td class="p-1 border-l border-slate-100">
                                    <input type="text" class="w-full text-right px-2 py-1.5 text-sm font-medium bg-transparent border-0 focus:ring-1 focus:ring-[#003d9e]/50 focus:bg-white rounded transition-colors money jml_pcs placeholder:text-slate-300" name="jml_pcs[]" placeholder="0">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2 mt-auto">
        <button type="button" onclick="window.closeTailwindModal()" class="px-4 py-2 bg-slate-100 border border-slate-200 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
            <i class="fas fa-times"></i> Batal
        </button>
        <button type="submit" class="bg-[#003d9e] hover:bg-blue-800 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm shadow-blue-200 flex items-center gap-2" id="btnSubmit">
            <i class="fas fa-paper-plane text-xs"></i>
            <span>Simpan Data</span>
        </button>
    </div>
</form>
<script>
   $(function() {
      const form = $("#formReject");
      $(".money").maskMoney();
      $(".flatpickr-date").flatpickr({
         enable: [{
            from: "{{ $start_periode }}",
            to: "{{ $end_periode }}"
         }, ]
      });



      $(document).on('submit', '#formReject', function(e) {

         e.stopImmediatePropagation();
         const tanggal = $(this).find("#tanggal").val();
         const jenis_mutasi = $(this).find("#jenis_mutasi").val();
         const keterangan = $(this).find("#keterangan").val();
         const kode_cabang = $(this).find("#kode_cabang").val();
         if (tanggal == "") {
            Swal.fire({
               title: "Oops!",
               text: "Tanggal Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $(this).find("#tanggal").focus();
               },
            });
            return false;
         } else if (jenis_mutasi == "") {
            Swal.fire({
               title: "Oops!",
               text: "Jenis Mutasi Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $(this).find("#jenis_mutasi").focus();
               },

            });
            return false;
         } else if (kode_cabang == "") {
            Swal.fire({
               title: "Oops!",
               text: "Cabang Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $(this).find("#kode_cabang").focus();
               },

            });
            return false;
         } else {
            $(this).find("#btnSubmit").prop('disabled', true);
         }

      });
   });
</script>
