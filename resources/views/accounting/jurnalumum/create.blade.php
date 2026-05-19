<style>
    /* Styling for Select2 to match standard form-control in modal */
    .select2-container--default .select2-selection--single {
        border: 1px solid #d9dee3 !important;
        border-radius: 0.375rem !important;
        height: 38.2px !important;
        display: flex;
        align-items: center;
        background-color: #fff;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        right: 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #697a8d !important;
        padding-left: 0.875rem !important;
        padding-right: 2rem !important;
        line-height: normal !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #a1acb8 !important;
    }
    .select2-search__field {
        outline: none !important;
    }
</style>

<form action="{{ route('jurnalumum.store') }}" method="POST" id="formJurnalumum">
    @csrf
    
    <!-- Input Fields Section -->
    <div class="row">
        <!-- Tanggal Row -->
        <div class="col-12">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
        </div>
        
        <!-- Akun & Jumlah Row -->
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
                    <option value="">Pilih Kode Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Jumlah" name="jumlah" align="right" numberFormat="true" icon="ti ti-moneybag" />
        </div>

        <!-- Keterangan Row -->
        <div class="col-12">
            <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
        </div>

        <!-- Debet/Kredit & Cabang Row -->
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <select name="debet_kredit" id="debet_kredit" class="form-select">
                    <option value="">Debet / Kredit</option>
                    <option value="D">Debet</option>
                    <option value="K">Kredit</option>
                </select>
            </div>
        </div>
        
        @if (auth()->user()->kode_cabang == 'PST' || empty(auth()->user()->kode_cabang))
            <div class="col-lg-6 col-md-12 col-sm-12" id="cabang">
                <div class="form-group mb-3">
                    <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{ auth()->user()->kode_cabang }}">
        @endif
        
        <input type="hidden" name="kode_peruntukan" id="kode_peruntukan" value="PC">

        <!-- Add Button -->
        <div class="col-12 mt-1">
            <button class="bg-[#003d9e] hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg w-full flex items-center justify-center gap-2 transition-all active:scale-95 text-sm" id="btnTambahItem">
                <i class="ti ti-plus"></i>
                Tambah Item
            </button>
        </div>
    </div>

    <!-- Full Width Table Section (Bottom) -->
    <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/30 w-full mt-4">
        <div class="px-4 py-3 bg-slate-100/80 border-b border-slate-200">
            <span class="text-sm font-bold text-slate-800 tracking-wide">Daftar Item Jurnal</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2">Akun</th>
                        <th class="px-3 py-2">Keterangan</th>
                        <th class="px-3 py-2 text-right">Debet</th>
                        <th class="px-3 py-2 text-right">Kredit</th>
                        <th class="px-3 py-2">Cabang</th>
                        <th class="px-3 py-2 text-right">#</th>
                    </tr>
                </thead>
                <tbody id="loadjurnalumum" class="divide-y divide-slate-200 bg-white">
                </tbody>
                <tfoot class="bg-slate-50 font-bold text-slate-700 border-t border-slate-200">
                    <tr>
                        <td colspan="3" class="px-3 py-2 text-right">TOTAL</td>
                        <td class="px-3 py-2 text-right text-emerald-600 font-bold" id="total_debet">-</td>
                        <td class="px-3 py-2 text-right text-red-600 font-bold" id="total_kredit">-</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Agreement & Submit -->
    <div class="pt-4 border-t border-slate-200 flex flex-col items-center md:items-start gap-3 mt-4">
        <div class="flex items-center gap-2">
            <input class="w-4 h-4 text-[#003d9e] border-slate-300 rounded focus:ring-[#003d9e] agreement" name="aggrement" value="aggrement" type="checkbox" id="defaultCheck3">
            <label class="text-sm font-medium text-slate-700" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
        </div>
        <div class="w-full" id="saveButton">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg w-full flex items-center justify-center gap-2 transition-all active:scale-95 text-sm" type="submit" id="btnSimpan">
                <i class="ti ti-send text-base"></i>
                Submit Jurnal
            </button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        let total_debet_set = 0;
        let total_kredit_set = 0;
        let baris = 0;
        const form = $('#formJurnalumum');
        $(".flatpickr-date").flatpickr();

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Kode Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                })
            })
        }

        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? '.' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? ',' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function calculateTotal() {
            let total_debet = 0;
            let total_kredit = 0;
            form.find("tbody tr").each(function() {
                const debet = $(this).find(".jmldebet").text().replace(/\./g, '') || 0;
                const kredit = $(this).find(".jmlkredit").text().replace(/\./g, '') || 0;
                total_debet += parseInt(debet);
                total_kredit += parseInt(kredit);
            });
            total_debet_set = total_debet;
            total_kredit_set = total_kredit;
            form.find("#total_debet").text(numberFormat(total_debet_set, 0, ',', '.'));
            form.find("#total_kredit").text(numberFormat(total_kredit_set, 0, ',', '.'));
        }

        $("#btnTambahItem").click(function(e) {
            e.preventDefault();
            const tanggal = form.find("#tanggal").val();
            const kode_akun = form.find("#kode_akun").val();
            const jumlah = form.find("#jumlah").val();
            const keterangan = form.find("#keterangan").val();
            const debet_kredit = form.find("#debet_kredit").val();
            const kode_peruntukan = form.find("#kode_peruntukan").val();
            const kode_cabang = form.find("#kode_cabang").val();
            const kredit = debet_kredit == 'K' ? jumlah : '';
            const debet = debet_kredit == 'D' ? jumlah : '';
            const dataCoa = form.find("#kode_akun :selected").select2(this.data);
            const nama_akun = $(dataCoa).text();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_akun").focus();
                    },
                });
                return false;
            } else if (jumlah == "" || jumlah == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else if (debet_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Debet/Kredit harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#debet_kredit").focus();
                    },
                });
                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else {
                const editId = form.find("#btnTambahItem").data('edit-id');

                if (editId) {
                    // Update row yang sudah ada
                    const row = form.find(`#${editId}`);
                    row.find('input[name="tanggal_item[]"]').val(tanggal);
                    row.find('input[name="kode_akun_item[]"]').val(kode_akun);
                    row.find('input[name="debet_kredit_item[]"]').val(debet_kredit);
                    row.find('input[name="jumlah_item[]"]').val(jumlah);
                    row.find('input[name="keterangan_item[]"]').val(keterangan);
                    row.find('input[name="kode_peruntukan_item[]"]').val(kode_peruntukan);
                    row.find('input[name="kode_cabang_item[]"]').val(kode_cabang);

                    // Update tampilan
                    row.find('td:eq(0)').text(tanggal);
                    row.find('td:eq(1)').text(nama_akun);
                    row.find('td:eq(2)').text(keterangan);
                    row.find('td:eq(3)').removeClass('jmldebet').addClass('text-end jmldebet').text(debet);
                    row.find('td:eq(4)').removeClass('jmlkredit').addClass('text-end jmlkredit').text(kredit);
                    row.find('td:eq(5)').text(kode_cabang);

                    // Reset mode edit
                    form.find("#btnTambahItem").removeData('edit-id');
                    form.find("#btnTambahItem").html('<i class="ti ti-plus"></i> Tambah Item');
                } else {
                    // Tambah row baru
                    baris += 1;
                    let newRow = `<tr id="${baris}" class="hover:bg-slate-50 transition-colors">
                        <input type="hidden" name="tanggal_item[]" value="${tanggal}"/>
                        <input type="hidden" name="kode_akun_item[]" value="${kode_akun}"/>
                        <input type="hidden" name="debet_kredit_item[]" value="${debet_kredit}"/>
                        <input type="hidden" name="jumlah_item[]" value="${jumlah}"/>
                        <input type="hidden" name="keterangan_item[]" value="${keterangan}"/>
                        <input type="hidden" name="kode_peruntukan_item[]" value="${kode_peruntukan}"/>
                        <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}"/>
                        <td class="px-3 py-2">${tanggal}</td>
                        <td class="px-3 py-2 font-medium text-slate-700">${nama_akun}</td>
                        <td class="px-3 py-2">${keterangan}</td>
                        <td class="px-3 py-2 text-right jmldebet text-emerald-600 font-semibold">${debet ? debet : '-'}</td>
                        <td class="px-3 py-2 text-right jmlkredit text-red-600 font-semibold">${kredit ? kredit : '-'}</td>
                        <td class="px-3 py-2"><span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] font-bold border border-blue-100">${kode_cabang}</span></td>
                        <td class="px-3 py-2 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="#" id="${baris}" class="edit w-6 h-6 flex items-center justify-center bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors"><i class="ti ti-edit text-xs"></i></a>
                                <a href="#" id="${baris}" class="delete w-6 h-6 flex items-center justify-center bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors"><i class="ti ti-trash text-xs"></i></a>
                            </div>
                        </td>
                    </tr>`;
                    form.find("#loadjurnalumum").append(newRow);
                }
                resetForm();
                calculateTotal();
            }
        });

        form.on('click', '.delete', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#003d9e",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!"
            }).then((result) => {
                if (result.isConfirmed) {
                    const editId = form.find("#btnTambahItem").data('edit-id');
                    if (editId == id) {
                        form.find("#btnTambahItem").removeData('edit-id');
                        form.find("#btnTambahItem").html('<i class="ti ti-plus"></i> Tambah Item');
                        resetForm();
                    }
                    $(`#${id}`).remove();
                    calculateTotal();
                }
            });
        });

        form.on('click', '.edit', function(e) {
            e.preventDefault();
            const id = $(this).attr("id");
            const row = form.find(`#${id}`);

            const currentEditId = form.find("#btnTambahItem").data('edit-id');
            if (currentEditId && currentEditId != id) {
                resetForm();
            }

            // Ambil data dari row
            const tanggal = row.find('input[name="tanggal_item[]"]').val();
            const kode_akun = row.find('input[name="kode_akun_item[]"]').val();
            const jumlah = row.find('input[name="jumlah_item[]"]').val();
            const keterangan = row.find('input[name="keterangan_item[]"]').val();
            const debet_kredit = row.find('input[name="debet_kredit_item[]"]').val();
            const kode_cabang = row.find('input[name="kode_cabang_item[]"]').val();

            // Isi form dengan data yang akan diedit
            form.find("#tanggal").val(tanggal);
            form.find('.select2Kodeakun').val(kode_akun).trigger('change');
            form.find("#jumlah").val(jumlah);
            form.find("#keterangan").val(keterangan);
            form.find("#debet_kredit").val(debet_kredit).trigger('change');

            setTimeout(function() {
                const userBranch = "{{ auth()->user()->kode_cabang }}";
                if ((userBranch === 'PST' || userBranch === '') && kode_cabang) {
                    form.find('.select2Kodecabang').val(kode_cabang).trigger('change');
                }
            }, 100);

            // Set mode edit
            form.find("#btnTambahItem").data('edit-id', id);
            form.find("#btnTambahItem").html('<i class="ti ti-edit"></i> Update Item');

            $('html, body').animate({
                scrollTop: form.find("#tanggal").offset().top - 100
            }, 500);
        });

        function resetForm() {
            form.find('.select2Kodeakun').val('').trigger("change");
            form.find("#debet_kredit").val("").trigger("change");
            form.find("#jumlah").val("");
            const userBranch = "{{ auth()->user()->kode_cabang }}";
            if (userBranch === 'PST' || userBranch === '') {
                form.find('.select2Kodecabang').val('').trigger("change");
            }

            if (form.find("#btnTambahItem").data('edit-id')) {
                form.find("#btnTambahItem").removeData('edit-id');
                form.find("#btnTambahItem").html('<i class="ti ti-plus"></i> Tambah Item');
            }
        }

        form.submit(function() {
            if (form.find("#loadjurnalumum").children().length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jurnal Umum Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#loadjurnalumum").focus();
                    },
                });
                return false;
            } else if (total_debet_set != total_kredit_set) {
                Swal.fire({
                    title: "Oops!",
                    text: "Total Debet Tidak Sama Dengan Total Kredit !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#loadjurnalumum").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
