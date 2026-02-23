@foreach ($produk as $d)
    @php
        $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->saldo_akhir));
        $jumlah_dus = $jumlah[0];
        $jumlah_pack = $jumlah[1];
        $jumlah_pcs = $jumlah[2];
        $desimal = formatAngkaDesimal3($d->saldo_akhir);
    @endphp
    <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100">
        <td class="px-3 py-2.5 text-sm text-slate-600 whitespace-nowrap">
            <input type="hidden" class="kode_produk" name="kode_produk[]"
                value="{{ $d->kode_produk }}">
            <input type="hidden" class="isi_pcs_dus" name="isi_pcs_dus[]"
                value="{{ $d->isi_pcs_dus }}">
            <input type="hidden" class="isi_pcs_pack" name="isi_pcs_pack[]"
                value="{{ $d->isi_pcs_pack }}">
            {{ $d->kode_produk }}
        </td>
        <td class="px-3 py-2.5 text-slate-800 text-sm whitespace-nowrap">{{ $d->nama_produk }}</td>
        <td class="px-2 py-1.5" style="width: 90px;">
            @if ($readonly)
                <input type="hidden" name="jml_dus[]" value="{{ formatAngka($jumlah_dus) }}">
                <span class="text-slate-600 text-sm text-right block">{{ formatAngka($jumlah_dus) }}</span>
            @else
                <input type="text" name="jml_dus[]" value="{{ formatAngka($jumlah_dus) }}"
                    style="width: 80px;"
                    class="px-2 py-1.5 bg-white border border-slate-300 rounded text-sm text-right focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors money">
            @endif
        </td>
        <td class="px-2 py-1.5" style="width: 90px;">
            @if ($readonly)
                <input type="hidden" name="jml_pack[]" value="{{ formatAngka($jumlah_pack) }}">
                <span class="text-slate-600 text-sm text-right block">{{ formatAngka($jumlah_pack) }}</span>
            @else
                <input type="text" name="jml_pack[]"
                    value="{{ !empty($d->isi_pcs_pack) ? formatAngka($jumlah_pack) : '-' }}" style="width: 80px;"
                    class="px-2 py-1.5 {{ empty($d->isi_pcs_pack) ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-white text-slate-700 focus:border-[#003d9e] focus:ring-[#003d9e]' }} border border-slate-300 rounded text-sm text-right focus:outline-none focus:ring-1 transition-colors money"
                    {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}>
            @endif
        </td>
        <td class="px-2 py-1.5" style="width: 90px;">
            @if ($readonly)
                <input type="hidden" name="jml_pcs[]" value="{{ formatAngka($jumlah_pcs) }}">
                <span class="text-slate-600 text-sm text-right block">{{ formatAngka($jumlah_pcs) }}</span>
            @else
                <input type="text" name="jml_pcs[]" value="{{ formatAngka($jumlah_pcs) }}"
                    style="width: 80px;"
                    class="px-2 py-1.5 bg-white border border-slate-300 rounded text-sm text-right focus:outline-none focus:border-[#003d9e] focus:ring-1 focus:ring-[#003d9e] transition-colors money">
            @endif
        </td>
    </tr>
@endforeach

<script>
    $(".money").maskMoney();
</script>
